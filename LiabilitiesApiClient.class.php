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

require_once(dirname(__FILE__) ."/common/dto/ListFilter.class.php");
require_once(dirname(__FILE__) ."/liabilities/dto/CommissionInvoiceDetails.class.php");
require_once(dirname(__FILE__) ."/liabilities/dto/InterestInvoiceDetails.class.php");
require_once(dirname(__FILE__) ."/liabilities/dto/InterestNoteDetails.class.php");

class LiabilitiesApiClient extends BaseApiClient
{
	protected function getServiceAddress(){ return '/liabilities'; }

	////////////////////////////////////////////////////////////////////////////

	public function getCommissionInvoice($id)
	{
		$connection = $this->createConnection()
							->setMethodPath('/ci/details')
							->setQuery(array('id' => $id))
							->setHttpMethod(RestApiConnection::HTTP_GET);

		$connection->getResultUnmarshaller()->setOutputClass(new CommissionInvoiceDetails);

		return $connection->call();
	}

	public function listCommissionInvoices(ListFilter $filter)
	{
		$connection = $this->createConnection()
					->setMethodPath('/ci/list')
					->setBody($filter)
					->setHttpMethod(RestApiConnection::HTTP_POST);
					
		$connection->getResultUnmarshaller()->setOutputClass(new CommissionInvoiceDetails)
											->setIsOutputAnArray(true);

		return $connection->call();
	}

	public function downloadCommissionInvoiceDocument($id)
	{
		$connection = $this->createConnection()
					->setMethodPath('/ci/document')
					->setQuery(array('id' => $id))
					->setHttpMethod(RestApiConnection::HTTP_GET);
					
		$connection->getResultUnmarshaller()->setOutputUnmarshallFunction(RestApiConnection::OUTPUT_FUNCTION_BASE64);

		return $connection->call();
	}

	////////////////////////////////////////////////////////////////////////////

	public function getInterestInvoice($id)
	{
		$connection = $this->createConnection()
					->setMethodPath('/ii/details')
					->setQuery(array('id' => $id))
					->setHttpMethod(RestApiConnection::HTTP_GET);

		$connection->getResultUnmarshaller()->setOutputClass(new InterestInvoiceDetails);

		return $connection->call();
	}

	public function listInterestInvoices(ListFilter $filter)
	{
		$connection = $this->createConnection()
					->setMethodPath('/ii/list')
					->setBody($filter)
					->setHttpMethod(RestApiConnection::HTTP_POST);

		$connection->getResultUnmarshaller()->setOutputClass(new InterestInvoiceDetails)
											->setIsOutputAnArray(true);

		return $connection->call();
	}

	public function downloadInterestInvoiceDocument($id)
	{
		$connection = $this->createConnection()
					->setMethodPath('/ii/document')
					->setQuery(array('id' => $id))
					->setHttpMethod(RestApiConnection::HTTP_GET);

		$connection->getResultUnmarshaller()->setOutputUnmarshallFunction(RestApiConnection::OUTPUT_FUNCTION_BASE64);

		return $connection->call();
	}

	////////////////////////////////////////////////////////////////////////////

	public function getInterestNote($id)
	{
		$connection = $this->createConnection()
					->setMethodPath('/in/details')
					->setQuery(array('id' => $id))
					->setHttpMethod(RestApiConnection::HTTP_GET);

		$connection->getResultUnmarshaller()->setOutputClass(new InterestNoteDetails);

		return $connection->call();
	}

	public function listInterestNotes(ListFilter $filter)
	{
		$connection = $this->createConnection()
					->setMethodPath('/in/list')
					->setBody($filter)
					->setHttpMethod(RestApiConnection::HTTP_POST);

		$connection->getResultUnmarshaller()->setOutputClass(new InterestNoteDetails)
											->setIsOutputAnArray(true);

		return $connection->call();
	}

	public function downloadInterestNoteDocument($id)
	{
		$connection = $this->createConnection()
					->setMethodPath('/in/document')
					->setQuery(array('id' => $id))
					->setHttpMethod(RestApiConnection::HTTP_GET);

		$connection->getResultUnmarshaller()->setOutputUnmarshallFunction(RestApiConnection::OUTPUT_FUNCTION_BASE64);

		return $connection->call();
	}
}

?>