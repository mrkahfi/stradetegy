var mapExporter = function () {
    var value = this.value;

    var values = {
        company_join: [
            {
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

var resSup1 = db.import_document_exporter.mapReduce(mapExporter, reduceExporter, { out: "import_document_exporter_real_part_3"});
