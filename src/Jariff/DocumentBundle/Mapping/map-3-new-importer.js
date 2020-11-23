var mapExporter = function () {
    var value = this.value;

    var checkDateGroup = value.dateGroup;

    var values = {
        company_name: value.consignee_name,
        company_address: value.consignee_address,
        country_of_origin: value.country_of_origin,
        company_as: 'importer',
        total_quantity: parseInt(value.total_quantity),
        total_container_count: parseInt(value.total_container_count),
        total_weight: parseInt(value.total_weight),
        slug_consignee_name: value.slug_consignee_name,
        slug_country_of_origin: value.slug_country_of_origin,
        shipment: value.shipment,
        customer: 1,
        dateGroup: {},
        slug_country_ori: value.slug_country_ori_consignee,
        company_join: [
            {
                company_join_name: value.shipper_name,
                company_join_address: value.shipper_address,
                country: value.ship_registered_in,
                notify_name: value.notify_name,
                us_port: value.us_port,
                foreign_port: value.foreign_port,
                bill_of_lading: value.bill_of_lading,
                carrier: value.carrier,
                vessel: value.vessel,
                container_number: value.container_number,
                product_description: value.product_description,
                arrival_date: value.arrival_date,
                quantity: parseInt(value.quantity),
                container_count: parseInt(value.container_count),
                weight: parseInt(value.weight),
                cbm: value.cbm,
                house_vs_master: value.house_vs_master,
                other_info: value.other_info,
                place_of_receipt: value.place_of_receipt,
                slug_company: value.slug_shipper_name,
                slug_country: value.slug_ship_register_in,
                slug_country_ori: value.slug_country_ori_shipper,
                shipment: value.shipment
            }
        ]
    };

    for (thisDate in checkDateGroup) {
        if (values.dateGroup.hasOwnProperty(thisDate))
            values.dateGroup[thisDate] += value.dateGroup[thisDate];
        else
            values.dateGroup[thisDate] = value.dateGroup[thisDate];
    }

    emit(this._id.slug_consignee_name, values);
}

