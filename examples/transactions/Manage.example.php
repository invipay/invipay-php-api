<?php

require_once(dirname(__FILE__)."/../../TransactionsApiClient.class.php");
require_once(dirname(__FILE__)."/../config.php");

$client = new TransactionsApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);

$orderId = null;

// Find an order transaction
{
	Logger::info('Searching for an order transaction');
	
	$filter = new TransactionsFilter();
	$filter->setSide(TransactionSide::SALE);
	$filter->setType(TransactionType::ORDER);

	$result = $client->listTransactions($filter);
	$ordersCount = count($result);

	Logger::info(Logger::format('Found {0} orders', $ordersCount));

	if ($ordersCount > 0)
	{
		$orderId = $result[0]->getId();
		Logger::info(Logger::format('Using order {0} for further processing', $orderId));
	}
}

// Convert order to invoice
if ($orderId !== null)
{
	Logger::info(Logger::format('Converting order {0} into invoice', $orderId));

	$pdf = new FileData();
	$pdf->setFromFile(dirname(__FILE__).'/../test.pdf');

	$data = new OrderConversionData();
	$data->setTransactionId($orderId);
	$data->setInvoiceDocumentNumber("Converted from order ".uniqid());
	$data->setIssueDate(date('Y-m-d', time()));
	$data->setDueDate(date('Y-m-d', time() + (30 * 24 * 60 * 60)));
	$data->setInvoiceDocument($pdf);

	$result = $client->convertOrderToInvoice($data);

	Logger::info(Logger::format('Result is: {0}', $result));
}

// Confirm delivery
if ($orderId !== null)
{
	Logger::info(Logger::format('Confirming delivery for transaction {0}', $orderId));
	$result = $client->confirmDelivery($orderId);
	Logger::info(Logger::format('Result is: {0}', $result));
}

?>