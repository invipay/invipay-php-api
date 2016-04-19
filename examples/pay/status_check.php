<?php

require_once('config.php');

$paymentId = $_REQUEST['paymentId'];
$version = $_REQUEST['version'];

$paymentFile = realpath(DIR_REPOSITORY . '/' . $paymentId);
$output = null;

Logger::debug(Logger::format("Checking repository {1} for payment's {1} data newer then {2}", $paymentFile, $paymentId, $version));

if (dirname($paymentFile) == DIR_REPOSITORY && file_exists($paymentFile)) {

	$data = unserialize(file_get_contents($paymentFile));
	
	if (!empty($data) && is_array($data) && array_key_exists('version', $data) && array_key_exists('data', $data))
	{
		$currentDataVersion = $data['version'];
		Logger::debug(Logger::format("Version in repository ({0}):\r\n{1}", $currentDataVersion, $data));

		if ($currentDataVersion > $version)
		{
			$output = $data['data'];
		}
	}
}

// Marshaller class is defined in 'Marshaller.class.php' and is included with whole InviPayApi.
// It provides marshalling of POPO's into JSON representation

$marshaller = new Marshaller();
$output = $marshaller->marshall($output);

Logger::debug(Logger::format("Output: {0}", $output));

die($output);

?>