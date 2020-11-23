db.import_document_exporter_real_part_2.find().forEach(
    function (obj) {
        for (var i = 0; i < obj.value.company_join.length; i++) {
            db.import_document_exporter_real_part_1.update({'_id': obj._id, 'value.company_join.slug_company': obj.value.company_join[i].slug_company},
                {
                    $set: {
                        'value.company_join.$.arrival_date': obj.value.company_join[i].arrival_date,
                        'value.company_join.$.container_number': obj.value.company_join[i].container_number,
                        'value.company_join.$.quantity': obj.value.company_join[i].quantity,
                        'value.company_join.$.container_count': obj.value.company_join[i].container_count,
                        'value.company_join.$.weight': obj.value.company_join[i].weight,
                        'value.company_join.$.cbm': obj.value.company_join[i].cbm
                    }
                })
        }
    }
)

db.import_document_exporter_real_part_3.find().forEach(
    function (obj) {
        for (var i = 0; i < obj.value.company_join.length; i++) {
            db.import_document_exporter_real_part_1.update({'_id': obj._id, 'value.company_join.slug_company': obj.value.company_join[i].slug_company},
                {
                    $set: {
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