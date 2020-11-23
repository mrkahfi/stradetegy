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
        slug_country_ori: value.slug_country_ori_shipper,
        company_join: [
            {
                company_join_name: value.consignee_name,
                company_join_address: value.consignee_address,
                country: value.country_of_origin,
                notify_name: value.notify_name,
                us_port: value.us_port,
                foreign_port: value.foreign_port,
                bill_of_lading: value.bill_of_lading,
                carrier: value.carrier,
                vessel: value.vessel,
                product_description: value.product_description,

                arrival_date: value.arrival_date,
                container_number: value.container_number,
                quantity: parseInt(value.quantity),
                container_count: parseInt(value.container_count),
                weight: parseInt(value.weight),
                cbm: value.cbm,
                house_vs_master: value.house_vs_master,
                other_info: value.other_info,
                place_of_receipt: value.place_of_receipt,
                slug_company: value.slug_consignee_name,
                slug_country: value.slug_country_of_origin,
                slug_country_ori: value.slug_country_ori_consignee,
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

    emit(this._id.slug_shipper_name, values);
}

var reduceExporter = function (key, vals) {
    var r = {total_quantity: 0, total_container_count: 0, total_weight: 0, shipment: 0, company_join: [], customer: 0, dateGroup: {}};
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
        for (var j = 0; j < vals[i].company_join.length; j++) {
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

var resSup1 = db.import_document_exporter.mapReduce(mapExporter, reduceExporter, { out: "import_document_exporter_real"});

db.import_document_exporter_real_container_number.find().forEach(
    function (obj) {
        db.import_document_exporter_real.update({'_id': obj._id},
            {
                $set: {
                    'value.company_join.company_join_name': obj.value.company_join.company_join_name,
                    'value.company_join.company_join_address': obj.value.company_join.company_join_address,
                    'value.company_join.country': obj.value.company_join.country,
                    'value.company_join.notify_name': obj.value.company_join.notify_name,
                    'value.company_join.us_port': obj.value.company_join.us_port,
                    'value.company_join.foreign_port': obj.value.company_join.foreign_port,
                    'value.company_join.bill_of_lading': obj.value.company_join.bill_of_lading,
                    'value.company_join.carrier': obj.value.company_join.carrier,
                    'value.company_join.vessel': obj.value.company_join.vessel,
                    'value.company_join.slug_company': obj.value.company_join.slug_company
                }
            })
    }
)

db.import_document_exporter_real_container_number_2.find().forEach(
    function (obj) {
        db.import_document_exporter_real.update({'_id': obj._id},
            {
                $set: {
                    'value.company_join.container_number': obj.value.company_join.container_number,
                    'value.company_join.product_description': obj.value.company_join.product_description,
                    'value.company_join.arrival_date': obj.value.company_join.arrival_date,
                    'value.company_join.quantity': obj.value.company_join.quantity,
                    'value.company_join.container_count': obj.value.company_join.container_count,
                    'value.company_join.weight': obj.value.company_join.weight,
                    'value.company_join.cbm': obj.value.company_join.cbm,
                    'value.company_join.house_vs_master': obj.value.company_join.house_vs_master,
                    'value.company_join.other_info': obj.value.company_join.other_info,
                    'value.company_join.place_of_receipt': obj.value.company_join.place_of_receipt,
                    'value.company_join.slug_company': obj.value.company_join.slug_company,
                    'value.company_join.slug_country': obj.value.company_join.slug_country,
                    'value.company_join.slug_country_ori': obj.value.company_join.slug_country_ori,
                    'value.company_join.shipment': obj.value.company_join.shipment
                }
            })
    }
)

db.import_document_exporter_real_container_number_2.find().forEach(
    function (obj) {
        for (var i = 0; i < obj.value.company_join.length; i++) {

            db.import_document_exporter_real_container_number.update({'_id': obj._id, 'value.company_join.slug_company': obj.value.company_join[i].slug_company},
                {
                    $set: {
                        'value.company_join.$.container_number': obj.value.company_join[i].container_number,
                        'value.company_join.$.arrival_date': obj.value.company_join[i].arrival_date,
                        'value.company_join.$.quantity': obj.value.company_join[i].quantity,
                        'value.company_join.$.container_count': obj.value.company_join[i].container_count,
                        'value.company_join.$.weight': obj.value.company_join[i].weight,
                        'value.company_join.$.cbm': obj.value.company_join[i].cbm,
                        'value.company_join.$.house_vs_master': obj.value.company_join[i].house_vs_master,
                        'value.company_join.$.other_info': obj.value.company_join[i].other_info,
                        'value.company_join.$.place_of_receipt': obj.value.company_join[i].place_of_receipt,
                        'value.company_join.$.slug_country': obj.value.company_join[i].slug_country,
                        'value.company_join.$.slug_country_ori': obj.value.company_join[i].slug_country_ori,
                        'value.company_join.$.shipment': obj.value.company_join[i].shipment
                    }
                })
        }
    }
)

db.import_document_exporter_real_container_number.find().forEach(
    function (obj) {
        db.import_document_exporter_real.update({'_id': obj._id},
            {
                set: {
                    'value.company_join': obj.value.company_join
                }
            })
    }
)

var mapExporter = function () {
    var value = this.value;

    var values = {
        company_join: [
            {
                container_number: value.container_number,
                arrival_date: value.arrival_date,
                quantity: parseInt(value.quantity),
                container_count: parseInt(value.container_count),
                weight: parseInt(value.weight),
                cbm: value.cbm,
                house_vs_master: value.house_vs_master,
                other_info: value.other_info,
                place_of_receipt: value.place_of_receipt,
                slug_company: value.slug_consignee_name,
                slug_country: value.slug_country_of_origin,
                slug_country_ori: value.slug_country_ori_consignee,
                shipment: value.shipment
            }
        ]
    };

    emit(this._id.slug_shipper_name, values);
}

var reduceExporter = function (key, vals) {
    var r = {total_quantity: 0, total_container_count: 0, total_weight: 0, shipment: 0, company_join: [], customer: 0};
    for (var i = 0; i < vals.length; i++) {
        for (var j = 0; j < vals[i].company_join.length; j++) {
            r.company_join.push(
                {
                    container_number: vals[i].company_join[j].container_number,
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

var resSup1 = db.import_document_exporter.mapReduce(mapExporter, reduceExporter, { out: "import_document_exporter_real_part_1"});

db.import_document_exporter_real_part_1.find().forEach(
    function (obj) {
        db.all_data.update({'value.slug_company': obj._id, 'value.company_join.slug_company': obj.value.company_join[i].slug_company, 'value.company_as': 'exporter'},
            {
                $set: {
                    'value.company_join.$.container_number': obj.value.company_join[i].container_number,
                    'value.company_join.$.arrival_date': obj.value.company_join[i].arrival_date,
                    'value.company_join.$.quantity': obj.value.company_join[i].quantity,
                    'value.company_join.$.container_count': obj.value.company_join[i].container_count,
                    'value.company_join.$.weight': obj.value.company_join[i].weight,
                    'value.company_join.$.cbm': obj.value.company_join[i].cbm,
                    'value.company_join.$.house_vs_master': obj.value.company_join[i].house_vs_master,
                    'value.company_join.$.other_info': obj.value.company_join[i].other_info,
                    'value.company_join.$.place_of_receipt': obj.value.company_join[i].place_of_receipt,
                    'value.company_join.$.slug_country': obj.value.company_join[i].slug_country,
                    'value.company_join.$.slug_country_ori': obj.value.company_join[i].slug_country_ori,
                    'value.company_join.$.shipment': obj.value.company_join[i].shipment
                }
            })
    }
)

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
        slug_country_ori: value.slug_country_ori_shipper,
        company_join: [
            {
                company_join_name: value.consignee_name,
                company_join_address: value.consignee_address,
                country: value.country_of_origin,
                notify_name: value.notify_name,
                us_port: value.us_port,
                foreign_port: value.foreign_port,
                bill_of_lading: value.bill_of_lading,
                carrier: value.carrier,
                vessel: value.vessel,
                product_description: value.product_description,

                arrival_date: value.arrival_date,
                container_number: value.container_number,
                quantity: parseInt(value.quantity),
                container_count: parseInt(value.container_count),
                weight: parseInt(value.weight),
                cbm: value.cbm,
                house_vs_master: value.house_vs_master,
                other_info: value.other_info,
                place_of_receipt: value.place_of_receipt,
                slug_company: value.slug_consignee_name,
                slug_country: value.slug_country_of_origin,
                slug_country_ori: value.slug_country_ori_consignee,
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

    emit(this._id.slug_shipper_name, values);
}

var reduceExporter = function (key, vals) {
    var r = {total_quantity: 0, total_container_count: 0, total_weight: 0, shipment: 0, company_join: [], customer: 0, dateGroup: {}};
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
        for (var j = 0; j < 1; j++) {
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

var resSup1 = db.import_document_exporter.mapReduce(mapExporter, reduceExporter, { out: "import_document_exporter_real"});