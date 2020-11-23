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
                slug_company: value.slug_consignee_name,
                product_description: value.product_description,
                arrival_date: value.arrival_date,
                container_count: parseInt(value.container_count),
                weight: parseInt(value.weight),
                slug_country: value.slug_country_of_origin,
                slug_country_ori: value.slug_country_ori_consignee

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
                    slug_company: vals[i].company_join[j].slug_company,
                    product_description: vals[i].company_join[j].product_description,
                    arrival_date: vals[i].company_join[j].arrival_date,
                    container_count: vals[i].company_join[j].container_count,
                    weight: vals[i].company_join[j].weight,
                    slug_country: vals[i].company_join[j].slug_country,
                    slug_country_ori: vals[i].company_join[j].slug_country_ori
                }
            );
        }
    }
    return r;
}

var resSup1 = db.import_document_exporter.mapReduce(mapExporter, reduceExporter, { out: "import_document_exporter_real_part_1"});
