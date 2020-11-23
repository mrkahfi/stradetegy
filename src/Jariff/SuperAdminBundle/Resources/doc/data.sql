SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE `competitor`;
INSERT INTO `competitor`(`alexa`, `count`, `name`, `website`, `value`) VALUES 
(0,0,'Corbes','corbes.com','corbes'),
(0,0,'Datamyne','datamyne.com','datamyne'),
(0,0,'Datasur','datasur.com','datasur'),
(0,0,'DNB','dnb.com','dnb'),
(0,0,'Great Import Exports','great_import_exports.com','great_import_exports'),
(0,0,'InfoDrive','info_drive.com','info_drive'),
(0,0,'Info Necta','info_necta.com','info_necta'),
(0,0,'Manifest Journals','manifest_journals.com','manifest_journals'),
(0,0,'Panjiva','panjiva.com','panjiva'),
(0,0,'Penta Transactions','penta_transactions.com','penta_transactions'),
(0,0,'Piers','piers.com','piers'),
(0,0,'Top Ease','top_ease.com','top_ease'),
(0,0,'Trade Navigator','trade_navigator.com','trade_navigator'),
(0,0,'Trade Source','trade_source.com','trade_source'),
(0,0,'Veritrade','veritrade.com','veritrade'),
(0,0,'Zepol','zepol.com','zepol');

TRUNCATE TABLE `business_type`;
INSERT INTO `business_type`(`name`, `count`, `value`) VALUES
('Association', 0, 'association'),
('Attorney', 0, 'attorney'),
('Bank', 0, 'bank'),
('Consultant', 0, 'consultant'),
('Customs Broker', 0, 'customs_broker'),
('Foreign Manufacturer', 0, 'foreign_manufacturer'),
('Foreign Supplier', 0, 'foreign_supplier'),
('Importer', 0, 'importer'),
('Private Investigator', 0, 'private_investigator'),
('Research Analyst', 0, 'research_analyst'),
('Trading Company', 0, 'trading_company'),
('Trucking Company', 0, 'trucking_company'),
('U.S. Manufacturer', 0, 'us_manufacturer'),
('U.S. Exporter', 0, 'us_exporter'),
('Unknown', 0, 'unknown'),
('Warehouse', 0, 'warehouse');

TRUNCATE TABLE `lead_source`;
INSERT INTO `lead_source`(`name`, `count`, `value`) VALUES
('Advertisement', 0, 'advertisement');
('Canceled User', 0, 'canceled_user'),
('Suspended User', 0, 'suspended_user'),
('Collections User', 0, 'collections_user'),
('Returning User ', 0, 'returning_user'),
('Contact Form', 0, 'contact_form'),
('Demo Form', 0, 'demo_form'),
('Email to info@stradetegy', 0, 'email_to_info'),
('Email Campaign', 0, 'email_campaign'),
('External Referral', 0, 'external_referral'),
('Facebook', 0, 'facebook'),
('Incomplete Sign Up', 0, 'incomplete_sign_up'),
('Initial Decline', 0, 'initial_decline'),
('LinkedIn', 0, 'linked_in'),
('Live Chat', 0, 'live_chat'),
('Online Sign Up', 0, 'online_sign_up'),
('Phone - Generic', 0, 'phone_generic'),
('Phone - Privacy', 0, 'phone_privacy'),
('Privacy Request', 0, 'privacy_request'),
('Reverse Lead', 0, 'reverse_lead'),
('Shipment Alerts', 0, 'shipment_alerts'),
('Student/Educator', 0, 'student_educator');

TRUNCATE TABLE `lead_stage`;
INSERT INTO `lead_stage`(`name`, `value`) VALUES 
('Awaiting Contract', 'awaiting_contract'),
('Awaiting Payment', 'awaiting_payment'),
('Closed-Lost', 'closed_lost'),
('Closed-Won', 'closed_won'),
('Interested', 'interested'),
('Maybe Later', 'maybe_later'),
('New Lead', 'new_lead'),
('The Pot', 'the_pot'),
('Seeking Approval', 'seeking_approval'),
('Unqualified', 'unqualified');

TRUNCATE TABLE `call_time`;
INSERT INTO `call_time`(`name`, `value`) VALUES 
('PST', 'pst'),
('MST', 'mst'),
('CST', 'cst'),
('EST', 'est');

