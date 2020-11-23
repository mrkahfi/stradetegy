var getNthWord = function (string, n) {
    var words = string.split(" ");
    return words[n - 1];
}

var getSlugConvert = function (string, to) {
    if (string !== '') {
        if (to == 'company') {
            return string.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
        } else {
            return string.toLowerCase().replace(/ /g, '').replace(/[^\w-]+/g, '');
        }
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

    for (var k = 1; k <= 3; k++) {
        if (getNthWord(this.shipper_name, k)) {
            if (splitNameShipper == '') {
                splitNameShipper = getNthWord(this.shipper_name, k);
            } else {
                splitNameShipper += ' ' + getNthWord(this.shipper_name, k);
            }
        } else {
            break;
        }
    }

    for (var k = 1; k <= 3; k++) {
        if (getNthWord(this.consignee_name, k)) {
            if (splitNameConsignee == '') {
                splitNameConsignee = getNthWord(this.consignee_name, k);
            } else {
                splitNameConsignee += ' ' + getNthWord(this.consignee_name, k);
            }
        } else {
            break;
        }
    }

    var shipp_address = this.shipper_address.split("Show Map")[0];
    var consig_address = this.consignee_address.split("Show Map")[0];
    var countryShipperOri = getSlugConvert(this.country_of_origin, 'country');
    for (var k = 0; k < 225; k++) {
        var str = countries(k).toLowerCase();
        var check = shipp_address.toLowerCase().match(str);
        if (check !== null) {
            countryShipperOri = str;
            break;
        }
    }

    var countryConsigneeOri = 'unknown';
    for (var k = 0; k < 225; k++) {
        var str = countries(k).toLowerCase();
        var check = consig_address.toLowerCase().match(str);
        if (check !== null) {
            countryConsigneeOri = str;
            break;
        }
    }

    var slug_consignee = getSlugConvert(splitNameConsignee, 'company');
    var slug_shipper = getSlugConvert(splitNameShipper, 'company');
    var slug_country = getSlugConvert(this.country_of_origin, 'country');
    var slug_ship_registered = getSlugConvert(this.ship_registered_in, 'country');
    var value = {
        product_description: this.product_description,
        consignee_name: splitNameConsignee,
        consignee_address: consig_address,
        shipper_name: splitNameShipper,
        shipper_address: shipp_address,
        notify_name: this.notify_name,
        us_port: this.us_port,
        foreign_port: this.foreign_port,
        bill_of_lading: this.bill_of_lading,
        carrier: this.carrier,
        vessel: this.vessel,
        container_number: this.container_number,
        country_of_origin: this.country_of_origin,
        ship_registered_in: this.ship_registered_in,
        arrival_date: this.arrival_date,
        quantity: this.quantity,
        container_count: this.container_count,
        weight: this.weight,
        cbm: this.cbm,
        house_vs_master: this.house_vs_master,
        other_info: this.other_info,
        slug_country_ori_shipper: getSlugConvert(countryShipperOri),
        slug_country_ori_consignee: getSlugConvert(countryConsigneeOri),
        place_of_receipt: this.place_of_receipt,
        slug_consignee_name: slug_consignee,
        slug_country_of_origin: slug_country,
        slug_shipper_name: slug_shipper,
        slug_ship_register_in: slug_ship_registered
    };

    emit(this._id, value);
}

var reduceFase1 = function (key, vals) {
    var r = {};
    for (var i = 0; i < vals.length; i++) {
        r.product_description = vals[i].product_description;
        r.consignee_name = vals[i].consignee_name;
        r.consignee_address = vals[i].consignee_address;
        r.shipper_name = vals[i].shipper_name;
        r.shipper_address = vals[i].shipper_address;
        r.notify_name = vals[i].notify_name;
        r.us_port = vals[i].us_port;
        r.foreign_port = vals[i].foreign_port;
        r.bill_of_lading = vals[i].bill_of_lading;
        r.carrier = vals[i].carrier;
        r.vessel = vals[i].vessel;
        r.container_number = vals[i].container_number;
        r.country_of_origin = vals[i].country_of_origin;
        r.ship_registered_in = vals[i].ship_registered_in;
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
        r.slug_shipper_name = vals[i].slug_shipper_name;
        r.slug_ship_register_in = vals[i].slug_ship_register_in;
        r.slug_country_ori_shipper = vals[i].slug_country_ori_shipper;
        r.slug_country_ori_consignee = vals[i].slug_country_ori_consignee;
    }
    return r;
}

var resSup1 = db.import_document.mapReduce(map1, reduceFase1,
    {query: { "consignee_name": {$ne: "-NOT AVAILABLE-"},
        $or: [
            { "consignee_name": {$ne: ""} },
            { "shipper_name": {$ne: ""} },
            { "shipper_name": {$ne: "-NOT AVAILABLE-"}}
        ]},
        out: "import_document_rewrite", scope: {getNthWord: getNthWord, getSlugConvert: getSlugConvert, countries: countries}});