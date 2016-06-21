<?php

require_once(dirname(__FILE__)."/../../TransactionsApiClient.class.php");
require_once(dirname(__FILE__)."/../config.php");

$client = new TransactionsApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY, INVIPAY_PARTNER_API_KEY, INVIPAY_PARTNER_SIGNATURE_KEY);

$orderId = null;

// Calculate payoff costs
{
	Logger::info('Getting payoff cost calculation');
	
	$transactionIds = array('4e95f6d9-6b64-4129-8332-ab7163e012f9', '5e15f6d9-6b64-4129-8332-ab7163e012f2');

	$result = $client->calculatePayoffCost($transactionIds);

	Logger::info('Result is: {0}', $result);
}

// Start payoffs
{
	Logger::info('Getting payoff cost calculation');
	
	$transactionIds = array('4e95f6d9-6b64-4129-8332-ab7163e012f9', '5e15f6d9-6b64-4129-8332-ab7163e012f2');

	$result = $client->startPayoff($transactionIds);

	Logger::info('Result is: {0}', $result);
}

?>