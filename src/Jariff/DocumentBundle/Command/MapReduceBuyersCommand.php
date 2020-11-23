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


class MapReduceBuyersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('jariff:cron:map-reduce-buyers')
            ->setDescription('Mengeksekusi mapping buyers')
            ->addOption('plan-limit', 18, InputOption::VALUE_OPTIONAL, 'limit terendah selama 18 bulan ke belakang dari hari ini.')
            ->setHelp(<<<EOF
<info>jariff:cron:map-reduce-buyers</info>
Command digunkan untuk mengupdate data baru untuk membantu pencarian di tab Buyers

Syntax :
php app/console jariff:cron:map-reduce-buyers
EOF
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //semua cron file dengan command seperti ini harus ada file lock nya,
        //untuk menghindari duplikasi proses
        //nama file nya 
        // 'lock-'.str_replace(':', '-', 'nama command di definisi fungsi configure di atas').'.txt'
        $fp = fopen(getcwd() . '/lock/jariff-map-reduce-buyers.lock', 'w');

        if (flock($fp, LOCK_EX | LOCK_NB)) {

            $dateRun = new \DateTime('now');
            $output->writeln('Start map reduce buyers at ' . $dateRun->format("d-m-Y H:i:s"));

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



            // Digunakan untuk memisahkan consignee_name,dan shipper
            $this->runBuyers1($db, $output, $planLimit, $rangeLimit);
            // Digunakan untuk memisahkan consignee berdasarkan slug
            $this->runBuyers2($db, $output, $planLimit);
            // Digunakan untuk membuat ID Object
            $this->runBuyers3($db, $output, $planLimit);


            $dateRun = new \DateTime('now');
            $output->writeln('Yuhui, stop reduce Buyers at ' . $dateRun->format("d-m-Y H:i:s"));


            flock($fp, LOCK_UN);
        } else {
            $output->writeln('Program pengantri untuk aktifasi sedang berjalan, terasa aneh ? coba cek log nya.');
        }
    }

    public function runBuyers1($db, $output, $planLimit, $range = array())
    {

        $map = new \MongoCode('function(){
	myDate = this.arrival_date;
	var slugGenerate = this.consignee_name.toLowerCase().replace(/ /g,"-").replace(/[^\w-]+/g,"");
	var slugCountry = this.country_of_origin.toLowerCase().replace(/ /g,"").replace(/[^\w-]+/g,"");
	var value = {
		consignee_address:this.consignee_address,
		notify_name: this.notify_name,
		shipper_address: this.shipper_address,
		notify_address: this.notify_address,
		product_description:this.product_description,
		arrival_date: this.arrival_date,
		carrier: this.carrier,
		foreign_port: this.foreign_port,
		country_of_origin: this.country_of_origin,
		place_of_receipt: this.place_of_receipt,
		bill_of_lading: this.bill_of_lading,
		shipment: 1,
		slug: slugGenerate,
		slugCountry: slugCountry,
		dateGroup: {},
		container_count: parseInt(this.container_count),
		ship_registered_in: this.ship_registered_in,
		us_port: this.us_port
	};

	value.dateGroup[myDate] = 1;

	emit({ consignee : this.consignee_name,shipper : this.shipper_name },value);
}');


        $reduce = new \MongoCode('function(key,vals){
 	var r = {shipment: 0,dateGroup:{},container_count: 0}
	for(var i =0; i < vals.length; i++){
		r.consignee_address = vals[i].consignee_address;
		r.shipper_address = vals[i].shipper_address;
		r.notify_name = vals[i].notify_name;
		r.notify_address = vals[i].notify_address;
		r.product_description = vals[i].product_description;
		r.arrival_date = vals[i].arrival_date;
		r.carrier = vals[i].carrier;
		r.foreign_port = vals[i].foreign_port;
		r.country_of_origin = vals[i].country_of_origin;
		r.place_of_receipt = vals[i].place_of_receipt;
		r.bill_of_lading = vals[i].bill_of_lading;
		r.shipment += vals[i].shipment;
		r.slug = vals[i].slug;
		r.slugCountry = vals[i].slugCountry;
		r.container_count += vals[i].container_count;
		r.ship_registered_in = vals[i].ship_registered_in;
		r.us_port = vals[i].us_port;
		for(thisDate in vals[i].dateGroup){
		    if(r.dateGroup[thisDate] == null){
			r.dateGroup[thisDate] = 1;
		    }else{
			r.dateGroup[thisDate] += vals[i].dateGroup[thisDate];
		    }
		}
	}
return r;
}');

        $sup = $db->command(array(
            'mapreduce' => 'import_document',
            'map' => $map,
            'reduce' => $reduce,
            'query' => array_merge(array(
                    'consignee_name' => array('$ne' => '-NOT AVAILABLE-'),
                    '$or' =>
                    array(
                        array(
                            'consignee_name' => array('$ne' => "")
                        )
                    )
                ),
                $range
            ),
            'out' => 'buyers' . $planLimit
        ), array("timeout" => 1000000000));

        if ($sup['ok'])
            $output->writeln('Success execute Buyers1' . "\n");
        else {
            $output->writeln('error message ' . $sup['errmsg'] . "\n");
            exit(1);
        }

    }

    public function runBuyers2($db, $output, $planLimit)
    {
        $map = new \MongoCode('function(){
	var id = this._id;
	var value = this.value;
	var shipper_address = value.shipper_address.split("Show Map")[0];
	emit(value.slug,{
		consignee_name:id.consignee,
		consignee_address:value.consignee_address,
		shipper_address: shipper_address,
		shipper_name:id.shipper,
		notify_name: value.notify_name,
		notify_address: value.notify_address,
		product_description:value.product_description,
		arrival_date: value.arrival_date,
		carrier: value.carrier,
		foreign_port: value.foreign_port,
		country_of_origin: value.country_of_origin,
		place_of_receipt: value.place_of_receipt,
		bill_of_lading: value.bill_of_lading,
		shipment: value.shipment,
		slugCountry: value.slugCountry,
		shipper: [{"shipper_name":id.shipper,"shipper_address":shipper_address,"country_of_origin" : value.country_of_origin, "container_count" : value.container_count}],
		dateGroup: value.dateGroup,
		last_export_company: id.shipper,
		container_count: value.container_count,
		ship_registered_in: value.ship_registered_in,
		us_port: value.us_port,
	})
}');


        $reduce = new \MongoCode('function(key,vals){
 	var r = {shipment: 0,shipper: [],container_count:0}
 	var duplicat = 0;
	for(var i =0; i < vals.length; i++){
		r.consignee_name = vals[i].consignee_name;
		r.consignee_address = vals[i].consignee_address;
		r.shipper_address = vals[i].shipper_address;
		r.notify_name = vals[i].notify_name;
		r.notify_address = vals[i].notify_address;
		r.product_description = vals[i].product_description;
		r.arrival_date = vals[i].arrival_date;
		r.carrier = vals[i].carrier;
		r.foreign_port = vals[i].foreign_port;
		r.country_of_origin = vals[i].country_of_origin;
		r.place_of_receipt = vals[i].place_of_receipt;
		r.bill_of_lading = vals[i].bill_of_lading;
		r.shipment += vals[i].shipment;
		r.slugCountry = vals[i].slugCountry;
		r.dateGroup = vals[i].dateGroup;
		r.last_export_company = vals[i].last_export_company;
		r.shipper.push({"shipper_name":vals[i].last_export_company,"shipper_address":vals[i].shipper_address,"country_of_origin" : vals[i].country_of_origin, "container_count" : vals[i].container_count});
		r.container_count += vals[i].container_count;
		r.ship_registered_in = vals[i].ship_registered_in;
		r.us_port = vals[i].us_port;
		}
return r;
}');

        $sup = $db->command(array(
                'mapreduce' => 'buyers' . $planLimit,
                'map' => $map,
                'reduce' => $reduce,
                'out' => 'buyers' . $planLimit
            ), array("timeout" => 1000000000)
        );

        if ($sup['ok'])
            $output->writeln('Success execute Buyers2' . "\n");
        else
            $output->writeln('error message ' . $sup['errmsg'] . "\n");
    }

    public function runBuyers3($db, $output, $planLimit)
    {
        $map = new \MongoCode('function(){
	var id = this._id;
	var value = this.value;
	var consignee_address = value.consignee_address.split("Show Map")[0];
	emit(ObjectId(),{
		consignee_name:value.consignee_name,
		consignee_address:consignee_address,
		shipper_name: value.shipper_name,
		notify_name: value.notify_name,
		notify_address: value.notify_address,
		product_description:value.product_description,
		arrival_date: value.arrival_date,
		carrier: value.carrier,
		foreign_port: value.foreign_port,
		country_of_origin: value.country_of_origin,
		place_of_receipt: value.place_of_receipt,
		bill_of_lading: value.bill_of_lading,
		shipment: value.shipment,
		slug: id,
		slugCountry: value.slugCountry,
		shipper: value.shipper,
		dateGroup: value.dateGroup,
		last_export_company: value.last_export_company,
		container_count: value.container_count,
		ship_registered_in: value.ship_registered_in,
		us_port: value.us_port,
	})
}');
        $reduce = new \MongoCode('function(key,vals){
 	var r = {shipment: 0}
 	var duplicat = 0;
	for(var i =0; i < vals.length; i++){
		r.consignee_name = vals[i].consignee_name;
		r.consignee_address = vals[i].consignee_address;
		r.notify_name = vals[i].notify_name;
		r.notify_address = vals[i].notify_address;
		r.product_description = vals[i].product_description;
		r.arrival_date = vals[i].arrival_date;
		r.carrier = vals[i].carrier;
		r.foreign_port = vals[i].foreign_port;
		r.country_of_origin = vals[i].country_of_origin;
		r.place_of_receipt = vals[i].place_of_receipt;
		r.bill_of_lading = vals[i].bill_of_lading;
		r.shipment += vals[i].shipment;
		r.slug = vals[i].slug;
		r.slugCountry = vals[i].slugCountry;
		r.dateGroup = vals[i].dateGroup;
		r.last_export_company = vals[i].last_export_company;
		r.shipper = vals[i].shipper;
		r.container_count = vals[i].container_count;
		r.ship_registered_in = vals[i].ship_registered_in;
		r.us_port = vals[i].us_port;
	}
return r;
}');

        $sup = $db->command(array(
                'mapreduce' => 'buyers' . $planLimit,
                'map' => $map,
                'reduce' => $reduce,
                'out' => 'buyers' . $planLimit
            ), array("timeout" => 1000000000)
        );

        if ($sup['ok'])
            $output->writeln('Success execute Buyers3' . "\n");
        else
            $output->writeln('error message ' . $sup['errmsg'] . "\n");
    }
}