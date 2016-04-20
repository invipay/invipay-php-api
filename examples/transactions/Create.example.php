<?php

require_once(dirname(__FILE__)."/../../TransactionsApiClient.class.php");
require_once(dirname(__FILE__)."/../config.php");

$client = new TransactionsApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);

// Creating order
{
	Logger::info('Creating new order');

	$request = new OrderData();
	$request->setDocumentNumber('Test/'.uniqid());
	$request->setIssueDate(date('Y-m-d', time()));
	$request->setPriceGross(123);
	$request->setCurrency('PLN');
	$request->setNote('Test transaction from API');
	$request->setNoRisk(true);

	$contractor = new Contractor();
	$contractor->setName('Test contractor '.uniqid());
	$contractor->setTaxPayerNumber('8429067910');
	$contractor->setEmail('test@esr24.pl');
	$contractor->setFax('12312123');
	$contractor->setPhone('234234234');
	$contractor->setWww('www.esr24.pl');

	$contractorAccount = new BankAccount();
	$contractorAccount->setBankName('Test bank');
	$contractorAccount->setNumber('PL123123123123123123123');

	$contractor->setAccount($contractorAccount);

	$contractorAddress = new AddressData();
	$contractorAddress->setStreet('Test street 1/2');
	$contractorAddress->setCity('Testville');
	$contractorAddress->setPostCode('00-111');
	$contractorAddress->setCountryCode('PL');

	$contractor->setAddress($contractorAddress);

	$request->setContractor($contractor);

	$pdf = new FileData();
	$pdf->setFromFile(dirname(__FILE__).'/../test.pdf');

	$request->setDocument($pdf);

	$result = $client->createOrder($request);

	Logger::info('Result is: {0}', $result);
	
	print_separator();
}

// Creating invoice
{
	Logger::info('Creating new invoice');

	$request = new InvoiceData();
	$request->setDocumentNumber('Test/'.uniqid());
	$request->setIssueDate(date('Y-m-d', time()));
	$request->setDueDate(date('Y-m-d', time() + (7 * 24 * 60 * 60))); // Due date == issue date + 7 days
	$request->setPriceGross(123);
	$request->setCurrency('PLN');
	$request->setNote('Test transaction from API');
	$request->setNoRisk(true);

	$contractor = new Contractor();
	$contractor->setName('Test contractor '.uniqid());
	$contractor->setTaxPayerNumber('8429067910');
	$contractor->setCompanyGovId('146665640');
	$contractor->setEmail('test@invipay.com');
	$contractor->setFax('12312123');
	$contractor->setPhone('234234234');
	$contractor->setWww('www.esr24.pl');

	$contractorAccount = new BankAccount();
	$contractorAccount->setBankName('Test bank');
	$contractorAccount->setNumber('PL123123123123123123123');

	$contractor->setAccount($contractorAccount);

	$contractorAddress = new AddressData();
	$contractorAddress->setStreet('Test street 1/2');
	$contractorAddress->setCity('Testville');
	$contractorAddress->setPostCode('00-111');
	$contractorAddress->setCountryCode('PL');

	$contractor->setAddress($contractorAddress);

	$request->setContractor($contractor);

	$pdf = new FileData();
	$pdf->setFromFile(dirname(__FILE__).'/../test.pdf');

	$request->setDocument($pdf);

	$result = $client->createInvoice($request);

	Logger::info('Result is: {0}', $result);
	
	print_separator();
}


?>