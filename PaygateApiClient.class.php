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

require_once(dirname(__FILE__) ."/common/BaseApiClient.class.php");

require_once(dirname(__FILE__) ."/paygate/dto/PaymentStartInfo.class.php");
require_once(dirname(__FILE__) ."/paygate/dto/PaymentData.class.php");
require_once(dirname(__FILE__) ."/paygate/dto/PaymentCreationData.class.php");
require_once(dirname(__FILE__) ."/paygate/dto/PaymentManagementDataWithId.class.php");
require_once(dirname(__FILE__) ."/paygate/dto/PaymentStatusChangedInfo.class.php");

require_once(dirname(__FILE__) ."/paygate/exceptions/TransactionContractorException.class.php");

class PaygateApiClient extends BaseApiClient
{
	protected function getServiceAddress(){ return '/paygate'; }

	////////////////////////////////////////////////////////////////////////////

	public function paymentStatusFromCallbackPost()
	{
		return $this->createCallbackHandler()->handleFromPost(new PaymentStatusChangedInfo);
	}

	////////////////////////////////////////////////////////////////////////////

	public function createPayment(PaymentCreationData $paymentData)
	{
		$connection = $this->createConnection()
							->setMethodPath('/create')
							->setBody($paymentData)
							->setHttpMethod(RestApiConnection::HTTP_POST);
				
		$connection->getResponseUnmarshaller()->setOutputClass(new PaymentStartInfo);
				
		return $connection->call();
	}

	public function getPayment($id)
	{
		$connection = $this->createConnection()
							->setMethodPath('/details')
							->setQuery(array('id' => $id))
							->setHttpMethod(RestApiConnection::HTTP_GET);

		$connection->getResponseUnmarshaller()->setOutputClass(new PaymentData);
				
		return $connection->call();
	}

	public function managePayment(PaymentManagementDataWithId $managementData)
	{
		$connection = $this->createConnection()
							->setMethodPath('/manage')
							->setBody($managementData)
							->setHttpMethod(RestApiConnection::HTTP_POST);
				
		$connection->getResponseUnmarshaller()->setOutputClass(new PaymentData);
		
		return $connection->call();
	}
}

?>