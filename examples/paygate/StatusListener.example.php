<?php

require_once(dirname(__FILE__)."/../../PaygateApiClient.class.php");
require_once(dirname(__FILE__)."/../Config.php");

$client = new PaygateApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);

Logger::setWriter(new FileLoggerWriter(dirname(__FILE__)."/StatusListener.dump.txt"));

$paymentId = null;

{
	Logger::info('Getting object received through callback');
	$data = $client->paymentStatusFromCallbackPost();
	Logger::trace(Logger::format('Payment status changed info: {0}', $data));

	$paymentId = $data->getPaymentId();
}

if ($paymentId !== null)
{
	Logger::info(Logger::format('Getting full data of payment {0}', $paymentId));
	$fullData = $client->getPayment($paymentId);

	$file = dirname(__FILE__)."/repository/" . $paymentId . ".txt";
	file_put_contents($file, serialize($fullData));

	Logger::info(Logger::format('Data saved to file {0}', $file));
}

?>