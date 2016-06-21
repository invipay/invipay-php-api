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

require_once(dirname(__FILE__)."/Unmarshaller.class.php");
require_once(dirname(__FILE__)."/SecurityHelper.class.php");


class ApiCallbackHandler
{
	private $apiKey;
	private $signatureKey;
	private $partnerApiKey;
	private $partnerSignatureKey;

	protected $isSignatureCheckDisabled = true;
	protected $unmarshaller;

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function __construct($apiKey, $signatureKey, $partnerApiKey = null, $partnerSignatureKey = null)
	{
		$this->apiKey = $apiKey;
		$this->signatureKey = $signatureKey;
		$this->partnerApiKey = $partnerApiKey;
		$this->partnerSignatureKey = $partnerSignatureKey;

		$this->unmarshaller = new Unmarshaller($this->apiKey, $this->signatureKey, $this->partnerApiKey, $this->partnerSignatureKey);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function isSignatureCheckDisabled(){ return $this->isSignatureCheckDisabled; }
	public function setIsSignatureCheckDisabled($isSignatureCheckDisabled){ $this->isSignatureCheckDisabled = $isSignatureCheckDisabled; return $this; }

	public function getUnmarshaller(){ return $this->unmarshaller; }

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function handleFromPost($outputClass = null)
	{
		return $this->handle($_SERVER, file_get_contents('php://input'), $outputClass);
	}

	public function handle($headers, $body, $outputClass = null)
	{
		if ($outputClass !== null)
		{
			$this->getUnmarshaller()->setOutputClass($outputClass);
		}

		Logger::info('Handling callback');
		Logger::trace('Headers: {0}', $headers);
		Logger::trace('Body: {0}', $body);

		if (!$this->isSignatureCheckDisabled())
		{
			$securityHelper = new SecurityHelper($this->apiKey, $this->signatureKey);
			$signature = $securityHelper->getSignature($headers);
			$securityHelper->checkSignature($signature, $body);
		}

		return $this->getUnmarshaller()->unmarshall($body);
	}
}

?>