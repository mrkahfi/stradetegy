<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\DocumentBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use PDO;


class MapReduceSuppliersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('jariff:cron:map-reduce-suppliers')
            ->setDescription('Mengeksekusi mapping suppliers')
            ->addOption('plan-limit', 18, InputOption::VALUE_OPTIONAL, 'limit terendah selama 18 bulan ke belakang dari hari ini.')
            ->setHelp(<<<EOF
<info>jariff:cron:map-reduce-suppliers</info>
Command digunkan untuk mengupdate data baru untuk membantu pencarian di tab suppliers

Syntax :
php app/console jariff:cron:map-reduce-suppliers
EOF
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //semua cron file dengan command seperti ini harus ada file lock nya,
        //untuk menghindari duplikasi proses
        //nama file nya 
        // 'lock-'.str_replace(':', '-', 'nama command di definisi fungsi configure di atas').'.txt'
        $fp = fopen(getcwd() . '/lock/jariff-map-reduce-suppliers.lock', 'w');

        if (flock($fp, LOCK_EX | LOCK_NB)) {

            $dateRun = new \DateTime('now');
            $output->writeln('Start map reduce Suppliers at ' . $dateRun->format("d-m-Y H:i:s"));


            $server = $this->getContainer()->getParameter('mongo_server');
            $db = $this->getContainer()->getParameter('mongo_database_name');

            $mongo = new \MongoClient($server);

            $db = $mongo->selectDB($db);

            $planLimit = $input->getOption('plan-limit');

            $rangeLimit = array();

            if (!empty($planLimit)) {
                $lastmonth = mktime(0, 0, 0, date("m") - intval($planLimit), date("d"), date("Y"));
                $startDate = date("Y-m-d", $lastmonth);

                $endDate = $dateRun->format("Y-m-d");

                $rangeLimit = array(
                    '$or' => array(
                        array(
                            'arrival_date' => array(
                                '$gte' => $startDate,
                                '$lt' => $endDate
                            )
                        )
                    )
                );
            } else {
                $planLimit = '';
            }


            // Digunakan untuk memisahkan consignee_name
            $this->runSuppliers1($db, $output, $planLimit, $rangeLimit);
            // Digunakan untuk memisahkan shipper sesuai diatas
            $this->runSuppliers2($db, $output, $planLimit);
            // Digunakan untuk memimsah berdasarkan slug
            $this->runSuppliers3($db, $output, $planLimit);
            // Digunakan untuk menciptakan ID Object
            $this->runSuppliers4($db, $output, $planLimit);


            $dateRun = new \DateTime('now');
            $output->writeln('Yuhui, stop reduce Suppliers at ' . $dateRun->format("d-m-Y H:i:s"));


            flock($fp, LOCK_UN);
        } else {
            $output->writeln('Program pengantri untuk aktifasi sedang berjalan, terasa aneh ? coba cek log nya.');
        }
    }

    public function runSuppliers1($db, $output, $plan_limit, $range = array())
    {
        $map = new \MongoCode('function(){
	myDate = this.arrival_date;
	var value = {
		shipper_name: this.shipper_name,
		shipper_address:this.shipper_address,
		notify_name: this.notify_name,
		notify_address: this.notify_address,
		consignee_address:this.consignee_address,
		ship_registered_in: this.ship_registered_in,
		other_info: this.other_info,
		product_description:this.product_description,
		arrival_date: this.arrival_date,
		dateGroup:{},
		shipment: 1,
		container_count: parseInt(this.container_count),
		country_of_origin: this.country_of_origin,
		us_port: this.us_port,
		foreign_port: this.foreign_port
	};

	value.dateGroup[myDate] = 1;
	emit(this.consignee_name,value);
}');
        $reduce = new \MongoCode('function(key,vals){
 	var r = {shipment: 0,dateGroup:{},container_count:0}
	for(var i =0; i < vals.length; i++){
		for(thisDate in vals[i].dateGroup){
		    if(r.dateGroup[thisDate] == null){
			r.dateGroup[thisDate] = 1;
		    }else{
			r.dateGroup[thisDate] += vals[i].dateGroup[thisDate];
		    }
		}
		r.shipper_name = vals[i].shipper_name,
		r.shipper_address = vals[i].shipper_address;
		r.notify_name = vals[i].notify_name;
		r.notify_address = vals[i].notify_address;
		r.consignee_address = vals[i].consignee_address;
		r.other_info = vals[i].other_info;
		r.ship_registered_in = vals[i].ship_registered_in;
		r.product_description = vals[i].product_description;
		r.arrival_date = vals[i].arrival_date;
		r.shipment += vals[i].shipment;
		r.container_count += vals[i].container_count;
		r.country_of_origin = vals[i].country_of_origin;
		r.us_port = vals[i].us_port;
		r.foreign_port = vals[i].foreign_port;
	}
return r;
}');

        $sup = $db->command(array(
            'mapreduce' => 'import_document',
            'map' => $map,
            'reduce' => $reduce,
            'query' => array_merge(array(
                    'shipper_name' => array('$ne' => '-NOT AVAILABLE-'),
                    '$or' =>
                    array(
                        array(
                            'shipper_name' => array('$ne' => "")
                        )
                    )
                ),
                $range
            ),
            'out' => 'suppliers' . $plan_limit
        ), array("timeout" => 1000000000));


        if ($sup['ok'])
            $output->writeln('Success execute suppliers1' . "\n");
        else {
            $output->writeln('error message ' . $sup['errmsg'] . "\n");
            exit(1);
        }

    }

    public function runSuppliers2($db, $output, $plan_limit)
    {
        $map = new \MongoCode('function(){
	var id = this._id;
	var value = this.value;
	var slugGenerate = value.shipper_name.toLowerCase().replace(/ /g,"-").replace(/[^\w-]+/g,"");
	var consignee_address = value.consignee_address.split("Show Map")[0];
	emit(value.shipper_name,{
		shipper_address:value.shipper_address,
		other_info:value.other_info,
		consignee_address:consignee_address,
		notify_name: value.notify_name,
		notify_address: value.notify_address,
		ship_registered_in: value.ship_registered_in,
		consignee: [{"consignee_name":id,"consignee_address":consignee_address,"country_of_origin":value.country_of_origin,"container_count":value.container_count}],
		product_description: value.product_description,
		arrival_date: value.arrival_date,
		customer: 1,
		shipment: value.shipment,
		last_import_company: id,
		slug: slugGenerate,
		dateGroup: value.dateGroup,
		container_count: value.container_count,
		country_of_origin: value.country_of_origin,
		us_port: value.us_port,
		foreign_port: value.foreign_port
	})
}');


        $reduce = new \MongoCode('function(key,vals){
 	var r = {shipment: 0,consignee : [],customer:0,container_count:0};
	for(var i =0; i < vals.length; i++){
		r.shipper_address = vals[i].shipper_address;
		r.other_info = vals[i].other_info;
		r.consignee_address = vals[i].consignee_address;
		r.ship_registered_in = vals[i].ship_registered_in;
		r.notify_name = vals[i].notify_name;
		r.notify_address = vals[i].notify_address;
		r.customer += vals[i].customer;
		r.shipment += vals[i].shipment;
		r.last_import_company = vals[i].last_import_company;
		r.container_count += vals[i].container_count;
		r.consignee.push({"consignee_name": vals[i].last_import_company,"consignee_address":vals[i].consignee_address,"country_of_origin":vals[i].country_of_origin,"container_count":vals[i].container_count});
		r.product_description = vals[i].product_description;
		r.arrival_date = vals[i].arrival_date;
		r.slug = vals[i].slug;
		r.dateGroup = vals[i].dateGroup;
		r.country_of_origin = vals[i].country_of_origin;
		r.us_port = vals[i].us_port;
		r.foreign_port = vals[i].foreign_port;
	}
return r;
}');

        $sup = $db->command(array(
                'mapreduce' => 'suppliers' . $plan_limit,
                'map' => $map,
                'reduce' => $reduce,
                'out' => 'suppliers' . $plan_limit
            ), array("timeout" => 1000000000)
        );

        if ($sup['ok'])
            $output->writeln('Success execute suppliers2' . "\n");
        else
            $output->writeln('error message ' . $sup['errmsg'] . "\n");
    }

    public function runSuppliers3($db, $output, $plan_limit)
    {
        $map = new \MongoCode('function(){
	var id = this._id;
	var value = this.value;
	var shipper_address = value.shipper_address.split("Show Map")[0];
	emit(value.slug,{
		shipper_name: id,
		shipper_address:shipper_address,
		other_info:value.other_info,
		notify_name: value.notify_name,
		notify_address: value.notify_address,
		consignee_address:value.consignee_address,
		ship_registered_in: value.ship_registered_in,
		consignee: value.consignee,
		product_description: value.product_description,
		arrival_date: value.arrival_date,
		last_import_company: value.last_import_company,
		customer: value.customer,
		shipment: value.shipment,
		dateGroup: value.dateGroup,
		container_count: value.container_count,
		country_of_origin: value.country_of_origin,
		us_port: value.us_port,
		foreign_port: value.foreign_port
	})
}');
        $reduce = new \MongoCode('function(key,vals){
 	var r = {shipment: 0,customer:0,container_count:0};
	for(var i =0; i < vals.length; i++){
		r.shipper_name = vals[i].shipper_name;
		r.shipper_address = vals[i].shipper_address;
		r.consignee_address = vals[i].consignee_address;
		r.notify_name = vals[i].notify_name;
		r.notify_address = vals[i].notify_address;
		r.last_import_company= vals[i].last_import_company;
		r.other_info = vals[i].other_info;
		r.ship_registered_in = vals[i].ship_registered_in;
		r.customer = vals[i].customer;
		r.shipment += vals[i].shipment;
		r.consignee = vals[i].consignee;
		r.product_description = vals[i].product_description;
		r.dateGroup = vals[i].dateGroup;
		r.arrival_date = vals[i].arrival_date;
		r.container_count += vals[i].container_count;
		r.country_of_origin = vals[i].country_of_origin;
		r.us_port = vals[i].us_port;
		r.foreign_port = vals[i].foreign_port;
	}
return r;
}');

        $sup = $db->command(array(
                'mapreduce' => 'suppliers' . $plan_limit,
                'map' => $map,
                'reduce' => $reduce,
                'out' => 'suppliers' . $plan_limit
            ), array("timeout" => 1000000000)
        );

        if ($sup['ok'])
            $output->writeln('Success execute suppliers3' . "\n");
        else
            $output->writeln('error message ' . $sup['errmsg'] . "\n");
    }

    public function runSuppliers4($db, $output, $plan_limit)
    {
        $map = new \MongoCode('function(){
	var id = this._id;
	var value = this.value;
	var slugCountry = value.ship_registered_in.toLowerCase().replace(/ /g,"").replace(/[^\w-]+/g,"");
	emit(ObjectId(),{
		shipper_name: value.shipper_name,
		shipper_address:value.shipper_address,
		ship_registered_in: value.ship_registered_in,
		notify_name: value.notify_name,
		notify_address: value.notify_address,
		consignee: value.consignee,
		product_description: value.product_description,
		arrival_date: value.arrival_date,
		customer: value.customer,
		shipment: value.shipment,
		dateGroup: value.dateGroup,
		slug: id,
		last_import_company: value.last_import_company,
		slugCountry: slugCountry,
		container_count: value.container_count,
		country_of_origin: value.country_of_origin,
		us_port: value.us_port,
		foreign_port: value.foreign_port
	})
}');
        $reduce = new \MongoCode('function(key,vals){
 	var r = {shipment: 0,customer:0}
 	var duplicat = 0;
	for(var i =0; i < vals.length; i++){
		r.shipper_name = vals[i].shipper_name;
		r.shipper_address = vals[i].shipper_address;
		r.other_info = vals[i].other_info;
		r.ship_registered_in = vals[i].ship_registered_in;
		r.customer = vals[i].customer;
		r.shipment = vals[i].shipment;
		r.consignee = vals[i].consignee;
		r.product_description = vals[i].product_description;
		r.slug = vals[i].slug;
		r.dateGroup = vals[i].dateGroup;
		r.last_import_company= vals[i].last_import_company;
		r.arrival_date = vals[i].arrival_date;
		r.slugCountry = vals[i].slugCountry;
		r.container_count = vals[i].container_count;
		r.country_of_origin = vals[i].country_of_origin;
		r.us_port = vals[i].us_port;
		r.foreign_port = vals[i].foreign_port;
	}
return r;
}');

        $sup = $db->command(array(
                'mapreduce' => 'suppliers' . $plan_limit,
                'map' => $map,
                'reduce' => $reduce,
                'out' => 'suppliers' . $plan_limit
            ), array("timeout" => 1000000000)
        );

        if ($sup['ok'])
            $output->writeln('Success execute suppliers4' . "\n");
        else
            $output->writeln('error message ' . $sup['errmsg'] . "\n");
    }
}