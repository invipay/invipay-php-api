<?php

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

?>