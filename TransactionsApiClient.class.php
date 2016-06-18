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

require_once(dirname(__FILE__) ."/transactions/dto/InvoiceData.class.php");
require_once(dirname(__FILE__) ."/transactions/dto/OrderData.class.php");
require_once(dirname(__FILE__) ."/transactions/dto/OrderConversionData.class.php");
require_once(dirname(__FILE__) ."/transactions/dto/TransactionDetails.class.php");
require_once(dirname(__FILE__) ."/transactions/dto/TransactionItemDetails.class.php");
require_once(dirname(__FILE__) ."/transactions/dto/TransactionsFilter.class.php");
require_once(dirname(__FILE__) ."/transactions/dto/TransactionSide.enum.php");
require_once(dirname(__FILE__) ."/transactions/dto/TransactionType.enum.php");
require_once(dirname(__FILE__) ."/transactions/dto/TransactionDeletedInfo.class.php");
require_once(dirname(__FILE__) ."/transactions/dto/PayoffCost.class.php");
require_once(dirname(__FILE__) ."/transactions/dto/PayoffDetails.class.php");
require_once(dirname(__FILE__) ."/common/dto/FileInfo.class.php");

class TransactionsApiClient extends BaseApiClient
{
	protected function getServiceAddress(){ return '/transactions'; }

	////////////////////////////////////////////////////////////////////////////

	public function createInvoice(InvoiceData $data)
	{
		$connection =  $this->createConnection()
							->setMethodPath('/createInvoice')
							->setBody($data)
							->setHttpMethod(RestApiConnection::HTTP_POST);

		$connection->getResponseUnmarshaller()->setOutputClass(new TransactionDetails);

		return $connection->call();
	}

	public function createOrder(OrderData $data)
	{
		$connection = $this->createConnection()
							->setMethodPath('/createOrder')
							->setBody($data)
							->setHttpMethod(RestApiConnection::HTTP_POST);

		$connection->getResponseUnmarshaller()->setOutputClass(new TransactionDetails);
		
		return $connection->call();
	}

	public function getTransaction($id)
	{
		$connection = $this->createConnection()
							->setMethodPath('/details')
							->setQuery(array('id' => $id))
							->setHttpMethod(RestApiConnection::HTTP_GET);

		$connection->getResponseUnmarshaller()->setOutputClass(new TransactionDetails);

		return $connection->call();
	}

	public function listTransactions(TransactionsFilter $filter)
	{
		$connection = $this->createConnection()
							->setMethodPath('/list')
							->setBody($filter)
							->setHttpMethod(RestApiConnection::HTTP_POST);

		$connection->getResponseUnmarshaller()
					->setOutputClass(new TransactionDetails)
					->setIsOutputAnArray(true);
					
		return $connection->call();
	}

	public function getDocumentInfo($id)
	{
		$connection = $this->createConnection()
							->setMethodPath('/document_info')
							->setQuery(array('id' => $id))
							->setHttpMethod(RestApiConnection::HTTP_GET);

		$connection->getResponseUnmarshaller()->setOutputClass(new FileInfo);
					
		return $connection->call();
	}

	public function downloadDocument($id)
	{
		$connection = $this->createConnection()
							->setMethodPath('/document')
							->setQuery(array('id' => $id))
							->setHttpMethod(RestApiConnection::HTTP_GET)
							->setIsResponseSignatureCheckDisabled(true);

		return $connection->call();
	}

	public function confirmDelivery($id)
	{
		$connection = $this->createConnection()
							->setMethodPath('/confirm_delivery')
							->setQuery(array('id' => $id))
							->setHttpMethod(RestApiConnection::HTTP_GET);

		$connection->getResponseUnmarshaller()->setOutputClass(new TransactionDetails);

		return $connection->call();
	}

	public function convertOrderToInvoice(OrderConversionData $data)
	{
		$connection = $this->createConnection()
							->setMethodPath('/convert')
							->setBody($data)
							->setHttpMethod(RestApiConnection::HTTP_POST);
					
		$connection->getResponseUnmarshaller()->setOutputClass(new TransactionDetails);

		return $connection->call();
	}

	public function attachDocument($id, FileData $document)
	{
		$connection = $this->createConnection()
							->setMethodPath('/document_attach')
							->setQuery(array('id' => $id))
							->setBody($data)
							->setHttpMethod(RestApiConnection::HTTP_POST);

		$connection->getResponseUnmarshaller()->setOutputClass(new TransactionDetails);
		
		return $connection->call();
	}

	public function deleteTransaction($id)
	{
		$connection = $this->createConnection()
							->setMethodPath('/delete')
							->setQuery(array('id' => $id))
							->setHttpMethod(RestApiConnection::HTTP_DELETE);

		$connection->getResponseUnmarshaller()->setOutputClass(new TransactionDeletedInfo);

		return $connection->call();
	}

	public function calculatePayoffCost(array $list)
	{
		$connection = $this->createConnection()
							->setMethodPath('/payoff/cost')
							->setBody($list)
							->setHttpMethod(RestApiConnection::HTTP_POST);

		$connection->getResponseUnmarshaller()->setIsOutputAnArray(true)
												->setOutputClass(new PayoffCost);

		return $connection->call();
	}

	public function startPayoff(array $list)
	{
		$connection = $this->createConnection()
							->setMethodPath('/payoff')
							->setBody($list)
							->setHttpMethod(RestApiConnection::HTTP_POST);

		$connection->getResponseUnmarshaller()->setIsOutputAnArray(true)
												->setOutputClass(new PayoffDetails);

		return $connection->call();
	}
}

?>