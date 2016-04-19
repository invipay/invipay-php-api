<?php

require_once('config.php');

$apiClient = new PayApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index';
$actionFile = DIR_ACTIONS . '/' .$action . '.php';
$actionPath = realpath($actionFile);

if (dirname($actionPath) == DIR_ACTIONS && file_exists($actionPath))
{
	session_start();
	require_once($actionPath);
}
else
{
	die('Action [' . $action . '] not found. Action file: [' . $actionPath . '].');
}

?>