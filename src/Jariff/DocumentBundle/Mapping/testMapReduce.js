var mapExporter = function () {
    var value = this.value;

    var myDate = value.arrival_date;
    var myDateSplit = myDate.split(" ");

    var values = {
        dateGroup: {}
    };

    if (values.dateGroup.hasOwnProperty(myDateSplit[0]))
        values.dateGroup[myDateSplit[0]] += 1;
    else
        values.dateGroup[myDateSplit[0]] = 1;

    emit(value.slug_shipper_name, values);
}

var reduceExporter = function (key, vals) {
    var r = {dateGroup: {}};
    for (var i = 0; i < vals.length; i++) {

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
    { query: { "value.slug_shipper_name": "volkswagen-ag"}, out: "import_document_show"});

db.import_document_show.aggregate(
    [
        { $unwind : "$value" },
        { $group : { _id : "$value" , number : { $sum : 1 } } },
        { $sort : { number : -1 } },
        { $limit : 5 }
    ]
)