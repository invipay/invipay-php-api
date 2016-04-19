<?php

$paymentId = $_SESSION[SESSION_KEY];
$employeeId = $_REQUEST['employeeId'];
$smsData = $apiClient->beginConfirmation($paymentId, $employeeId);

include DIR_VIEWS . '/confirm_sms_input.php';

?>