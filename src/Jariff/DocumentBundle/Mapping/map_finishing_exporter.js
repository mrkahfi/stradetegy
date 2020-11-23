var mapExporter = function () {
    var value = this.value;

    var values = {
        company_name: value.company_name,
        company_address: value.company_address,
        country: value.ship_registered_in,
        company_as: value.company_as,
        total_quantity: parseInt( value.total_quantity),
        total_container_count: parseInt(value.total_container_count),
        total_weight: parseInt(value.total_weight),
        slug_company: value.slug_shipper_name,
        slug_country: value.slug_ship_register_in,
        shipment: value.shipment,
        customer: value.customer,
        dateGroup: value.dateGroup,
        company_join: value.company_join,
        slug_country_ori: value.slug_country_ori
    };

    emit(ObjectId(), values);
}

var reduceExporter = function (key, vals) {
    var r = {};
    for (var i = 0; i < vals.length; i++) {
        r.company_name = vals[i].company_name;
        r.company_address = vals[i].company_address;
        r.country = vals[i].country;
        r.company_as = vals[i].company_as;
        r.total_quantity = vals[i].total_quantity;
        r.total_container_count = vals[i].total_container_count;
        r.total_weight = vals[i].total_weight;
        r.slug_company = vals[i].slug_company;
        r.slug_country = vals[i].slug_country;
        r.shipment = vals[i].shipment;
        r.customer = vals[i].customer;
        r.dateGroup = vals[i].dateGroup;
        r.slug_country_ori= vals[i].slug_country_ori;
        r.company_join = vals[i].company_join;
    }
    return r;
}

var resSup1 = db.import_document_exporter_real.mapReduce(mapExporter, reduceExporter,{ out: "import_document_exporter_finishing"});

db.import_document_exporter_real_part_1.find().addOption(DBQuery.Option.noTimeout).forEach(
    function (obj) {
        db.import_document_exporter_finishing.update({'value.slug_company': obj._id},
            {
                $set: {
                    'value.company_join': obj.value.company_join
                }
            },false,true)
    }
)