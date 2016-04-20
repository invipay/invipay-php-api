<?php

require_once(dirname(__FILE__)."/../../TestApiClient.class.php");
require_once(dirname(__FILE__)."/../config.php");

$client = new TestApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);


// Current InviPay's server date and time (HTTP GET test)
{
	Logger::info('Calling getDate method of TestApiClient');

	$result = $client->getDate();

	Logger::info('Result is: {0}', $result);
	
	print_separator();
}

// Current API user's name (HTTP GET + authentication test)
{
	Logger::info('Calling whoAmI method of TestApiClient');
	
	$result = $client->whoAmI();
	
	Logger::info('Result is: {0}', $result);

	print_separator();
}

// Reverse input message (HTTP POST test)
{
	Logger::info('Calling echoMessage method of TestApiClient');

	$result = $client->echoMessage(new EchoIn('Hello world', true));
	
	Logger::info('Result is: {0}', $result);
	
	print_separator();
}


?>