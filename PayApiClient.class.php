<?php

/**
*	Copyright (C) 2016 inviPay.com
*	
*	http://www.invipay.com
*
*	@author Kuba Pilecki (kpilecki@invipay.com)
* 	@version 1.0.5
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

require_once(dirname(__FILE__) ."/lib/AbstractRestApiClient.class.php");

// require_once(dirname(__FILE__) ."/dto/payapiservice/AuthorizationSMSRequest.class.php");
// require_once(dirname(__FILE__) ."/dto/payapiservice/AuthorizationTransferRequest.class.php");
// require_once(dirname(__FILE__) ."/dto/payapiservice/BuyerEmployee.class.php");
// require_once(dirname(__FILE__) ."/dto/payapiservice/OperationState.enum.php");
// require_once(dirname(__FILE__) ."/dto/payapiservice/PaymentConfiguration.class.php");
// require_once(dirname(__FILE__) ."/dto/payapiservice/PaymentConfimationRequest.class.php");
// require_once(dirname(__FILE__) ."/dto/payapiservice/PaymentOperationState.class.php");
// require_once(dirname(__FILE__) ."/dto/payapiservice/PaymentStartRequest.class.php");
// require_once(dirname(__FILE__) ."/dto/payapiservice/BankTransferRedirectionData.class.php");

require_once(dirname(__FILE__) ."/dto/payapiservice/PaymentStartRequest.class.php");
require_once(dirname(__FILE__) ."/dto/transactionapiservice/TransactionDetails.class.php");

require_once(dirname(__FILE__) ."/exceptions/payapiservice/BuyerAccountException.class.php");
require_once(dirname(__FILE__) ."/exceptions/payapiservice/CallbackReceivedException.class.php");
require_once(dirname(__FILE__) ."/exceptions/payapiservice/ConfirmationException.class.php");
require_once(dirname(__FILE__) ."/exceptions/payapiservice/SellerAccountException.class.php");

class PayApiClient extends AbstractRestApiClient
{
	protected function getServiceAddress(){ return '/pay'; }

	////////////////////////////////////////////////////////////////////////////

	public function paymentOperationStateFromCallbackPost($callbackDataFormat, $throwOnError = true)
	{
		return $this->paymentOperationStateFromCallback(file_get_contents('php://input'), $callbackDataFormat, $throwOnError = true);
	}

	public function paymentOperationStateFromCallback($callbackData, $callbackDataFormat = CallbackDataFormat::JSON, $throwOnError = true)
	{
		$output = null;

		if ($this->__checkRequestHash($callbackData))
		{
			switch ($callbackDataFormat)
			{
				case CallbackDataFormat::JSON: $output = $this->__mapArrayToObject(json_decode($callbackData, true), 'PaymentOperationState', false); break;
				case CallbackDataFormat::XML: $output = $this->__mapArrayToObject($this->__xmlToArray($callbackData), 'PaymentOperationState', false); break;
				default: break;
			}

			if ($output !== null)
			{
				$dataString = $output->getData();
				$dataType = $output->getDataType();
				$dataObject = null;

				switch ($callbackDataFormat)
				{
					case CallbackDataFormat::JSON: $dataObject = $this->__mapArrayToObject(json_decode($callbackData, true), $dataType, false); break;
					case CallbackDataFormat::XML: $dataObject = $this->__mapArrayToObject($this->__xmlToArray($callbackData), $dataType, false); break;
					default: break;
				}

				if ($dataObject !== null)
				{
					$output->setData($dataObject);
				}

				if ($throwOnError && $dataObject->getData() instanceof ApiOperationException)
				{
					throw new CallbackReceivedException($output->getPaymentId(), $dataObject->getData());
				}
			}

			return $output;
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function startPayment(PaymentStartRequest $data)
	{
		return $this->__call_ws_action('/start', null, $data, 'POST', null);
	}

	public function selectEmployee($paymentId, $employeeId)
	{
		return $this->__call_ws_action('/buyer/employee/select', array('paymentId' => $paymentId), $employeeId, 'POST', null, false, false, 'text/plain');
	}

	public function confirmPayment($paymentId, $code)
	{
		return $this->__call_ws_action('/confirm', array('paymentId' => $paymentId), $code, 'POST', 'TransactionDetails', false, false, 'text/plain');
	}
}

?>