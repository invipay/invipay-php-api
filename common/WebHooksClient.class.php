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

require_once(dirname(__FILE__)."/CompatibilityLayer.php");
require_once(dirname(__FILE__)."/HelperFunctions.php");
require_once(dirname(__FILE__)."/Logger.class.php");

require_once(dirname(__FILE__)."/WebHookDefinition.class.php");
require_once(dirname(__FILE__)."/ApiCallbackHandler.class.php");

require_once(dirname(__FILE__)."/dto/WebHookEvent.class.php");

class WebHooksClientListenerDefinition
{
	private $webHookDefinition;
	private $callback;

	public function __construct(WebHookDefinition $webHookDefinition, $callback)
	{
		$this->webHookDefinition = $webHookDefinition;
		$this->callback = $callback;
	}

	public function getWebHookDefinition()
	{
		return $this->webHookDefinition;
	}

	public function getCallback()
	{
		return $this->callback;
	}
}

class WebHooksClient
{
	private $apiKey;
	private $signatureKey;
	private $partnerApiKey;
	private $partnerSignatureKey;
	private $eventListeners = array();
	private $handler;

	public function __construct($apiKey, $signatureKey, $partnerApiKey = null, $partnerSignatureKey = null)
	{
		$this->apiKey = $apiKey;
		$this->signatureKey = $signatureKey;
		$this->partnerApiKey = $partnerApiKey;
		$this->partnerSignatureKey = $partnerSignatureKey;

		$this->handler = new ApiCallbackHandler($apiKey, $signatureKey, $partnerApiKey, $partnerSignatureKey);
	}

	public function onEvent(WebHookDefinition $definition, $callback)
	{
		$eventType = $definition->getEventType();
		if (!array_key_exists($eventType, $this->eventListeners))
		{
			$this->eventListeners[$eventType] = array();
		}

		$this->eventListeners[$eventType][] = new WebHooksClientListenerDefinition($definition, $callback);
	}

	public function handle()
	{
		$eventWrapper = $this->handler->handleFromPost(new WebHookEvent);
			if ($eventWrapper !== null && $eventWrapper instanceof WebHookEvent)
			{
			$eventType = $eventWrapper->getEventType();

			if (array_key_exists($eventType, $this->eventListeners))
			{
				foreach ($this->eventListeners[$eventType] as $eventListeners)
				{
					$this->callListeners($eventWrapper, $eventListeners);
				}
			}
		}
	}

	protected function callListeners(WebHookEvent $eventWrapper, WebHooksClientListenerDefinition $listener)
	{
		$webHookDefinition = $listener->getWebHookDefinition();
		$callback = $listener->getCallback();

		$unmarshalledData = $this->handler->getUnmarshaller()->mapToObject($eventWrapper->getEventData(), $webHookDefinition->getDataType());

		call_user_func($callback, $unmarshalledData, $eventWrapper->getEventType(), $eventWrapper->getTimestamp());
	}
}

?>