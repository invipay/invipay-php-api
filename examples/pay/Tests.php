<?php

require_once(dirname(__FILE__)."/../../PayApiClient.class.php");
require_once(dirname(__FILE__)."/../Config.php");

$client = new PayApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);

$paymentId = null;

{
	Logger::info('Creating new payment');

	$request = new PaymentStartRequest();
	$request->setStatusUrl("http://aeroapi.localhost/pay/StatusListener.example.php");
	$request->setDocumentNumber(uniqid('Paygate/'));
	$request->setIssueDate(date('Y-m-d', time()));
	$request->setDueDate(date('Y-m-d', time() + (14 * 24 * 60 * 60)));
	$request->setPriceGross(123.00);
	$request->setCurrency("PLN");
	$request->setNote("My note");
	$request->setNoRisk(true);
	$request->setIsInvoice(false);
	$request->setBuyerGovId('8429067910');

	$result = $client->startPayment($request);
	$paymentId = $result->getPaymentId();
	
	Logger::info(Logger::format('Result is: {0}', $result));
}

sleep(5);

if ($paymentId !== null)
{
	Logger::info(Logger::format('Finalizing payment {0} stage 1', $paymentId));

	$result = $client->beginConfirmation($paymentId, '00000000-0000-0000-0000-00000000092f');

	Logger::info(Logger::format('Result is: {0}', $result));
}

sleep(5);

if ($paymentId !== null)
{
	Logger::info(Logger::format('Finalizing payment {0} stage 2', $paymentId));

	$result = $client->completeConfirmation($paymentId, '1111');

	Logger::info(Logger::format('Result is: {0}', $result));
}

?>