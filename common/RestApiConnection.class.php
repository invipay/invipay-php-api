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

require_once(dirname(__FILE__)."/RequestMarshaller.class.php");
require_once(dirname(__FILE__)."/ResponseUnmarshaller.class.php");
require_once(dirname(__FILE__)."/SecurityHelper.class.php");


class RestApiConnection
{
	const HTTP_GET = 'GET';
	const HTTP_POST = 'POST';
	const HTTP_PUT = 'PUT';

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	private $apiKey;
	private $signatureKey;
	private $url;

	protected $methodPath;
	protected $query;
	protected $body;
	protected $httpMethod;
	protected $isResponseSignatureCheckDisabled = false;
	protected $requestMarshaller;
	protected $responseUnmarshaller;

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function __construct($url, $apiKey, $signatureKey)
	{
		$this->url = $url;
		$this->apiKey = $apiKey;
		$this->signatureKey = $signatureKey;

		$this->requestMarshaller = new RequestMarshaller($this->apiKey, $this->signatureKey);
		$this->responseUnmarshaller = new ResponseUnmarshaller($this->apiKey, $this->signatureKey);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function setMethodPath($methodPath){ $this->methodPath = $methodPath; return $this; }
	public function getMethodPath(){ return $this->methodPath; }

	public function setQuery(array $query){ $this->query = $query; return $this; }
	public function getQuery(){ return $this->query; }

	public function setBody($body){ $this->body = $body; return $this; }
	public function getBody(){ return $this->body; }

	public function setHttpMethod($httpMethod){ $this->httpMethod = $httpMethod; return $this; }
	public function getHttpMethod(){ return $this->httpMethod; }

	public function isResponseSignatureCheckDisabled(){ return $this->isResponseSignatureCheckDisabled; }
	public function setIsResponseSignatureCheckDisabled($isResponseSignatureCheckDisabled){ $this->isResponseSignatureCheckDisabled = $isResponseSignatureCheckDisabled; return $this; }

	public function getRequestMarshaller(){ return $this->requestMarshaller; }

	public function getResponseUnmarshaller(){ return $this->responseUnmarshaller; }

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function call()
	{
		$queryString = !empty($this->getQuery()) ? http_build_query($this->getQuery()) : null;
		$targetUrl = $this->url . $this->getMethodPath() . ($queryString !== null ? '?' . $queryString : '');
		Logger::trace('Call target URL: ' . $targetUrl);

		$bodyContent = $this->getBody() !== null ? $this->getRequestMarshaller()->marshall($this->getBody()) : null;
		Logger::trace('Call body: ' . $bodyContent);

		$securityHelper = new SecurityHelper($this->apiKey, $this->signatureKey);
		$signature = $securityHelper->calculateSignature($queryString, $bodyContent);
		Logger::trace('Signature: ' . $signature);

		$headers = $this->getHeaders($signature);
		Logger::trace(Logger::format('Headers: {0}', $headers));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $targetUrl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		if ($bodyContent !== null)
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyContent);
		}

		switch ($this->getHttpMethod()) {
			case self::HTTP_GET: curl_setopt($ch, CURLOPT_HTTPGET, true); break;
			case self::HTTP_POST: curl_setopt($ch, CURLOPT_POST, true); break;
			case self::HTTP_PUT: curl_setopt($ch, CURLOPT_PUT, true); break;
			default: break;
		}

		Logger::trace(Logger::format("Calling {0} with http method {1}, body content: {2}", $targetUrl, $this->getHttpMethod(), $bodyContent));

		$response = curl_exec($ch);
		$responseStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$responseHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		curl_close($ch);

		Logger::trace(Logger::format("Response status: {0}, header size: {1}", $responseStatus, $responseHeaderSize));

		$responseHeader = trim(substr($response, 0, $responseHeaderSize));
		$responseBody = trim(substr($response, $responseHeaderSize));

		Logger::trace(Logger::format("Response header: {0}", $responseHeader));
		Logger::trace(Logger::format("Response body: {0}", $responseBody));

		if ($responseStatus != '200')
		{
			$this->setIsResponseSignatureCheckDisabled(true);
			$this->getResponseUnmarshaller()->setOutputClass(new ApiOperationException);
			$this->getResponseUnmarshaller()->setOutputClassResolveFunction(array('ResponseUnmarshaller', 'ResolveExceptionClass'));
			$this->getResponseUnmarshaller()->setIsOutputAnArray(false);
			$this->getResponseUnmarshaller()->setPropertyClassResolveFunctions(array());
			//$this->getResponseUnmarshaller()->addPropertyClassResolveFu
		}

		if (!$this->isResponseSignatureCheckDisabled())
		{
			$responseSignature = $securityHelper->getSignature($responseHeader);
			$securityHelper->checkSignature($responseSignature, $responseBody);
		}

		return $this->getResponseUnmarshaller()->unmarshall($responseBody);
	}

	protected function getHeaders($signature)
	{
		$headers = array( 
		            "Content-type: " . $this->getRequestMarshaller()->getContentType(), 
		            "Accept: application/json,application/octet-stream", 
		            "Cache-Control: no-cache", 
		            "Pragma: no-cache",
		            SecurityHelper::HEADER_APIKEY_KEY . ": " . $this->apiKey,
		            SecurityHelper::HEADER_SIGNATURE_KEY . ": ". $signature
		        );

		return $headers;
	}
}

?>