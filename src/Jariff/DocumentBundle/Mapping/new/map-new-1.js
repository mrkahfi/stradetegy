var getNthWord = function (string, n) {
    string = String(string);
    var words = string.split(" ");
    if (words.length >= 3) {
        return words[n - 1];
    }
    return false;
}

var getSlugConvert = function (string, to) {
    string = String(string);
    if (string !== '') {
        if (to == 'company') {
            return string.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
        } else {
            return string.toLowerCase().replace(/ /g, '').replace(/[^\w-]+/g, '');
        }
    }

    return string;
}

var expo = function (string) {
    string = String(string);
    var splits = string.split("e+");
    if (splits.length > 0) {
        var r = Math.pow(10, splits[1]);
        return splits[0] * r;
    }
    return string;
}

var countries = function (n) {
    return new Array(
        "Afghanistan",
        "Aland Islands",
        "Albania",
        "Algeria",
        "American Samoa",
        "Andorra",
        "Angola",
        "Anguilla",
        "Antarctica",
        "Antigua And Barbuda",
        "Argentina",
        "Armenia",
        "Aruba",
        "Australia",
        "Austria",
        "Azerbaijan",
        "Bahamas",
        "Bahrain",
        "Bangladesh",
        "Barbados",
        "Belarus",
        "Belgium",
        "Belize",
        "Benin",
        "Bermuda",
        "Bhutan",
        "Bolivia",
        "Bosnia And Herzegovina",
        "Botswana",
        "Bouvet Island",
        "Brazil",
        "Brunei Darussalam",
        "Bulgaria",
        "Burkina Faso",
        "Burundi",
        "Cambodia",
        "Cameroon",
        "Canada",
        "Cape Verde",
        "Central African Republic",
        "Chad",
        "Chile",
        "China",
        "Christmas Island",
        "Colombia",
        "Comoros",
        "Congo",
        "Cook Islands",
        "Costa Rica",
        "Croatia",
        "Cuba",
        "Cyprus",
        "Czech Republic",
        "Denmark",
        "Djibouti",
        "Dominica",
        "Dominican Republic",
        "Ecuador",
        "Egypt",
        "El Salvador",
        "Equatorial Guinea",
        "Eritrea",
        "Estonia",
        "Ethiopia",
        "Faroe Islands",
        "Fiji",
        "Finland",
        "France",
        "Gabon",
        "Gambia",
        "Georgia",
        "Germany",
        "Ghana",
        "Gibraltar",
        "Greece",
        "Greenland",
        "Grenada",
        "Guadeloupe",
        "Guam",
        "Guatemala",
        "Guernsey",
        "Guinea",
        "Guyana",
        "Haiti",
        "Honduras",
        "Hong Kong",
        "Hungary",
        "Iceland",
        "India",
        "Indonesia",
        "Iran",
        "Iraq",
        "Ireland",
        "Israel",
        "Italy",
        "Jamaica",
        "Japan",
        "Jersey",
        "Jordan",
        "Kazakhstan",
        "Kenya",
        "Kiribati",
        "South Korea",
        "Kuwait",
        "Kyrgyzstan",
        "Latvia",
        "Lebanon",
        "Lesotho",
        "Liberia",
        "Libyan Arab Jamahiriya",
        "Liechtenstein",
        "Lithuania",
        "Luxembourg",
        "Macao",
        "Macedonia",
        "Madagascar",
        "Malawi",
        "Malaysia",
        "Maldives",
        "Mali",
        "Malta",
        "Marshall Islands",
        "Martinique",
        "Mauritania",
        "Mauritius",
        "Mayotte",
        "Mexico",
        "Micronesia",
        "Moldova",
        "Monaco",
        "Mongolia",
        "Montserrat",
        "Morocco",
        "Mozambique",
        "Myanmar",
        "Namibia",
        "Nauru",
        "Nepal",
        "Netherlands",
        "Netherlands Antilles",
        "New Caledonia",
        "New Zealand",
        "Nicaragua",
        "Niger",
        "Nigeria",
        "Niue",
        "Norfolk Island",
        "Northern Mariana Islands",
        "Norway",
        "Oman",
        "Pakistan",
        "Palau",
        "Palestinian",
        "Panama",
        "Papua New Guinea",
        "Paraguay",
        "Peru",
        "Philippines",
        "Pitcairn",
        "Poland",
        "Portugal",
        "Puerto Rico",
        "Qatar",
        "Reunion",
        "Romania",
        "Russian Federation",
        "Rwanda",
        "Saint Helena",
        "Saint Kitts And Nevis",
        "Saint Lucia",
        "Saint Pierre And Miquelon",
        "Saint Vincent And The Grenadines",
        "Samoa",
        "San Marino",
        "Sao Tome And Principe",
        "Saudi Arabia",
        "Senegal",
        "Serbia And Montenegro",
        "Seychelles",
        "Sierra Leone",
        "Singapore",
        "Slovakia",
        "Slovenia",
        "Solomon Islands",
        "Somalia",
        "South Africa",
        "South Georgia And The South Sandwich Islands",
        "Spain",
        "Sri Lanka",
        "Sudan",
        "Suriname",
        "Svalbard And Jan Mayen",
        "Swaziland",
        "Sweden",
        "Switzerland",
        "Syrian Arab Republic",
        "Taiwan",
        "Tajikistan",
        "Tanzania",
        "Thailand",
        "Timor-Leste",
        "Togo",
        "Tokelau",
        "Tonga",
        "Trinidad And Tobago",
        "Tunisia",
        "Turkey",
        "Turkmenistan",
        "Turks And Caicos Islands",
        "Tuvalu",
        "Uganda",
        "Ukraine",
        "United Arab Emirates",
        "United Kingdom",
        "United States",
        "Uruguay",
        "Uzbekistan",
        "Vanuatu",
        "Venezuela",
        "Viet Nam",
        "Wallis And Futuna",
        "Western Sahara",
        "Yemen",
        "Zambia",
        "Zimbabwe"
    )[n];
}

