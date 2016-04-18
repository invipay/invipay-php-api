<?php

/**
*	Copyright (C) 2016 inviPay.com
*	
*	http://www.invipay.com
*
*	@author Kuba Pilecki (kpilecki@invipay.com)
* 	@version 2.0
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

require_once(dirname(__FILE__)."/common/BaseApiClient.class.php");
require_once(dirname(__FILE__)."/common/exceptions/ObjectNotFoundException.class.php");
require_once(dirname(__FILE__)."/common/exceptions/AuthorizationException.class.php");

require_once(dirname(__FILE__)."/common/dto/SmsAuthorizationDetails.class.php");
require_once(dirname(__FILE__)."/common/dto/TransferAuthorizationDetails.class.php");

require_once(dirname(__FILE__)."/transactions/dto/TransactionDetails.class.php");

require_once(dirname(__FILE__)."/pay/dto/EmployeeData.class.php");
require_once(dirname(__FILE__)."/pay/dto/EmployeeDetails.class.php");
require_once(dirname(__FILE__)."/pay/dto/EmployeesCreationWithSMSAuthorizationData.class.php");
require_once(dirname(__FILE__)."/pay/dto/EmployeesCreationWithTransferAuthorizationData.class.php");
require_once(dirname(__FILE__)."/pay/dto/IEmployeesCreationRequest.interface.php");
require_once(dirname(__FILE__)."/pay/dto/OperationState.enum.php");
require_once(dirname(__FILE__)."/pay/dto/PaymentDetails.class.php");
require_once(dirname(__FILE__)."/pay/dto/PaymentManagementData.class.php");
require_once(dirname(__FILE__)."/pay/dto/PaymentOperationDetails.class.php");
require_once(dirname(__FILE__)."/pay/dto/PaymentOperationState.class.php");
require_once(dirname(__FILE__)."/pay/dto/PaymentStartRequest.class.php");
require_once(dirname(__FILE__)."/pay/exceptions/ConfirmationException.class.php");


class PayApiClient extends BaseApiClient
{
	protected function getServiceAddress(){ return '/pay'; }

	////////////////////////////////////////////////////////////////////////////

	public function paymentOperationStateFromCallbackPost()
	{
		$handler = $this->createCallbackHandler();

		$handler->getUnmarshaller()
				->setOutputClass(new PaymentOperationState)
				->addPropertyClassResolveFunction('data', function($rawData){ return array_key_exists('dataType', $rawData) ? $rawData['dataType'] : null; });

		return $handler->handleFromPost();
	}

	////////////////////////////////////////////////////////////////////////////

	public function startPayment(PaymentStartRequest $paymentData)
	{
		$connection = $this->createConnection()
							->setMethodPath('/start')
							->setBody($paymentData)
							->setHttpMethod(RestApiConnection::HTTP_POST);
				
		$connection->getResponseUnmarshaller()->setOutputClass(new PaymentDetails);
				
		return $connection->call();
	}

	public function beginConfirmation($paymentId, $employeeId)
	{
		$connection = $this->createConnection()
							->setMethodPath('/confirm')
							->setQuery(array('paymentId' => $paymentId))
							->setBody($employeeId)
							->setHttpMethod(RestApiConnection::HTTP_POST);
		
		$connection->getRequestMarshaller()->setContentType(RequestMarshaller::CONTENT_TYPE_PLAIN);
		$connection->getResponseUnmarshaller()->setOutputClass(new SmsAuthorizationDetails);
				
		return $connection->call();
	}

	public function completeConfirmation($paymentId, $token)
	{
		$connection = $this->createConnection()
							->setMethodPath('/confirm/complete')
							->setQuery(array('paymentId' => $paymentId))
							->setBody($token)
							->setHttpMethod(RestApiConnection::HTTP_POST);
		
		$connection->getRequestMarshaller()->setContentType(RequestMarshaller::CONTENT_TYPE_PLAIN);
		$connection->getResponseUnmarshaller()->setOutputClass(new TransactionDetails);
				
		return $connection->call();
	}

	////////////////////////////////////////////////////////////////////////////

	public function addEmployeesWithTransferAuthorization($paymentId, EmployeesCreationWithTransferAuthorizationData $data)
	{
		$connection = $this->createConnection()
							->setMethodPath('/employees/add/authorize_by_transfer')
							->setQuery(array('paymentId' => $paymentId))
							->setBody($data)
							->setHttpMethod(RestApiConnection::HTTP_POST);
		
		$connection->getResponseUnmarshaller()->setOutputClass(new TransferAuthorizationDetails);
				
		return $connection->call();
	}

	public function beginAddingEmployeesWithSMSAuthorization($paymentId, EmployeesCreationWithSMSAuthorizationData $data)
	{
		$connection = $this->createConnection()
							->setMethodPath('/employees/add/authorize_by_sms')
							->setQuery(array('paymentId' => $paymentId))
							->setBody($data)
							->setHttpMethod(RestApiConnection::HTTP_POST);
		
		$connection->getResponseUnmarshaller()->setOutputClass(new SmsAuthorizationDetails);
				
		return $connection->call();
	}

	public function completeAddingEmployeesWithSMSAuthorization($paymentId, $token)
	{
		$connection = $this->createConnection()
							->setMethodPath('/employees/add/authorize_by_sms/complete')
							->setQuery(array('paymentId' => $paymentId))
							->setBody($token)
							->setHttpMethod(RestApiConnection::HTTP_POST);
		
		$connection->getRequestMarshaller()->setContentType(RequestMarshaller::CONTENT_TYPE_PLAIN);
		$connection->getResponseUnmarshaller()->setOutputClass(null);
				
		return $connection->call();
	}

	////////////////////////////////////////////////////////////////////////////

	public function managePayment(PaymentManagementData $managementData)
	{
		$connection = $this->createConnection()
							->setMethodPath('/manage')
							->setBody($managementData)
							->setHttpMethod(RestApiConnection::HTTP_POST);
				
		$connection->getResponseUnmarshaller()->setOutputClass(new TransactionDetails);
		
		return $connection->call();
	}
}

?>