var reduceExporter = function (key, vals) {
    var r = {total_quantity: 0, total_container_count: 0, total_weight: 0, shipment: 0, company_join: [], dateGroup: {}};
    for (var i = 0; i < vals.length; i++) {
        r.company_name = vals[i].company_name;
        r.company_address = vals[i].company_address;
        r.country_of_origin = vals[i].country_of_origin;
        r.company_as = vals[i].company_as;
        r.total_quantity += vals[i].total_quantity;
        r.total_container_count += vals[i].total_container_count;
        r.total_weight += vals[i].total_weight;
        r.slug_consignee_name = vals[i].slug_consignee_name;
        r.slug_country_of_origin = vals[i].slug_country_of_origin;
        r.shipment += vals[i].shipment;
        r.slug_country_ori = vals[i].slug_country_ori;
        for (j = 0; j < vals[i].company_join.length; j++) {
            r.company_join.push(
                {
                    company_join_name: vals[i].company_join[j].company_join_name,
                    company_join_address: vals[i].company_join[j].company_join_address,
                    country: vals[i].company_join[j].country,
                    notify_name: vals[i].company_join[j].notify_name,
                    us_port: vals[i].company_join[j].us_port,
                    foreign_port: vals[i].company_join[j].foreign_port,
                    bill_of_lading: vals[i].company_join[j].bill_of_lading,
                    carrier: vals[i].company_join[j].carrier,
                    vessel: vals[i].company_join[j].vessel,
                    container_number: vals[i].company_join[j].container_number,
                    product_description: vals[i].company_join[j].product_description,
                    arrival_date: vals[i].company_join[j].arrival_date,
                    quantity: vals[i].company_join[j].quantity,
                    container_count: vals[i].company_join[j].container_count,
                    weight: vals[i].company_join[j].weight,
                    cbm: vals[i].company_join[j].cbm,
                    house_vs_master: vals[i].company_join[j].house_vs_master,
                    other_info: vals[i].company_join[j].other_info,
                    place_of_receipt: vals[i].company_join[j].place_of_receipt,
                    slug_company: vals[i].company_join[j].slug_company,
                    slug_country: vals[i].company_join[j].slug_country,
                    slug_country_ori: vals[i].company_join[j].slug_country_ori,
                    shipment: vals[i].company_join[j].shipment
                }
            );
        }
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

var resSup1 = db.import_document_importer.mapReduce(mapExporter, reduceExporter,
    { out: "import_document_importer_real"});

var mapExporter = function () {
    var value = this.value;

    var checkDateGroup = value.dateGroup;

    var values = {
        company_name: value.consignee_name,
        company_address: value.consignee_address,
        country_of_origin: value.country_of_origin,
        company_as: 'importer',
        total_quantity: parseInt(value.total_quantity),
        total_container_count: parseInt(value.total_container_count),
        total_weight: parseInt(value.total_weight),
        slug_consignee_name: value.slug_consignee_name,
        slug_country_of_origin: value.slug_country_of_origin,
        shipment: value.shipment,
        customer: 1,
        dateGroup: {},
        slug_country_ori: value.slug_country_ori_consignee
    };

    for (thisDate in checkDateGroup) {
        if (values.dateGroup.hasOwnProperty(thisDate))
            values.dateGroup[thisDate] += value.dateGroup[thisDate];
        else
            values.dateGroup[thisDate] = value.dateGroup[thisDate];
    }

    emit(this._id.slug_consignee_name, values);
}

var reduceExporter = function (key, vals) {
    var r = {total_quantity: 0, total_container_count: 0, total_weight: 0, shipment: 0, company_join: [], dateGroup: {}};
    for (var i = 0; i < vals.length; i++) {
        r.company_name = vals[i].company_name;
        r.company_address = vals[i].company_address;
        r.country_of_origin = vals[i].country_of_origin;
        r.company_as = vals[i].company_as;
        r.total_quantity += vals[i].total_quantity;
        r.total_container_count += vals[i].total_container_count;
        r.total_weight += vals[i].total_weight;
        r.slug_consignee_name = vals[i].slug_consignee_name;
        r.slug_country_of_origin = vals[i].slug_country_of_origin;
        r.shipment += vals[i].shipment;
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

var resSup1 = db.import_document_importer.mapReduce(mapExporter, reduceExporter,
    { out: "import_document_importer_real"});

var mapExporter = function () {
    var value = this.value;

    var values = {
        company_join: [
            {
                company_join_name: value.shipper_name,
                company_join_address: value.shipper_address,
                country: value.ship_registered_in,
                notify_name: value.notify_name,
                us_port: value.us_port,
                foreign_port: value.foreign_port,
                bill_of_lading: value.bill_of_lading,
                carrier: value.carrier,
                vessel: value.vessel,
                container_number: value.container_number,
                product_description: value.product_description,
                arrival_date: value.arrival_date,
                quantity: parseInt(value.quantity),
                container_count: parseInt(value.container_count),
                weight: parseInt(value.weight),
                cbm: value.cbm,
                house_vs_master: value.house_vs_master,
                other_info: value.other_info,
                place_of_receipt: value.place_of_receipt,
                slug_company: value.slug_shipper_name,
                slug_country: value.slug_ship_register_in,
                slug_country_ori: value.slug_country_ori_shipper,
                shipment: value.shipment
            }
        ]
    };

    emit(this._id.slug_consignee_name, values);
}

var reduceExporter = function (key, vals) {
    var r = {company_join: []};
    for (var i = 0; i < vals.length; i++) {

        for (j = 0; j < vals[i].company_join.length; j++) {
            r.company_join.push(
                {
                    company_join_name: vals[i].company_join[j].company_join_name,
                    company_join_address: vals[i].company_join[j].company_join_address,
                    country: vals[i].company_join[j].country,
                    notify_name: vals[i].company_join[j].notify_name,
                    us_port: vals[i].company_join[j].us_port,
                    foreign_port: vals[i].company_join[j].foreign_port,
                    bill_of_lading: vals[i].company_join[j].bill_of_lading,
                    carrier: vals[i].company_join[j].carrier,
                    vessel: vals[i].company_join[j].vessel,
                    container_number: vals[i].company_join[j].container_number,
                    product_description: vals[i].company_join[j].product_description,
                    arrival_date: vals[i].company_join[j].arrival_date,
                    quantity: vals[i].company_join[j].quantity,
                    container_count: vals[i].company_join[j].container_count,
                    weight: vals[i].company_join[j].weight,
                    cbm: vals[i].company_join[j].cbm,
                    house_vs_master: vals[i].company_join[j].house_vs_master,
                    other_info: vals[i].company_join[j].other_info,
                    place_of_receipt: vals[i].company_join[j].place_of_receipt,
                    slug_company: vals[i].company_join[j].slug_company,
                    slug_country: vals[i].company_join[j].slug_country,
                    slug_country_ori: vals[i].company_join[j].slug_country_ori,
                    shipment: vals[i].company_join[j].shipment
                }
            );
        }
    }
    return r;
}

var resSup1 = db.import_document_importer.mapReduce(mapExporter, reduceExporter,
    { out: "import_document_importer_real_part_1"});


