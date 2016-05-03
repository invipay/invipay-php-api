<?php

require_once('config.php');

session_start();
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index';
$apiClient = new PayApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);

if ($action == 'index')
{
	include DIR_VIEWS . '/index.php';
}
else if ($action == 'checkout')
{
	$request = new PaymentStartRequest();
	$request->setStatusUrl(URL_ROOT . '/status_listener.php');
	$request->setDocumentNumber(uniqid('Order/'));
	$request->setBuyerGovId($_REQUEST['buyer_gov_id']);
	$request->setPriceGross($_REQUEST['price_gross']);
	$request->setSubscribedEvents(PayOperationEvents::EMPLOYEES_LIST_CHANGE | PayOperationEvents::PAYMENT_CONFIRMATION);

	$paymentInfo = $apiClient->startPayment($request);
	$paymentId = $paymentInfo->getPaymentId();

	$data = array('version' => 0, 'data' => null);
	file_put_contents(DIR_REPOSITORY . '/' . $paymentId, serialize($data));

	$_SESSION[SESSION_KEY] = $paymentId;

	header('Location: ' . URL_ROOT . '/index.php?action=paygate');
}
else if ($action == 'paygate')
{
	$paymentId = $_SESSION[SESSION_KEY];
	$paymentOperationData = unserialize(file_get_contents(DIR_REPOSITORY . '/' . $paymentId));

	$paymentData = null;
	$paymentDataVersion = $paymentOperationData !== null ? $paymentOperationData['version'] : 0;

	if ($paymentOperationData !== null && $paymentOperationData['data'] !== null)
	{
		$paymentData = $paymentOperationData['data']->getData();
	}

	include DIR_VIEWS . '/paygate.php';
}
else if ($action == 'confirm_sms_input')
{
	$paymentId = $_SESSION[SESSION_KEY];
	$employeeId = $_REQUEST['employee_id'];
	$smsData = $apiClient->beginConfirmation($paymentId, $employeeId);

	include DIR_VIEWS . '/confirm_sms_input.php';
}
else if ($action == 'confirm_final')
{
	$paymentId = $_SESSION[SESSION_KEY];
	$smsCode = $_REQUEST['sms_code'];
	$transactionData = $apiClient->completeConfirmation($paymentId, $smsCode);

	include DIR_VIEWS . '/confirm_final.php';
}
else if ($action == 'add_employees')
{
	$paymentId = $_SESSION[SESSION_KEY];
	$paymentData = unserialize(file_get_contents(DIR_REPOSITORY . '/' . $paymentId));
	
	if (!empty($paymentData) && !empty($paymentData['data']) && !empty($paymentData['data']->getData()->getEmployees()))
	{
		$availableEmployees = $paymentData['data']->getData()->getEmployees();
	}
	else
	{
		$availableEmployees = null;
	}

	include DIR_VIEWS . '/add_employees.php';
}
else if ($action == 'do_add_employees')
{
	$paymentId = $_SESSION[SESSION_KEY];
	$employeeId = isset($_REQUEST['employee_id']) && !empty($_REQUEST['employee_id']) ? $_REQUEST['employee_id'] : null;

	$newEmployee = new EmployeeData();
	$newEmployee->setFirstName($_REQUEST['first_name']);
	$newEmployee->setLastName($_REQUEST['last_name']);
	$newEmployee->setEmail($_REQUEST['email']);
	$newEmployee->setPhone($_REQUEST['phone']);

	if ($employeeId === null)
	{
		$request = new EmployeesCreationWithTransferAuthorizationData();
		$request->setEmployees(array($newEmployee));
		$request->setReturnUrl(URL_ROOT . '/index.php?action=paygate');

		$redirectInfo = $apiClient->addEmployeesWithTransferAuthorization($paymentId, $request);

		header('Location: ' . $redirectInfo->getUrl());
	}
	else
	{
		$request = new EmployeesCreationWithSMSAuthorizationData();
		$request->setEmployees(array($newEmployee));
		$request->setAuthorizingEmployeeId($employeeId);

		$smsData = $apiClient->beginAddingEmployeesWithSMSAuthorization($paymentId, $request);

		include DIR_VIEWS . '/add_employees_sms_input.php';
	}
}
else if ($action == 'confirm_add_employees')
{
	$paymentId = $_SESSION[SESSION_KEY];
	$smsCode = $_REQUEST['sms_code'];
	$apiClient->completeAddingEmployeesWithSMSAuthorization($paymentId, $smsCode);

	header('Location: index.php?action=paygate');
}

?>