var mapImporter = function () {
    var value = this.value;
    var quantities = value.quantity;
    var weight = value.weight;
    var measure = value.measure;

    var values = {
        system_identity_id: value.system_identity_id,
        estimate_arrival_date: value.estimate_arrival_date,
        actual_arrival_date: value.actual_arrival_date,
        bill_of_lading: value.bill_of_lading,
        master_bill_of_lading: value.master_bill_of_lading,
        carrier_sasc_code: value.carrier_sasc_code,
        vessel_country_code: value.vessel_country_code,
        vessel_code: value.vessel_code,
        vessel_name: value.vessel_name,
        voyage: value.voyage,
        inbond_type: value.inbond_type,
        manifest_no: value.manifest_no,
        mode_of_transportation: value.mode_of_transportation,
        loading_port: value.loading_port,
        last_vist_foreign_port: value.last_vist_foreign_port,
        us_clearing_district: value.us_clearing_district,
        unloading_port: value.unloading_port,
        place_of_receipt: value.place_of_receipt,
        country: value.country,
        country_sure_level: value.country_sure_level,
        weight_in_kg: value.weight_in_kg,
        weight: value.weight,
        weight_unit: value.weight_unit,
        teu: value.teu,
        quantity: value.quantity,
        quantity_unit: value.quantity_unit,
        measure_in_cm: value.measure_in_cm,
        measure: value.measure,
        measure_unit: value.measure_unit,
        container_id: value.container_id,
        container_size: value.container_size,
        container_type: value.container_type,
        container_desc_code: value.container_desc_code,
        container_load_status: value.container_load_status,
        container_type_of_service: value.container_type_of_service,
        shipper_name: value.shipper_name,
        shipper_address: value.shipper_address,
        raw_shipper_name: value.raw_shipper_name,
        raw_shipper_address_1: value.raw_shipper_address_1,
        raw_shipper_address_2: value.raw_shipper_address_2,
        raw_shipper_address_3: value.raw_shipper_address_3,
        raw_shipper_address_4: value.raw_shipper_address_4,
        raw_shipper_address_others: value.raw_shipper_address_others,
        consignee_name: value.consignee_name,
        consignee_address: value.consignee_address,
        raw_consignee_name: value.raw_consignee_name,
        raw_consignee_address: value.raw_consignee_address,
        raw_consignee_address_1: value.raw_consignee_address_1,
        raw_consignee_address_2: value.raw_consignee_address_2,
        raw_consignee_address_3: value.raw_consignee_address_3,
        raw_consignee_address_4: value.raw_consignee_address_4,
        raw_notify_party_name: value.raw_notify_party_name,
        raw_notify_party_address_1: value.raw_notify_party_address_1,
        raw_notify_party_address_2: value.raw_notify_party_address_2,
        raw_notify_party_address_3: value.raw_notify_party_address_3,
        raw_notify_party_address_4: value.raw_notify_party_address_4,
        raw_notify_party_address_others: value.raw_notify_party_address_others,
        product_description: value.product_description,
        marks_numbers: value.marks_numbers,
        hs_code: value.hs_code,
        hs_code_sure_level: value.hs_code_sure_level,
        cif: value.cif,
        indicator_of_true_supplier: value.indicator_of_true_supplier,
        end: value.end,
        slug_country_ori_shipper: value.slug_country_ori_shipper,
        slug_country_ori_consignee: value.slug_country_ori_consignee,
        slug_consignee_name: value.slug_consignee_name,
        slug_shipper_name: value.slug_shipper_name,
        total_quantity: value.total_quantity,
        total_weight: value.total_weight,
        total_measure: value.total_measure,
        total_shipment: value.total_shipment,
        total_company: 1,
        company_as: 'product'
    };

    emit(value.slug_shipper_name, values);
}

var reduceImporter = function (key, vals) {
    var r = {total_quantity: 0, total_container_count: 0, total_weight: 0, total_shipment: 0, total_measure: 0, total_consignee: 0};
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
        r.total_quantity += vals[i].total_quantity;
        r.total_weight += vals[i].total_weight;
        r.total_measure += vals[i].total_measure;
        r.total_shipment += vals[i].total_shipment;
        r.total_company += vals[i].total_company;
        r.company_as = vals[i].company_as;
    }
    return r;
}

var resSup1 = db.usa_exporter_detail.mapReduce(mapImporter, reduceImporter,
    { out: "usa_product"});