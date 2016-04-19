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

class Marshaller
{
	const CONTENT_TYPE_JSON = 'application/json;charset="utf-8"';
	const CONTENT_TYPE_PLAIN = 'text/plain';

	protected $contentType = self::CONTENT_TYPE_JSON;

	public function getContentType(){ return $this->contentType; }
	public function setContentType($contentType){ $this->contentType = $contentType; return $this; }

	public function marshall($body)
	{
		$output = null;

		switch ($this->getContentType()) {
			case self::CONTENT_TYPE_JSON: $output = json_encode($this->marshallToArray($body)); break;
			case self::CONTENT_TYPE_PLAIN: $output = $this->marshallToTextPlain($body); break;
			default: break;
		}

		return $output;
	}

	protected function marshallToTextPlain($body)
	{
		return $body . '';
	}

	protected function marshallToArray($body, $propertyPath = '')
	{
		if (is_array($body))
		{
			$output = array();
			
			foreach ($body as $key => $value)
			{
				$output[$key] = $this->marshallToArray($value, $propertyPath.'.'.$key);
			}

			return $output;
		}
		else if (is_object($body))
		{
			return $this->objectToArray($body);
		}
		else
		{
			return $body;
		}
	}

	protected function objectToArray($object)
	{
		$output = array();

		$rclass = new ReflectionClass($object);

		foreach ($rclass->getMethods(ReflectionMethod::IS_PUBLIC) as $rmethod)
		{
			if ($rmethod !== null)
			{
				$propertyName = $rmethod->name;
				if (strrpos($propertyName, 'get', -strlen($propertyName)) !== FALSE)
				{
					$propertyNameKey = substr($propertyName, 3);
					$propertyNameKey = $this->countLowerCaseChars($propertyNameKey) == 0 ? $propertyNameKey : lcfirst($propertyNameKey);
					$propertyValue = $this->marshallToArray($object->$propertyName());
					$output[$propertyNameKey] = $propertyValue;
				}
			}
		}

		return $output;
	}

	protected function countLowerCaseChars($input)
	{
		$match = array();
		preg_match_all('/[a-z]/', $input, $match);
		return count($match[0]);
	}
}

?>