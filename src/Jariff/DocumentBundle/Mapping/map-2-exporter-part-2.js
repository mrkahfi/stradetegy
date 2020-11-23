var mapExporter = function () {
    var value = this.value;
    var quantities = value.quantity;
    var container = value.container_count;
    var weight = value.weight;

    var myDate = value.arrival_date;
    var myDateSplit = myDate.split(" ");

    var values = {
        arrival_date: value.arrival_date,
        quantity: parseInt(quantities),
        container_count: parseInt(container),
        weight: parseInt(weight),
        cbm: value.cbm,
        house_vs_master: value.house_vs_master,
        other_info: value.other_info,
        place_of_receipt: value.place_of_receipt,
        slug_consignee_name: value.slug_consignee_name,
        slug_country_of_origin: value.slug_country_of_origin,
        slug_country_ori_shipper: value.slug_country_ori_shipper,
        slug_country_ori_consignee: value.slug_country_ori_consignee,
        dateGroup: {}
    };

    if (values.dateGroup.hasOwnProperty(myDateSplit[0]))
        values.dateGroup[myDateSplit[0]] += 1;
    else
        values.dateGroup[myDateSplit[0]] = 1;

    emit({slug_consignee_name: value.slug_consignee_name, slug_shipper_name: value.slug_shipper_name}, values);
}

var reduceExporter = function (key, vals) {
    var r = {dateGroup: {}};
    for (var i = 0; i < vals.length; i++) {
        r.arrival_date = vals[i].arrival_date;
        r.quantity = vals[i].quantity;
        r.container_count = vals[i].container_count;
        r.weight = vals[i].weight;
        r.cbm = vals[i].cbm;
        r.house_vs_master = vals[i].house_vs_master;
        r.other_info = vals[i].other_info;
        r.place_of_receipt = vals[i].place_of_receipt;
        r.slug_consignee_name = vals[i].slug_consignee_name;
        r.slug_country_of_origin = vals[i].slug_country_of_origin;
        r.slug_country_ori_shipper = vals[i].slug_country_ori_shipper;
        r.slug_country_ori_consignee = vals[i].slug_country_ori_consignee;

        for (thisDate in vals[i].dateGroup) {
            if (r.dateGroup[thisDate] == null) {
                r.dateGroup[thisDate] = 1;
            } else {
                r.dateGroup[thisDate] += vals[i].dateGroup[thisDate];
            }
        }
    }
    return r;
}

var resSup1 = db.import_document_rewrite.mapReduce(mapExporter, reduceExporter,
    { out: "import_document_exporter_part_2"});