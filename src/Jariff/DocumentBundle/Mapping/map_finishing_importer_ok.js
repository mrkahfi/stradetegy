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
        slug_country_ori: value.slug_country_ori_consignee,
        company_join: value.company_join,
        dateGroup: {}
    };

    for (thisDate in checkDateGroup) {
        if (values.dateGroup.hasOwnProperty(thisDate))
            values.dateGroup[thisDate] += value.dateGroup[thisDate];
        else
            values.dateGroup[thisDate] = value.dateGroup[thisDate];
    }

    emit(ObjectId(), values);
}

var reduceExporter = function (key, vals) {
    var r = {total_quantity: 0, total_container_count: 0, total_weight: 0, shipment: 0, company_join: [], customer: 1, dateGroup: {}};
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
        r.customer += vals[i].customer;
        r.slug_country_ori = vals[i].slug_country_ori;
        r.company_join = vals[i].company_join;

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

var resSup1 = db.import_document_importer_real.mapReduce(mapExporter, reduceExporter,
    { out: "import_document_importer_finishing"});