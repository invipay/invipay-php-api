<?php

require_once(dirname(__FILE__)."/../../TransactionsApiClient.class.php");
require_once(dirname(__FILE__)."/../config.php");

$client = new TransactionsApiClient(ApiUrl::URL_LOCAL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);

$transactionId = '5f28fb93-db4a-4d1c-9529-4f6e81603ffc';
Logger::info('Getting details of transaction {0}', $transactionId);
$result = $client->getTransaction($transactionId);

Logger::info('Result is: {0}', $result);

print_separator();

?>