var map1 = function () {

    var splitNameShipper = '';
    var splitNameConsignee = '';

    if (String(this.ShipperName)) {
        for (var k = 1; k <= 3; k++) {
            if (getNthWord(this.ShipperName, k)) {
                if (splitNameShipper == '') {
                    splitNameShipper = getNthWord(this.ShipperName, k);
                } else {
                    splitNameShipper += ' ' + getNthWord(this.ShipperName, k);
                }
            } else {
                break;
            }
        }
    }

    if (String(this.ConsigneeName)) {
        for (var k = 1; k <= 3; k++) {
            if (getNthWord(this.ConsigneeName, k)) {
                if (splitNameConsignee == '') {
                    splitNameConsignee = getNthWord(this.ConsigneeName, k);
                } else {
                    splitNameConsignee += ' ' + getNthWord(this.ConsigneeName, k);
                }
            } else {
                break;
            }
        }
    }

    var country = String(this.Country);

    if (country !== '') {
        for (var k = 0; k < 225; k++) {
            var str = countries(k).toLowerCase();
            var check = country.toLowerCase().match(str);
            if (check !== null) {
                country = str;
                break;
            }
        }
    }

    var countryShipperOri = getSlugConvert(country, 'country');
    var countryConsigneeOri = getSlugConvert('UNITED STATES', 'country');
    var slug_consignee = getSlugConvert(splitNameConsignee, 'company');
    var slug_shipper = getSlugConvert(splitNameShipper, 'company');
    var value = {
        system_identity_id: this.SystemIdentityId,
        estimate_arrival_date: new Date(this.EstimateArrivalDate.toString().substring(11, 15) + "-" + this.EstimateArrivalDate.toString().substring(15, 17) + "-" + this.EstimateArrivalDate.toString().substring(17, 19)),
        actual_arrival_date: new Date(this.ActualArrivalDate.toString().substring(11, 15) + "-" + this.ActualArrivalDate.toString().substring(15, 17) + "-" + this.ActualArrivalDate.toString().substring(17, 19)),
        bill_of_lading: this.BillofLading,
        master_bill_of_lading: this.MasterBillofLading,
        bill_type_code: this.BillTypeCode,
        carrier_sasc_code: this.CarrierSASCCode,
        vessel_country_code: this.VesselCountryCode,
        vessel_code: this.VesselCode,
        vessel_name: this.VesselName,
        voyage: this.Voyage,
        inbond_type: this.InbondType,
        manifest_no: this.ManifestNo,
        mode_of_transportation: this.ModeofTransportation,
        loading_port: this.LoadingPort,
        last_vist_foreign_port: this.LastVistForeignPort,
        us_clearing_district: this.USClearingDistrict,
        unloading_port: this.UnloadingPort,
        place_of_receipt: this.PlaceofReceipt,
        country: this.Country,
        country_sure_level: this.CountrySureLevel,
        weight_in_kg: expo(this.WeightinKG),
        weight: expo(this.Weight),
        weight_unit: this.WeightUnit,
        teu: expo(this.TEU),
        quantity: expo(this.Quantity),
        quantity_unit: this.QuantityUnit,
        measure_in_cm: expo(this.MeasureinCM),
        measure: expo(this.Measure),
        measure_unit: this.MeasureUnit,
        container_id: this.ContainerId,
        container_size: this.ContainerSize,
        container_type: this.ContainerType,
        container_desc_code: this.ContainerDescCode,
        container_load_status: this.ContainerLoadStatus,
        container_type_of_service: this.ContainerTypeofService,
        shipper_name: splitNameShipper,
        shipper_address: this.ShipperAddress,
        raw_shipper_name: this.RawShipperName,
        raw_shipper_address_1: this.RawShipperAddr1,
        raw_shipper_address_2: this.RawShipperAddr2,
        raw_shipper_address_3: this.RawShipperAddr3,
        raw_shipper_address_4: this.RawShipperAddr4,
        raw_shipper_address_others: this.RawShipperAddrOthers,
        consignee_name: splitNameConsignee,
        consignee_address: this.ConsigneeAddress,
        raw_consignee_name: this.RawConsigneeName,
        raw_consignee_address: this.ConsigneeAddress,
        raw_consignee_address_1: this.RawConsigneeAddr1,
        raw_consignee_address_2: this.RawConsigneeAddr2,
        raw_consignee_address_3: this.RawConsigneeAddr3,
        raw_consignee_address_4: this.RawConsigneeAddr4,
        raw_consignee_address_others: this.NotifyPartyName,
        notify_party_name: this.NotifyPartyAddress,
        raw_notify_party_name: this.RawNotifyPartyName,
        raw_notify_party_address_1: this.RawNotifyPartyAddr1,
        raw_notify_party_address_2: this.RawNotifyPartyAddr2,
        raw_notify_party_address_3: this.RawNotifyPartyAddr3,
        raw_notify_party_address_4: this.RawNotifyPartyAddr4,
        raw_notify_party_address_others: this.RawNotifyPartyAddrOthers,
        product_description: this.ProductDesc,
        marks_numbers: this.MarksNumbers,
        hs_code: this.HSCode,
        hs_code_sure_level: this.HSCodeSureLevel,
        cif: expo(this.CIF),
        indicator_of_true_supplier: this.Indicatoroftruesupplier,
        end: this.END,
        slug_country_ori_shipper: countryShipperOri,
        slug_country_ori_consignee: countryConsigneeOri,
        slug_consignee_name: slug_consignee,
        slug_shipper_name: slug_shipper
    };
    emit(this._id.valueOf(), value);
}

