<?php

require_once(dirname(__FILE__)."/../../TransactionsApiClient.class.php");
require_once(dirname(__FILE__)."/../Config.php");

$client = new TransactionsApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);

$randomItemId = null;
$randomItemAttachmentId = null;

// listTransactions
{
	Logger::info('Listing transactions');

	$filter = new TransactionsFilter();
	$filter->setSide(TransactionSide::SALE);

	$result = $client->listTransactions($filter);

	$randomItemIndex = array_rand($result);
	
	if ($randomItemIndex !== false && $randomItemIndex !== null)
	{
		$randomItemId = $result[$randomItemIndex]->getId();
		$randomItemAttachmentId = $result[$randomItemIndex]->getAttachment() !== null ? $result[$randomItemIndex]->getAttachment()->getId() : null;
	}
		
	Logger::info(Logger::format('Result is: {0}', $result));
	
	print_separator();
}

if ($randomItemId !== null)
{
	Logger::info(Logger::format('Getting details of transaction {0}', $randomItemId));
	$result = $client->getTransaction($randomItemId);

	Logger::info(Logger::format('Result is: {0}', $result));
	
	print_separator();
}

if ($randomItemAttachmentId !== null)
{
	Logger::info(Logger::format('Downloading document {0} of transaction {1}', $randomItemAttachmentId, $randomItemId));

	$result = $client->downloadDocument($randomItemAttachmentId);
	$outputPath = dirname(__FILE__).'/'.$randomItemAttachmentId.'.pdf';
	file_put_contents($outputPath, $result);

	Logger::info(Logger::format('Saved {0} bytes of data to file {1}', strlen($result), $outputPath));
}

?>