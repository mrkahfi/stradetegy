var mapExporter = function () {
    var value = this.value;

    var checkDateGroup = value.dateGroup;

    var values = {
        company_name: value.shipper_name,
        company_address: value.shipper_address,
        ship_registered_in: value.ship_registered_in,
        company_as: 'exporter',
        total_quantity: parseInt(value.total_quantity),
        total_container_count: parseInt(value.total_container_count),
        total_weight: parseInt(value.total_weight),
        slug_shipper_name: value.slug_shipper_name,
        slug_ship_register_in: value.slug_ship_register_in,
        shipment: value.shipment,
        customer: 1,
        dateGroup: {},
        slug_country_ori: value.slug_country_ori_shipper
    };

    for (thisDate in checkDateGroup) {
        if (values.dateGroup.hasOwnProperty(thisDate))
            values.dateGroup[thisDate] += value.dateGroup[thisDate];
        else
            values.dateGroup[thisDate] = value.dateGroup[thisDate];
    }

    emit(this._id.slug_shipper_name, values);
}

var reduceExporter = function (key, vals) {
    var r = {total_quantity: 0, total_container_count: 0, total_weight: 0, shipment: 0, customer: 0, dateGroup: {}};

    for (var i = 0; i < vals.length; i++) {
        r.company_name = vals[i].company_name;
        r.company_address = vals[i].company_address;
        r.ship_registered_in = vals[i].ship_registered_in;
        r.company_as = vals[i].company_as;
        r.total_quantity += vals[i].total_quantity;
        r.total_container_count += vals[i].total_container_count;
        r.total_weight += vals[i].total_weight;
        r.slug_shipper_name = vals[i].slug_shipper_name;
        r.slug_ship_register_in = vals[i].slug_ship_register_in;
        r.shipment += vals[i].shipment;
        r.customer += vals[i].customer;
        r.slug_country_ori = vals[i].slug_country_ori;

        for (thisDate in vals[i].dateGroup) {
            if (r.dateGroup[thisDate] == null) {
                r.dateGroup[thisDate] = vals[i].dateGroup[thisDate];
            } else {
                r.dateGroup[thisDate] += vals[i].dateGroup[thisDate];
            }
        }
    }
    return r;
}

var resSup1 = db.import_document_exporter.mapReduce(mapExporter, reduceExporter, { out: "import_document_exporter_real"});