TRUNCATE TABLE `flag`;
INSERT INTO `flag`(`color`, `count`, `description`, `icon`, `name`, `value`) VALUES 
( '#C50909', 0, 'DONT CONTINUE, Cannot move forward without completing required tasks', '/bundles/jariffproject/admin/img/icons/flag/red.png', 'Red', 'lock'),
( '#09C545', 0, 'Ready to make decision now', '/bundles/jariffproject/admin/img/icons/flag/green.png', 'Green', 'hot'),
( '#C57A09', 0, 'Needs a bit of work ', '/bundles/jariffproject/admin/img/icons/flag/orange.png', 'Orange', 'warm '),
( '#092FC5', 0, 'Needs significant work', '/bundles/jariffproject/admin/img/icons/flag/blue.png', 'Blue', 'cold'),
( '#C5BD09', 0, 'About to expire ', '/bundles/jariffproject/admin/img/icons/flag/full-yellow.png', 'Yellow', 'warning'),
( '#5C09C5', 0, 'No previous activity, auto assigned by system ', '/bundles/jariffproject/admin/img/icons/flag/purple.png', 'Purple', 'new');

TRUNCATE TABLE `data_interest`;
INSERT INTO `data_interest`(`name`, `value`, `count`) VALUES 
('U.S. Imports', 'us_imports', 0),
('U.S. Exports', 'us_exports', 0),
('Latin ', 'latin', 0),
('India', 'india', 0),
('Russia', 'russia', 0),
('China', 'china', 0),
('Canada', 'canada', 0),
('Pakistan', 'pakistan', 0),
('Europe', 'europe', 0),
('Census Data', 'census_data', 0),
('Market (Contact Information)', 'market_contact_information', 0),
('Analytical Graphs', 'analytical_graphs', 0);

TRUNCATE TABLE `lead_activity_result`;
INSERT INTO `lead_activity_result`(`name`, `count`, `value`) VALUES 
('No Answer', 0, 'no_answer'),
('Bad Phone', 0, 'bad_phone'),
('Bad Email', 0, 'bad_email'),
('Technical Issue', 0, 'technical_issue'),
('Presented Product', 0, 'presented_product'),
('Email Sent', 0, 'email_sent'),
('Email Received', 0, 'email_received'),
('Call Contact Established', 0, 'call_contact_established'),
('Call Received', 0, 'call_received'),
('Left Voicemail/Message', 0, 'left_voicemail_message'),
('Report/Data/Research Sent', 0, 'report_data_research_sent');

TRUNCATE TABLE `lead_activity_type`;
INSERT INTO `lead_activity_type`(`name`, `count`, `value`) VALUES 
('Internal', 0, 'internal'),
('Call', 0, 'call'),
('Email', 0, 'email'),
('New Inquiry', 0, 'new_inquiry'),
('Demo', 0, 'demo'),
('Data Research', 0, 'data_research'),
('Final Follow Up', 0, 'final_follow_up');

TRUNCATE TABLE `subscription`;
INSERT INTO `subscription`(`category`, `dateCreate`, `dateUpdate`, `label`, `price`, `value`) VALUES 
('history', now(), now(),  '12 months = $59 per month', 59, 12),
('history', now(), now(),  '24 months = $99 per month', 99, 24),
('history', now(), now(),  '36 months = $150 per month', 150, 36),
('search', now(), now(),  '5 (included)', 0, 5),
('search', now(), now(),  '25 = $10 per month', 10, 25),
('search', now(), now(),  '50 = $20 per month', 20, 50),
('search', now(), now(),  'unlimited = $35 per month', 35, 1000000),
('download', now(), now(),  'Add number of records', 0, 0),
('download', now(), now(),  '1 000 = $30 per month', 30, 1000),
('download', now(), now(),  '5 000 = $40 per month', 40, 5000),
('download', now(), now(),  '25 000 = $60 per month', 60, 25000),
('download', now(), now(),  '100 000 = $70 per month', 70, 100000),
('download', now(), now(),  'unlimited = $80 per month', 80, 10000000),
('bigPicture', now(), now(),  'off', 0, 0),
('bigPicture', now(), now(),  'on = $30 per month', 30, 1),
('month', now(), now(),  '3 months: discount 10%', 10, 3),
('month', now(), now(),  '6 months: discount 15%', 15, 6),
('month', now(), now(),  '12 months: discount 20%', 20, 12);

SET FOREIGN_KEY_CHECKS=1;

