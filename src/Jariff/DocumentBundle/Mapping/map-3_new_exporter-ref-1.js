var mapExporter = function () {
    var value = this.value;

    var values = {
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
                product_description: value.product_description
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
                    company_join_name: vals[i].company_join[j].company_join_name,
                    company_join_address: vals[i].company_join[j].company_join_address,
                    country: vals[i].company_join[j].country,
                    notify_name: vals[i].company_join[j].notify_name,
                    us_port: vals[i].company_join[j].us_port,
                    foreign_port: vals[i].company_join[j].foreign_port,
                    bill_of_lading: vals[i].company_join[j].bill_of_lading,
                    carrier: vals[i].company_join[j].carrier,
                    vessel: vals[i].company_join[j].vessel,
                    product_description: vals[i].company_join[j].product_description
                }
            );
        }
    }
    return r;
}

var resSup1 = db.import_document_exporter.mapReduce(mapExporter, reduceExporter, { out: "import_document_exporter_real_part_1"});

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

db.import_document_exporter.find().forEach(
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

            db.import_document_exporter_finishing.update({'value.slug_company': obj._id, 'value.comp_.$.company_join_name': obj.value.company_join[i].company_join_name},
                {
                    $set: {
                        'value.comp_.$.company_join_name.container_count': obj.value.company_join[i].company_join_name.container_count
                    }
                })
        }
    }
)

db.import_document_exporter_real_container_number_2.find().forEach(
    function (obj) {
        db.import_document_exporter_real.update({'_id': obj._id},
            {
                $set: {
                    'value.comp_': obj.value.company_join
                }
            })
    }
)