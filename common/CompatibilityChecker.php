<?php

function doInviPayCompatibilityCheck()
{
	$requiredFunctions = array(
			'curl_init',

		);

	foreach ($requiredFunctions as $funcName)
	{
		if (!function_exists($funcName))
		{
			die("Required function [" . $funcName ."] not found in this instalation of PHP! InviPay API can't be used.");
		}
	}
}

doInviPayCompatibilityCheck();

?>