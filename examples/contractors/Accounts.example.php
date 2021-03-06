<?php

require_once(dirname(__FILE__)."/../../ContractorsApiClient.class.php");
require_once(dirname(__FILE__)."/../config.php");

// Note that here we're using partner's api keys as for those methods we don't have contractor's account on whose behalf we can act
$client = new ContractorsApiClient(INVIPAY_API_URL, INVIPAY_PARTNER_API_KEY, INVIPAY_PARTNER_SIGNATURE_KEY);

// // getAccountData by id
// {
// 	$result = $client->getAccountData('00000000-0000-0000-0000-0000000000b5', null);
// 	Logger::info('Result is: {0}', $result);
// 	print_separator();
// }

// // getAccountData by nip
// {
// 	$result = $client->getAccountData(null, '9114272913');
// 	Logger::info('Result is: {0}', $result);
// 	print_separator();
// }

// // getAccountApiKeys by nip
// {
// 	$result = $client->getAccountApiKeys('00000000-0000-0000-0000-0000000000b5');
// 	Logger::info('Result is: {0}', $result);
// 	print_separator();
// }

$createAccountOperationId = null;

// createAccount
{
	$contractor = new Contractor();
	$contractor->setName('Test contractor '.uniqid());
	$contractor->setTaxPayerNumber('5271711106');
	$contractor->setEmail('test@esr24.pl');
	$contractor->setFax('12312123');
	$contractor->setPhone('234234234');
	$contractor->setWww('www.esr24.pl');

	$employees = array();

	{
		$employee = new EmployeeData();
		$employee->setFirstName('Jan');
		$employee->setLastName('Kowalski');
		$employee->setEmail('jankowalski@esr24.pl');
		$employee->setPhone('123123123');

		$employees[] = $employee;
	}

	$data = new AccountData();
	$data->setExId('exAccount' . uniqid());
	$data->setContractor($contractor);
	$data->setEmployees($employees);

	$result = $client->createAccount($data);

	$createAccountOperationId = $result->getOperationId();

	Logger::info('Result is: {0}', $result);
	print_separator();
}

// getAccountCreationResult
{
	$result = $client->getAccountCreationResult($createAccountOperationId);
	Logger::info('Result is: {0}', $result);
	print_separator();
}


?>