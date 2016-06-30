<?php

/**
*	Copyright (C) 2016 inviPay.com
*	
*	http://www.invipay.com
*
*	@author Kuba Pilecki (kpilecki@invipay.com)
* 	@version 2.1
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

require_once(dirname(__FILE__) ."/common/dto/Contractor.class.php");
require_once(dirname(__FILE__) ."/contractors/dto/VerificationResults.class.php");
require_once(dirname(__FILE__) ."/contractors/dto/VerificationCost.class.php");
require_once(dirname(__FILE__) ."/contractors/dto/AccountDetails.class.php");
require_once(dirname(__FILE__) ."/contractors/dto/AccountApiKeySet.class.php");
require_once(dirname(__FILE__) ."/contractors/dto/AccountData.class.php");
require_once(dirname(__FILE__) ."/contractors/dto/AccountCreationResult.class.php");

class ContractorsApiClient extends BaseApiClient
{
	protected function getServiceAddress(){ return '/contractors'; }

	////////////////////////////////////////////////////////////////////////////

	public function verifyContractor($data)
	{
		$connection =  $this->createConnection()
							->setMethodPath('/verify')
							->setBody($data)
							->setHttpMethod(RestApiConnection::HTTP_POST);

		$connection->getResponseUnmarshaller()->setOutputClass(new VerificationResult);

		return $connection->call();
	}

	public function getVerificationResult($id)
	{
		$connection =  $this->createConnection()
							->setMethodPath('/verify/result')
							->setQuery(array('id' => $id))
							->setHttpMethod(RestApiConnection::HTTP_GET);

		$connection->getResponseUnmarshaller()->setOutputClass(new VerificationResult);

		return $connection->call();
	}

	public function calculateVerificationCost(array $list)
	{
		$connection =  $this->createConnection()
							->setMethodPath('/verify/cost')
							->setBody($list)
							->setHttpMethod(RestApiConnection::HTTP_POST);

		$connection->getResponseUnmarshaller()->setOutputClass(new VerificationCost)
												->setIsOutputAnArray(true);

		return $connection->call();
	}

	////////////////////////////////////////////////////////////////////////////

	public function getAccountData($id = null, $nip = null)
	{
		$connection =  $this->createConnection()
							->setMethodPath('/account')
							->setQuery(array('id' => $id, 'nip' => $nip))
							->setHttpMethod(RestApiConnection::HTTP_GET);

		$connection->getResponseUnmarshaller()->setOutputClass(new AccountDetails);

		return $connection->call();
	}

	public function getAccountApiKeys($id)
	{
		$connection =  $this->createConnection()
							->setMethodPath('/account/apikeys')
							->setQuery(array('id' => $id))
							->setHttpMethod(RestApiConnection::HTTP_GET);

		$connection->getResponseUnmarshaller()->setOutputClass(new AccountApiKeySet);

		return $connection->call();
	}

	public function createAccount(AccountData $data)
	{
		$connection =  $this->createConnection()
							->setMethodPath('/account/create')
							->setBody($data)
							->setHttpMethod(RestApiConnection::HTTP_POST);

		$connection->getResponseUnmarshaller()->setOutputClass(new AccountCreationResult);

		return $connection->call();
	}

	public function getAccountCreationResult($id)
	{
		$connection =  $this->createConnection()
							->setMethodPath('/account/create/result')
							->setQuery(array('id' => $id))
							->setHttpMethod(RestApiConnection::HTTP_GET);

		$connection->getResponseUnmarshaller()->setOutputClass(new AccountCreationResult);

		return $connection->call();
	}
}

?>