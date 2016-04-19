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

	$paymentId = $apiClient->startPayment($request);

	$data = array('version' => 0, 'data' => null);
	file_put_contents(DIR_REPOSITORY . '/' . $paymentId, serialize($data));

	$_SESSION[SESSION_KEY] = $paymentId;

	header('Location: ' . URL_ROOT . '/index.php?action=user_select');
}
else if ($action == 'user_select')
{
	include DIR_VIEWS . '/user_select.php';
}
else if ($action == 'confirm_sms_input')
{
	$paymentId = $_SESSION[SESSION_KEY];
	$employeeId = $_REQUEST['employeeId'];
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

?>