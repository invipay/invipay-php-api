<?php

/**
*	Copyright (C) 2016 inviPay.com
*	
*	http://www.invipay.com
*
*	Redistribution and use in source and binary forms, with or
*	without modification, are permitted provided that the following
*	conditions are met: Redistributions of source code must retain the
*	above copyright notice, this list of conditions and the following
*	disclaimer. Redistributions in binary form must reproduce the above
*	copyright notice, this list of conditions and the following disclaimer
*	in the documentation and/or other materials provided with the
*	distribution.
*	
*	THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED
*	WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
*	MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN
*	NO EVENT SHALL CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
*	INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
*	BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS
*	OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
*	ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
*	TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
*	USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
*	DAMAGE.
*/

require_once(dirname(__FILE__) ."/example.common.php");
require_once(dirname(__FILE__) ."/../ProtectionAsyncApiClient.class.php");

$api_config = getConfig();

$client = new ProtectionAsyncApiClient($api_config['url'], $api_config['apiKey'], $api_config['signatureKey']);

$operationId = null;

// Order protection
{
	println('<h1>protect</h1>');

	$request = array();

	// Add items to request list

	// Transaction 1
	{
		$contractor = new Contractor();
		$contractor->setTaxPayerNumber('8429067910');
		$contractor->setEmail('kontakt@firma.com');

		$document = new FileData();
		$document->setFromFile(dirname(__FILE__).'/data/test.pdf');

		$data = new CommonTransactionData();
		$data->setExId('TRANSACTION/1');
		$data->setDocumentNumber(1);
		$data->setIssueDate('2016-02-18');
		$data->setDueDate('2016-03-01');
		$data->setPriceGross(101);
		$data->setContractor($contractor);
		$data->setDocument($document);
		$data->setOrder(false);

		$request[] = $data;
	}

	// Transaction 2
	{
		$contractor = new Contractor();
		$contractor->setTaxPayerNumber('9671343097');
		$contractor->setEmail('kontakt@firma.com');

		$document = new FileData();
		$document->setFromFile(dirname(__FILE__).'/data/test.pdf');

		$data = new CommonTransactionData();
		$data->setExId('TRANSACTION/2');
		$data->setDocumentNumber(2);
		$data->setIssueDate('2016-02-18');
		$data->setDueDate('2016-03-01');
		$data->setPriceGross(102);
		$data->setContractor($contractor);
		$data->setDocument($document);

		$request[] = $data;
	}

	try
	{
		$result = $client->protect($request);

		$operationId = $result->getOperationId();

		printDump('<h2>Request</h2>', $request);
		printDump('<h2>Result</h2>', $result);
	}
	catch (Exception $ex)
	{
		printDump('<h2>Exception</h2>', $ex);
	}


	println('<hr>');
}

sleep(30); // WARNING: DON'T DO THIS IN PRODUCTION ENVIRONMENT! YOUR PHP THREAD WILL PROBABLY TIMEOUT!

// Check result
{
	println('<h1>getResults</h1>');

	try
	{
		$result = $client->getResults($operationId);

		printDump('<h2>Result</h2>', $result);
	}
	catch (Exception $ex)
	{
		printDump('<h2>Exception</h2>', $ex);
	}


	println('<hr>');
}

?>