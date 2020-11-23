var mapExporter = function () {
    var value = this.value;
    var quantities = value.quantity;
    var container = value.container_count;
    var weight = value.weight;

    var myDate = value.arrival_date;
    var myDateSplit = myDate.split(" ");

    var values = {
        shipper_name: value.shipper_name,
        shipper_address: value.shipper_address,
        ship_registered_in: value.ship_registered_in,
        total_quantity: parseInt(quantities),
        total_container_count: parseInt(container),
        total_weight: parseInt(weight),
        slug_shipper_name: value.slug_shipper_name,
        slug_ship_register_in: value.slug_ship_register_in,
        shipment: 1,
        consignee_name: value.consignee_name,
        consignee_address: value.consignee_address,
        country_of_origin: value.country_of_origin,
        notify_name: value.notify_name,
        us_port: value.us_port,
        foreign_port: value.foreign_port,
        bill_of_lading: value.bill_of_lading,
        carrier: value.carrier,
        vessel: value.vessel,
        container_number: value.container_number,
        product_description: value.product_description,

        arrival_date: value.arrival_date,
        quantity: parseInt(quantities),
        container_count: parseInt(container),
        weight: parseInt(weight),
        cbm: value.cbm,
        house_vs_master: value.house_vs_master,
        other_info: value.other_info,
        place_of_receipt: value.place_of_receipt,
        slug_consignee_name: value.slug_consignee_name,
        slug_country_of_origin: value.slug_country_of_origin,
        slug_country_ori_shipper: value.slug_country_ori_shipper,
        slug_country_ori_consignee: value.slug_country_ori_consignee,
        dateGroup: {}
    };

    if (values.dateGroup.hasOwnProperty(myDateSplit[0]))
        values.dateGroup[myDateSplit[0]] += 1;
    else
        values.dateGroup[myDateSplit[0]] = 1;

    emit({slug_consignee_name: value.slug_consignee_name, slug_shipper_name: value.slug_shipper_name}, values);
}

var reduceExporter = function (key, vals) {
    var r = {total_quantity: 0, total_container_count: 0, total_weight: 0, shipment: 0,dateGroup: {}};
    for (var i = 0; i < vals.length; i++) {
        r.shipper_name = vals[i].shipper_name;
        r.shipper_address = vals[i].shipper_address;
        r.ship_registered_in = vals[i].ship_registered_in;
        r.total_quantity += vals[i].total_quantity;
        r.total_container_count += vals[i].total_container_count;
        r.total_weight += vals[i].total_weight;
        r.slug_shipper_name = vals[i].slug_shipper_name;
        r.slug_ship_register_in = vals[i].slug_ship_register_in;
        r.shipment += vals[i].shipment;
        r.product_description = vals[i].product_description;
        r.consignee_name = vals[i].consignee_name;
        r.consignee_address = vals[i].consignee_address;
        r.notify_name = vals[i].notify_name;
        r.us_port = vals[i].us_port;
        r.foreign_port = vals[i].foreign_port;
        r.bill_of_lading = vals[i].bill_of_lading;
        r.carrier = vals[i].carrier;
        r.vessel = vals[i].vessel;
        r.container_number = vals[i].container_number;
        r.country_of_origin = vals[i].country_of_origin;
        r.ship_registered_in = vals[i].ship_registered_in;
        r.arrival_date = vals[i].arrival_date;
        r.quantity = vals[i].quantity;
        r.container_count = vals[i].container_count;
        r.product_description= vals[i].product_description;
        r.weight = vals[i].weight;
        r.cbm = vals[i].cbm;
        r.house_vs_master = vals[i].house_vs_master;
        r.other_info = vals[i].other_info;
        r.place_of_receipt = vals[i].place_of_receipt;
        r.slug_consignee_name = vals[i].slug_consignee_name;
        r.slug_country_of_origin = vals[i].slug_country_of_origin;
        r.slug_country_ori_shipper = vals[i].slug_country_ori_shipper;
        r.slug_country_ori_consignee = vals[i].slug_country_ori_consignee;

        for (thisDate in vals[i].dateGroup) {
            if (r.dateGroup[thisDate] == null) {
                r.dateGroup[thisDate] = 1;
            } else {
                r.dateGroup[thisDate] += vals[i].dateGroup[thisDate];
            }
        }
    }
    return r;
}

var resSup1 = db.import_document_rewrite.mapReduce(mapExporter, reduceExporter,
    { out: "import_document_exporter"});