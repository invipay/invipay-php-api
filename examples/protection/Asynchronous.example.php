<?php

require_once(dirname(__FILE__)."/../../ProtectionAsyncApiClient.class.php");
require_once(dirname(__FILE__)."/../config.php");

$client = new ProtectionAsyncApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY, INVIPAY_PARTNER_API_KEY, INVIPAY_PARTNER_SIGNATURE_KEY);

$request = array();

// Add items to request list

// Transaction 1
{
	$contractor = new Contractor();
	$contractor->setTaxPayerNumber('8429067910');
	$contractor->setEmail('kontakt@firma.com');

	$document = new FileData();
	//$document->setFromFile(dirname(__FILE__).'/../test.pdf');

	$data = new CommonTransactionData();
	$data->setExId('TRANSACTION/1');
	$data->setDocumentNumber(1);
	$data->setIssueDate(date('Y-m-d', time()));
	$data->setDueDate(date('Y-m-d', time() + (24 * 60 * 60 * 7)));
	$data->setPriceGross(101);
	$data->setContractor($contractor);
	$data->setDocument($document);
	$data->setOrder(false);

	$request[] = $data;
}

// Transaction 2
{
	$contractor = new Contractor();
	$contractor->setTaxPayerNumber('9671343097');
	$contractor->setEmail('kontakt@firma.com');

	$document = new FileData();
	$document->setFromFile(dirname(__FILE__).'/../test.pdf');

	$data = new CommonTransactionData();
	$data->setExId('TRANSACTION/2');
	$data->setDocumentNumber(2);
	$data->setIssueDate(date('Y-m-d', time()));
	$data->setDueDate(date('Y-m-d', time() + (24 * 60 * 60 * 7)));
	$data->setPriceGross(102);
	$data->setContractor($contractor);
	$data->setDocument($document);

	$request[] = $data;
}

Logger::info("Calculating protection costs");

$calculations = $client->calculateProtectionCost($request);

Logger::info('Result: {0}', $calculations);

////////////////////////////////////////////////////////


Logger::info("Requesting transactions protection");

$result = $client->protect($request);

Logger::info('Result: {0}', $result);

$max_retries = 10;

while ($result->getItemsLeft() > 0 && --$max_retries > 0)
{
	Logger::info('Checking if results are available. Checks left: {0}.', $max_retries);
	sleep(10);
	$result = $client->getResults($result->getOperationId());
}

?>