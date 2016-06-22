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

require_once(dirname(__FILE__)."/CompatibilityLayer.php");
require_once(dirname(__FILE__)."/HelperFunctions.php");
require_once(dirname(__FILE__)."/Logger.class.php");
require_once(dirname(__FILE__)."/RestApiConnection.class.php");
require_once(dirname(__FILE__)."/Marshaller.class.php");
require_once(dirname(__FILE__)."/Unmarshaller.class.php");
require_once(dirname(__FILE__)."/ApiCallbackHandler.class.php");
require_once(dirname(__FILE__)."/ApiUrl.class.php");

require_once(dirname(__FILE__)."/exceptions/ApiOperationException.class.php");
require_once(dirname(__FILE__)."/exceptions/AccessFromIpDeniedException.class.php");
require_once(dirname(__FILE__)."/exceptions/AuthenticationException.class.php");
require_once(dirname(__FILE__)."/exceptions/AuthorizationException.class.php");
require_once(dirname(__FILE__)."/exceptions/ObjectNotFoundException.class.php");
require_once(dirname(__FILE__)."/exceptions/ValidationException.class.php");
require_once(dirname(__FILE__)."/exceptions/IntegrationPartnerException.class.php");
require_once(dirname(__FILE__)."/exceptions/AccessDeniedException.class.php");

abstract class BaseApiClient
{
	private $apiKey;
	private $signatureKey;
	private $partnerApiKey;
	private $partnerSignatureKey;
	private $baseUrl;

	public function __construct($baseUrl, $apiKey, $signatureKey, $partnerApiKey = null, $partnerSignatureKey = null)
	{
		$this->baseUrl = $baseUrl !== null ? $baseUrl : BaseApiClient::URL_PRODUCTION;
		$this->apiKey = $apiKey;
		$this->signatureKey = $signatureKey;
		$this->partnerApiKey = $partnerApiKey;
		$this->partnerSignatureKey = $partnerSignatureKey;
	}

	protected abstract function getServiceAddress();

	protected function createConnection()
	{
		return new RestApiConnection($this->baseUrl.$this->getServiceAddress(), $this->apiKey, $this->signatureKey, $this->partnerApiKey, $this->partnerSignatureKey);
	}

	protected function createCallbackHandler()
	{
		return new ApiCallbackHandler($this->apiKey, $this->signatureKey, $this->partnerApiKey, $this->partnerSignatureKey);
	}
}

?>