<?php

require_once(dirname(__FILE__) ."/example.common.php");
require_once(dirname(__FILE__) ."/../PayApiClient.class.php");

$api_config = getConfig();

$client = new PayApiClient($api_config['url'], $api_config['apiKey'], $api_config['signatureKey']);

$paymentId = null;

// Start payment procedure
{
	$request = new PaymentStartRequest();
	$request->setStatusUrl('http://31.179.212.22:9999/paycallbacktest.php');
	$request->setStatusDataFormat(CallbackDataFormat::JSON);
	$request->setDocumentNumber(uniqid('Test/'));
	$request->setIssueDate(date('Y-m-d', time()));
	$request->setDueDate(date('Y-m-d', time() + (14 * 24 * 60 * 60)));
	$request->setPriceGross(123.00);
	$request->setCurrency("PLN");
	$request->setNote("My note");
	$request->setNoRisk(true);
	$request->setIsInvoice(false);
	$request->setBuyerGovId('8429067910');

	$paymentId = $client->startPayment($request);
}

sleep(2);

{
	$client->selectEmployee($paymentId, '00000000-0000-0000-0000-00000000092f');
}

sleep(2);

{
	$transactionDetails = $client->confirmPayment($paymentId, '1111');

	var_dump($transactionDetails);
}

?>