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
                slug_company: value.slug_shipper_name
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
                    slug_company: vals[i].company_join[j].slug_company
                }
            );
        }
    }
    return r;
}

var resSup1 = db.import_document_importer.mapReduce(mapExporter, reduceExporter,
    { out: "import_document_importer_real_part_1"});

db.import_document_importer_real_part_2.find().addOption(DBQuery.Option.noTimeout).forEach(
    function (obj) {
        for (var i = 0; i < obj.value.company_join.length; i++) {
            db.import_document_importer_real_part_1.update({'_id': obj._id, 'value.company_join.slug_company': obj.value.company_join[i].slug_company},
                {
                    $set: {
                        'value.company_join.$.arrival_date': obj.value.company_join[i].arrival_date,
                        'value.company_join.$.quantity': obj.value.company_join[i].quantity,
                        'value.company_join.$.container_count': obj.value.company_join[i].container_count,
                        'value.company_join.$.weight': obj.value.company_join[i].weight,
                        'value.company_join.$.cbm': obj.value.company_join[i].cbm,
                        'value.company_join.$.house_vs_master': obj.value.company_join[i].house_vs_master,
                        'value.company_join.$.other_info': obj.value.company_join[i].other_info,
                        'value.company_join.$.place_of_receipt': obj.value.company_join[i].place_of_receipt,
                        'value.company_join.$.slug_company': obj.value.company_join[i].slug_company,
                        'value.company_join.$.slug_country': obj.value.company_join[i].slug_country,
                        'value.company_join.$.slug_country_ori': obj.value.company_join[i].slug_country_ori,
                        'value.company_join.$.shipment': obj.value.company_join[i].shipment
                    }
                })
        }
    }
)