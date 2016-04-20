<?php

require_once(dirname(__FILE__)."/config.php");

$client = new PayApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);
$paymentId = null;

{
	Logger::info('Getting object received through callback');
	$data = $client->paymentOperationStateFromCallbackPost();
	Logger::trace('Received from callback: {0}', $data);

	$paymentId = $data->getPaymentId();
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

	Logger::info('Putting new data into repository {0}:\r\n{1}', $file, $newData);

	file_put_contents($file, serialize($newData));
	
}

?>