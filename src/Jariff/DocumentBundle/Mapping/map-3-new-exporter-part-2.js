var mapExporter = function () {
    var value = this.value;

    var values = {
        company_join: [
            {
                arrival_date: value.arrival_date,
                container_number: value.container_number,
                quantity: parseInt(value.quantity),
                container_count: parseInt(value.container_count),
                weight: parseInt(value.weight),
                cbm: value.cbm,
                slug_company: value.slug_consignee_name
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
                    arrival_date: vals[i].company_join[j].arrival_date,
                    container_number: vals[i].company_join[j].container_number,
                    quantity: vals[i].company_join[j].quantity,
                    container_count: vals[i].company_join[j].container_count,
                    weight: vals[i].company_join[j].weight,
                    cbm: vals[i].company_join[j].cbm ,
                    slug_company: vals[i].company_join[j].slug_company
                }
            );
        }
    }
    return r;
}

var resSup1 = db.import_document_exporter.mapReduce(mapExporter, reduceExporter, { out: "import_document_exporter_real_part_2"});
