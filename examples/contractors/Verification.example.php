<?php

require_once(dirname(__FILE__)."/../../ContractorsApiClient.class.php");
require_once(dirname(__FILE__)."/../config.php");

$client = new ContractorsApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY, INVIPAY_PARTNER_API_KEY, INVIPAY_PARTNER_SIGNATURE_KEY);

// calculateVerificationCost
{
	Logger::info('Calculating verification cost');

	$list = array();

	{
		$contractor = new Contractor();
		$contractor->setName('Test contractor '.uniqid());
		$contractor->setTaxPayerNumber('8429067910');
		$contractor->setEmail('test@esr24.pl');
		$contractor->setFax('12312123');
		$contractor->setPhone('234234234');
		$contractor->setWww('www.esr24.pl');

		$list[] = $contractor;
	}

	$result = $client->calculateVerificationCost($list);

	Logger::info('Result is: {0}', $result);
	
	print_separator();
}

// verifyContractor
{

	Logger::info('Starting contractor verification');

	$list = array();

	{
		$contractor = new Contractor();
		$contractor->setName('Test contractor '.uniqid());
		$contractor->setTaxPayerNumber('8429067910');
		$contractor->setEmail('test@esr24.pl');
		$contractor->setFax('12312123');
		$contractor->setPhone('234234234');
		$contractor->setWww('www.esr24.pl');

		$list[] = $contractor;
	}

	$result = $client->verifyContractor($list);

	Logger::info('Result is: {0}', $result);
	
	print_separator();
}

?>