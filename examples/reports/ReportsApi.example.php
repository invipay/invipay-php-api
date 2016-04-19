<?php

require_once(dirname(__FILE__)."/../../ReportsApiClient.class.php");
require_once(dirname(__FILE__)."/../config.php");

$client = new ReportsApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);


// Current InviPay's server date and time (HTTP GET test)
{
	Logger::info('Calling getAccountantReport method of ReportsApiClient');

	$filter = new ReportFilter();
	$filter->setFromDate('2015-01-01 00:00:00');
	$filter->setToDate(date('Y-m-d H:I:s', time()));

	$result = $client->getAccountantReport($filter);

	Logger::info(Logger::format('Result is: {0}', $result));
	
	print_separator();
}

?>