db.import_document_exporter_part_2.find().forEach(
    function (obj) {
        db.import_document_exporter.update({'_id': obj._id}, {$set: {
            'value.arrival_date': obj.value.arrival_date,
            'value.quantity': obj.value.quantity,
            'value.container_count': obj.value.container_count,
            'value.weight': obj.value.weight,
            'value.cbm': obj.value.cbm,
            'value.house_vs_master': obj.value.house_vs_master,
            'value.other_info': obj.value.other_info,
            'value.place_of_receipt': obj.value.place_of_receipt,
            'value.slug_consignee_name': obj.value.slug_consignee_name,
            'value.slug_country_of_origin': obj.value.slug_country_of_origin,
            'value.slug_country_ori_shipper': obj.value.slug_country_ori_shipper,
            'value.slug_country_ori_consignee': obj.value.slug_country_ori_consignee,
            'value.dateGroup': obj.value.dateGroup
        }
        })
    })