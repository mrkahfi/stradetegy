var mapExporter = function () {
    var value = this.value;

    var values = {
        company_join: [
            {
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
    { out: "import_document_importer_real_part_2"});