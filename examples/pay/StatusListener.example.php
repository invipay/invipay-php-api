<?php

require_once(dirname(__FILE__)."/../../PayApiClient.class.php");
require_once(dirname(__FILE__)."/../Config.php");

$client = new PayApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);

Logger::setWriter(new FileLoggerWriter(dirname(__FILE__)."/StatusListener.dump.txt"));

$paymentId = null;

{
	Logger::info('Getting object received through callback');
	$data = $client->paymentOperationStateFromCallbackPost();
	Logger::trace(Logger::format('Received from callback: {0}', $data));

	$paymentId = $data->getPaymentId();
	$file = dirname(__FILE__)."/repository/" . $paymentId . ".txt";
	file_put_contents($file, serialize($data));
}

?>