var reduceFase1 = function (key, vals) {
    var r = {};
    for (var i = 0; i < vals.length; i++) {
        r.system_identity_id = vals[i].system_identity_id;
        r.estimate_arrival_date = vals[i].estimate_arrival_date;
        r.actual_arrival_date = vals[i].actual_arrival_date;
        r.bill_of_lading = vals[i].bill_of_lading;
        r.bill_type_code = vals[i].bill_type_code;
        r.master_bill_of_lading = vals[i].master_bill_of_lading;
        r.carrier_sasc_code = vals[i].carrier_sasc_code;
        r.vessel_country_code = vals[i].vessel_country_code;
        r.vessel_code = vals[i].vessel_code;
        r.vessel_name = vals[i].vessel_name;
        r.voyage = vals[i].voyage;
        r.inbond_type = vals[i].inbond_type;
        r.manifest_no = vals[i].manifest_no;
        r.mode_of_transportation = vals[i].mode_of_transportation;
        r.loading_port = vals[i].loading_port;
        r.last_vist_foreign_port = vals[i].last_vist_foreign_port;
        r.us_clearing_district = vals[i].us_clearing_district;
        r.unloading_port = vals[i].unloading_port;
        r.place_of_receipt = vals[i].place_of_receipt;
        r.country = vals[i].country;
        r.country_sure_level = vals[i].country_sure_level;
        r.weight_in_kg = vals[i].weight_in_kg;
        r.weight = vals[i].weight;
        r.weight_unit = vals[i].weight_unit;
        r.teu = vals[i].teu;
        r.quantity = vals[i].quantity;
        r.quantity_unit = vals[i].quantity_unit;
        r.measure_in_cm = vals[i].measure_in_cm;
        r.measure = vals[i].measure;
        r.measure_unit = vals[i].measure_unit;
        r.container_id = vals[i].container_id;
        r.container_size = vals[i].container_size;
        r.container_type = vals[i].container_type;
        r.container_desc_code = vals[i].container_desc_code;
        r.container_load_status = vals[i].container_load_status;
        r.container_type_of_service = vals[i].container_type_of_service;
        r.shipper_name = vals[i].shipper_name;
        r.shipper_address = vals[i].shipper_address;
        r.raw_shipper_name = vals[i].raw_shipper_name;
        r.raw_shipper_address_1 = vals[i].raw_shipper_address_1;
        r.raw_shipper_address_2 = vals[i].raw_shipper_address_2;
        r.raw_shipper_address_3 = vals[i].raw_shipper_address_3;
        r.raw_shipper_address_4 = vals[i].raw_shipper_address_4;
        r.raw_shipper_address_others = vals[i].raw_shipper_address_others;
        r.consignee_name = vals[i].consignee_name;
        r.consignee_address = vals[i].consignee_address;
        r.raw_consignee_name = vals[i].raw_consignee_name;
        r.raw_consignee_address = vals[i].raw_consignee_address;
        r.raw_consignee_address_1 = vals[i].raw_consignee_address_1;
        r.raw_consignee_address_2 = vals[i].raw_consignee_address_2;
        r.raw_consignee_address_3 = vals[i].raw_consignee_address_3;
        r.raw_consignee_address_4 = vals[i].raw_consignee_address_4;
        r.raw_consignee_address_others = vals[i].raw_consignee_address_others;
        r.notify_party_name = vals[i].notify_party_name;
        r.raw_notify_party_name = vals[i].raw_notify_party_name;
        r.raw_notify_party_address_1 = vals[i].raw_notify_party_address_1;
        r.raw_notify_party_address_2 = vals[i].raw_notify_party_address_2;
        r.raw_notify_party_address_3 = vals[i].raw_notify_party_address_3;
        r.raw_notify_party_address_4 = vals[i].raw_notify_party_address_4;
        r.raw_notify_party_address_others = vals[i].raw_notify_party_address_others;
        r.product_description = vals[i].product_description;
        r.marks_numbers = vals[i].marks_numbers;
        r.hs_code = vals[i].hs_code;
        r.hs_code_sure_level = vals[i].hs_code_sure_level;
        r.cif = vals[i].cif;
        r.indicator_of_true_supplier = vals[i].indicator_of_true_supplier;
        r.end = vals[i].end;
        r.slug_country_ori_shipper = vals[i].slug_country_ori_shipper;
        r.slug_country_ori_consignee = vals[i].slug_country_ori_consignee;
        r.slug_consignee_name = vals[i].slug_consignee_name;
        r.slug_shipper_name = vals[i].slug_shipper_name;
    }
    return r;
}

var resSup1 = db.usa_2006.mapReduce(map1, reduceFase1,
    {out: 'usa_import', scope: {getNthWord: getNthWord, getSlugConvert: getSlugConvert, countries: countries, expo: expo}});


var resSup1 = db.usa_2007.mapReduce(map1, reduceFase1,
    {out: {merge: 'usa_rewrite'}, scope: {getNthWord: getNthWord, getSlugConvert: getSlugConvert, countries: countries, expo: expo}});

var resSup1 = db.usa_2008.mapReduce(map1, reduceFase1,
    {out: {merge: 'usa_rewrite'}, scope: {getNthWord: getNthWord, getSlugConvert: getSlugConvert, countries: countries, expo: expo}});


var resSup1 = db.usa_2014.mapReduce(map1, reduceFase1,
    {out: {merge: 'usa_rewrite'}, scope: {getNthWord: getNthWord, getSlugConvert: getSlugConvert, countries: countries, expo: expo}});
