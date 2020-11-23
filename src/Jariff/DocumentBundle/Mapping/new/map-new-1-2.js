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
    var vals = this.value;

    if (String(vals.shipper_name)) {
        for (var k = 1; k <= 3; k++) {
            if (getNthWord(vals.shipper_name, k)) {
                if (splitNameShipper == '') {
                    splitNameShipper = getNthWord(vals.shipper_name, k);
                } else {
                    splitNameShipper += ' ' + getNthWord(vals.shipper_name, k);
                }
            } else {
                break;
            }
        }
    }

    if (String(vals.consignee_name)) {
        for (var k = 1; k <= 3; k++) {
            if (getNthWord(vals.consignee_name, k)) {
                if (splitNameConsignee == '') {
                    splitNameConsignee = getNthWord(vals.consignee_name, k);
                } else {
                    splitNameConsignee += ' ' + getNthWord(vals.consignee_name, k);
                }
            } else {
                break;
            }
        }
    }

    var country = String(vals.country);

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
        system_identity_id: vals.system_identity_id,
        estimate_arrival_date: vals.estimate_arrival_date,
        actual_arrival_date: vals.actual_arrival_date,
        bill_of_lading: vals.bill_of_lading,
        master_bill_of_lading: vals.master_bill_of_lading,
        bill_type_code: vals.bill_type_code,
        carrier_sasc_code: vals.carrier_sasc_code,
        vessel_country_code: vals.vessel_country_code,
        vessel_code: vals.vessel_code,
        vessel_name: vals.vessel_name,
        voyage: vals.voyage,
        inbond_type: vals.inbond_type,
        manifest_no: vals.manifest_no,
        mode_of_transportation: vals.mode_of_transportation,
        loading_port: vals.loading_port,
        last_vist_foreign_port: vals.last_vist_foreign_port,
        us_clearing_district: vals.us_clearing_district,
        unloading_port: vals.unloading_port,
        place_of_receipt: vals.place_of_receipt,
        country: vals.country,
        country_sure_level: vals.country_sure_level,
        weight_in_kg: expo(vals.weight_in_kg),
        weight: expo(vals.Weight),
        weight_unit: vals.weight_unit,
        teu: expo(vals.teu),
        quantity: expo(vals.quantity),
        quantity_unit: vals.quantity_unit,
        measure_in_cm: expo(vals.measure_in_cm),
        measure: expo(vals.measure),
        measure_unit: vals.measure_unit,
        container_id: vals.container_id,
        container_size: vals.container_size,
        container_type: vals.container_type,
        container_desc_code: vals.container_desc_code,
        container_load_status: vals.container_load_status,
        container_type_of_service: vals.container_type_of_service,
        shipper_name: vals.shipper_name,
        shipper_address: vals.shipper_address,
        raw_shipper_name: vals.raw_shipper_name,
        raw_shipper_address_1: vals.raw_shipper_address_1,
        raw_shipper_address_2: vals.raw_shipper_address_2,
        raw_shipper_address_3: vals.raw_shipper_address_3,
        raw_shipper_address_4: vals.raw_shipper_address_4,
        raw_shipper_address_others: vals.raw_shipper_address_others,
        consignee_name: splitNameConsignee,
        consignee_address: vals.consignee_address,
        raw_consignee_name: vals.raw_consignee_name,
        raw_consignee_address: vals.raw_consignee_address,
        raw_consignee_address_1: vals.raw_consignee_address_1,
        raw_consignee_address_2: vals.raw_consignee_address_2,
        raw_consignee_address_3: vals.raw_consignee_address_3,
        raw_consignee_address_4: vals.raw_consignee_address_4,
        raw_consignee_address_others: vals.raw_consignee_address_others,
        notify_party_name: vals.notify_party_name,
        raw_notify_party_name: vals.raw_notify_party_name,
        raw_notify_party_address_1: vals.raw_notify_party_address_1,
        raw_notify_party_address_2: vals.raw_notify_party_address_2,
        raw_notify_party_address_3: vals.raw_notify_party_address_3,
        raw_notify_party_address_4: vals.raw_notify_party_address_4,
        raw_notify_party_address_others: vals.raw_notify_party_address_others,
        product_description: vals.product_description,
        marks_numbers: vals.marks_numbers,
        hs_code: vals.hs_code,
        hs_code_sure_level: vals.hs_code_sure_level,
        cif: expo(vals.cif),
        indicator_of_true_supplier: vals.indicator_of_true_supplier,
        end: vals.end,
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

var resSup1 = db.mr.mapReduce(map1, reduceFase1,
    {out: {reduce:'test_collection_mrs',sharded:true}, scope: {getNthWord: getNthWord, getSlugConvert: getSlugConvert, countries: countries, expo: expo}});