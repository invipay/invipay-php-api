<?php

require_once(dirname(__FILE__)."/../../PaygateApiClient.class.php");
require_once(dirname(__FILE__)."/../config.php");

$client = new PaygateApiClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY);


$repository = dirname(__FILE__)."/repository/";
$payments = scandir($repository);

Logger::info('Scanning payments repository');

foreach ($payments as $paymentFile)
{
	if ($paymentFile != '.' && $paymentFile != '..')
	{
		Logger::info(Logger::format('Found payment data {0}', $paymentFile));

		$paymentData = unserialize(file_get_contents($repository.$paymentFile));
		$paymentId = $paymentData->getPaymentId();

		Logger::info(Logger::format('Payment {0} has status {1}', $paymentId, $paymentData->getStatus()));

		if ($paymentData->getStatus() == PaymentRequestStatus::COMPLETED)
		{
			Logger::info(Logger::format('Finalizing payment {0}', $paymentId));

			$request = new PaymentManagementData();
			$request->setPaymentId($paymentId);
			$request->setDoConfirmDelivery(true);

			{
				$conversionData = new OrderToInvoiceData();
				$conversionData->setInvoiceDocumentNumber("TestInvoice/1/2/3/".uniqid());
				$conversionData->setIssueDate(date('Y-m-d', time()));
				$conversionData->setDueDate(date('Y-m-d', time() + (14 * 24 * 60 * 60)));

				$request->setConversionData($conversionData);
			}

			{
				$document = new FileData();
				$document->setFromFile(dirname(__FILE__).'/../test.pdf');

				$request->setDocument($document);
			}

			$result = $client->managePayment($request);

			Logger::info(Logger::format('Result is: {0}', $result));
		}
	}
}

?>