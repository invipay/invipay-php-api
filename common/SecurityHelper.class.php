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

class SecurityHelper
{
    const HEADER_APIKEY_KEY = "X-InviPay-ApiKey";
    const HEADER_SIGNATURE_KEY = "X-InviPay-Signature";
	const SIGNING_ALGORHITM_NAME = "sha256";

	private $apiKey;
	private $signatureKey;

	public function __construct($apiKey, $signatureKey)
	{
		$this->apiKey = $apiKey;
		$this->signatureKey = $signatureKey;
	}

	public function calculateSignature($queryString, $bodyString)
	{
		if ($queryString !== null && strlen($queryString) > 0 && $queryString[0] == '?')
		{
			$queryString = substr($queryString, 1);
		}

		$fullData = '';
		$fullData .= $queryString !== null ? $queryString : '';
		$fullData .= $bodyString !== null ? $bodyString : '';
		$fullData .= $this->signatureKey;

		return hash(self::SIGNING_ALGORHITM_NAME, $fullData);
	}


	public function getSignature($headers)
	{
		if (is_array($headers))
		{
			return array_key_exists(SecurityHelper::HEADER_SIGNATURE_KEY, $headers) ? $headers[SecurityHelper::HEADER_SIGNATURE_KEY] : null;
		}
		else if (is_string($headers))
		{
			foreach (explode("\n", $headers) as $line)
			{
				$line = trim($line);
				$lineArray = explode(':', $line);
				if (count($lineArray) == 2)
				{
					$key = trim($lineArray[0]);
					$value = trim($lineArray[1]);

					if ($key === SecurityHelper::HEADER_SIGNATURE_KEY)
					{
						return $value;
					}
				}
			}
		}

		return null;
	}

	public function checkSignature($responseSignature, $bodyContent)
	{
		$expectedSignature = $this->calculateSignature(null, $bodyContent);

		if ($expectedSignature !== $responseSignature)
		{
			throw new SignatureException('Expecting signature [' . $expectedSignature . '], got: [' . $responseSignature . ']');
		}

		return true;
	}
}

?>