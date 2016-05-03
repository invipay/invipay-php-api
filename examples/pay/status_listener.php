<?php

require_once(dirname(__FILE__)."/config.php");

$client = new PayApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);
$paymentId = null;

{
	Logger::info('Getting object received through callback');
	$data = $client->paymentOperationStateFromCallbackPost();
	Logger::debug('Received from callback: {0}', $data);

	$paymentId = $data->getPaymentId();

	if ($data->getDataType() == 'PaymentDetails')
	{
		$file = dirname(__FILE__)."/repository/" . $paymentId;

		$newData = array('version' => 0, 'data' => $data);
		if (file_exists($file))
		{
			$oldData = unserialize(file_get_contents($file));
			if (!empty($oldData))
			{
				$newData['version'] = $oldData['version'] + 1;
			}
		}

		Logger::info("Putting new data into repository {0}:\r\n{1}", $file, $newData);

		file_put_contents($file, serialize($newData));
	}
	else if ($data->getDataType() == 'PaymentOperationFinalizationInfo')
	{
		// Call transactions api to confirm this event and fetch full transaction details
		$transactionsApiClient = new TransactionsApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);
		$transactionDetails = $transactionsApiClient->getTransaction($data->getData()->getTransactionId());

		$file = dirname(__FILE__)."/repository/" . $paymentId . "_finalization_info.txt";
		Logger::info("Putting transaction data into file {0}\r\n{1}", $file, $transactionDetails);
		file_put_contents($file, Logger::format('{0}', $transactionDetails));
	}
	else if ($data->getDataType() == 'ApiOperationException')
	{
		$file = dirname(__FILE__)."/repository/" . $paymentId . "_exception.txt";
		Logger::info('Putting exception info into file {0}', $file);

		file_put_contents($file, Logger::format('{0}', $data->getData()));
	}
	else
	{
		Logger::info("Got unknown data for payment {0}:\r\n{1}", $paymentId, $data->getData());
	}
	
}

?>