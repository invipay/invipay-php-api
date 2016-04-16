<?php

require_once(dirname(__FILE__)."/../../PaygateApiClient.class.php");
require_once(dirname(__FILE__)."/../Config.php");

$client = new PaygateApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);

{
	Logger::info('Creating new payment');

	$request = new PaymentCreationData();
	$request->setReturnUrl("http://aeroapi.localhost/paygate/thankyou.htm");
	$request->setStatusUrl("http://aeroapi.localhost/paygate/StatusListener.example.php");
	$request->setDocumentNumber(uniqid('Paygate/'));
	$request->setIssueDate(date('Y-m-d', time()));
	$request->setDueDate(date('Y-m-d', time() + (14 * 24 * 60 * 60)));
	$request->setPriceGross(123.00);
	$request->setCurrency("PLN");
	$request->setNote("My note");
	$request->setNoRisk(true);
	$request->setIsInvoice(false);
	$request->setBuyerGovId('8429067910');
	$request->setBuyerEmail('test@test.pl');

	$result = $client->createPayment($request);
	
	Logger::info(Logger::format('Result is: {0}', $result));
}

?>