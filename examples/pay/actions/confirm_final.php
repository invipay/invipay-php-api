<?php

$paymentId = $_SESSION[SESSION_KEY];
$smsCode = $_REQUEST['sms_code'];
$transactionData = $apiClient->completeConfirmation($paymentId, $smsCode);

include DIR_VIEWS . '/confirm_final.php';

?>