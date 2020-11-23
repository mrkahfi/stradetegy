crontab pengecek harga yg harus dibayar


GENERATE INVOICE
if new subscription
	new invoice ( either mtm or pif )


GENERATE INVOICE
check every day
get member.status == active and memberSubscription.paymentTerm == mtm
	if this month has no invoice
		new invoice


Code :
1. GET CURRENT ACTIVE SUBSCRIPTION
SELECT * 
FROM member m 
LEFT JOIN member_subscription ms ON m.id = ms.member_id
where 
m.status = 'active'
and ms.active = true
and ms.paymentTerm = 'mtm'

2. IF ANY 

SELECT * 
FROM member m 
LEFT JOIN member_subscription ms ON m.id = ms.member_id




EXECUTE PAYMENT
check every day
get unpaid invoice
if has cc
	process payment
	if approved
		set invoice payment
	if declined
		set status inactive