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

require_once(dirname(__FILE__)."/exceptions/SignatureException.class.php");

class Unmarshaller
{
	protected $outputClass;
	protected $outputClassResolveFunction = null;
	protected $isOutputAnArray = false;
	protected $outputMarshallFunction = null;
	protected $propertyClassResolveFunctions = array();

	public function getOutputClass(){ return $this->outputClass; }
	public function setOutputClass($outputClass){ $this->outputClass = $outputClass; return $this; }

	public function getOutputClassResolveFunction(){ return $this->outputClassResolveFunction; }
	public function setOutputClassResolveFunction($outputClassResolveFunction){ $this->outputClassResolveFunction = $outputClassResolveFunction; return $this; }

	public function isOutputAnArray(){ return $this->isOutputAnArray; }
	public function setIsOutputAnArray($isOutputAnArray){ $this->isOutputAnArray = $isOutputAnArray; return $this; }

	public function getOutputUnmarshallFunction(){ return $this->outputMarshallFunction; }
	public function setOutputUnmarshallFunction($outputMarshallFunction){ $this->outputMarshallFunction = $outputMarshallFunction; return $this; }

	public function getPropertyClassResolveFunctions(){ return $this->propertyClassResolveFunctions; }
	public function setPropertyClassResolveFunctions(array $propertyClassResolveFunctions){ $this->propertyClassResolveFunctions; }
	public function addPropertyClassResolveFunction($propertyPath, $function){ $this->propertyClassResolveFunctions[$propertyPath] = $function; }
	public function removePropertyClassResolveFunction($propertyPath){ if (array_key_exists($propertyPath, $this->propertyClassResolveFunctions)){ unset($this->propertyClassResolveFunctions[$propertyPath]); } }

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public static function OutputBase64($data)
	{
		return base64_decode($data);
	}

	public static function ResolveExceptionClass($data)
	{
		if (is_array($data) && array_key_exists('type', $data))
		{
			$type = $data['type'];

			$target = 'ApiOperationException';

			if (defined('INVIPAY_COMPATIBILITY_LAYER_53'))
			{
				$target = __NAMESPACE__ . '\\' . $target;
				$type = __NAMESPACE__ . '\\' . $type;
			}

			if (is_subclass_of($type, $target))
			{
				return $type;
			}
		}

		return null;
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

	public function unmarshall($body)
	{
		if ($body !== null)
		{
			if ($this->getOutputUnmarshallFunction() !== null)
			{
				return call_user_func($this->getOutputUnmarshallFunction(), $data);
			}
			else
			{
				$json = json_decode($body, true);
				$class = null;

				if ($this->getOutputClassResolveFunction() !== null && is_callable_ns($this->getOutputClassResolveFunction()))
				{
					$class = call_user_func_ns($this->getOutputClassResolveFunction(), $json);
				}

				if ($class === null)
				{
					$class = $this->getOutputClass();
				}

				return $json === null ? $body : ($this->isOutputAnArray() ? $this->mapToArrayOfObjects($json, $class) : $this->mapToObject($json, $class));
			}
		}

		return null;
	}

	public function mapToObject($data, $outputClass, $pathPrefix = null)
	{
		if ($outputClass === null || !is_array($data))
		{
			return $data;
		}
		else
		{
			$class = new ReflectionClass($outputClass);
			$output = null;

			if ($class->getName() === 'ApiOperationException')
			{
				$output = $class->newInstance($outputClass, array_key_exists('message', $data) ? $data['message'] : "", array_key_exists('code', $data) ? $data['code'] : 0);
			}
			else if ($class->isSubClassOf('Exception'))
			{
				$output = $class->newInstance(array_key_exists('type', $data) ? $data['type'] : null, array_key_exists('message', $data) ? $data['message'] : "", array_key_exists('code', $data) ? $data['code'] : 0);
			}
			else
			{
				$output = $class->newInstance();
			}

			foreach ($data as $key => $value)
			{
				$setter = $this->getSetter($class, $output, $key);

				if ($setter !== null)
				{
					$propertyPath = $pathPrefix === null ? $key : $pathPrefix . '.' . $key;
					$propertyIsArray = false;
					$propertyClass = $this->resolvePropertyClass($setter, $propertyPath, $data, $propertyIsArray);

					Logger::trace("Property: {0}, of type: {1}, is array: {2}", $propertyPath, is_object($propertyClass) ? $propertyClass->getName() : $propertyClass, $propertyIsArray);

					$propertyValue = $this->resolvePropertyValue($value, $propertyClass, $propertyIsArray, $propertyPath);
					$setter->invoke($output, $propertyValue);
				}
			}
		}

		return $output;
	}

	protected function getSetter($class, $object, $propertyName)
	{
		$propertySetterName = 'set' . ucfirst($propertyName);

		if ($class->hasMethod($propertySetterName))
		{
			$propertySetter = $class->getMethod($propertySetterName);

			if ($propertySetter->isPublic())
			{
				return $propertySetter;
			}
		}

		return null;
	}

	protected function resolvePropertyClass($setter, $propertyPath, $rawData, &$propertyIsArray)
	{
		$output = null;

		if (array_key_exists($propertyPath, $this->propertyClassResolveFunctions) && is_callable($this->propertyClassResolveFunctions[$propertyPath]))
		{
			$output = call_user_func($this->propertyClassResolveFunctions[$propertyPath], $rawData);
		}

		if ($output === null)
		{
			$parameters = $setter->getParameters();
			$parametersCount = $parameters !== null ? count($parameters) : 0;

			if ($parametersCount > 0)
			{
				if ($parameters[0]->isArray())
				{
					$propertyIsArray = true;

					if ($parametersCount > 1)
					{
						$output = $parameters[1]->getClass()->getName();
					}
				}
				else if ($parameters[0]->getClass() != null)
				{
					$output = $parameters[0]->getClass()->getName();
				}
			}
		}

		return $output;
	}

	protected function resolvePropertyValue($raw, $propertyClass, $propertyIsArray, $propertyPath)
	{		
		if ($propertyIsArray)
		{
			return $this->mapToArrayOfObjects($raw, $propertyClass, $propertyPath);
		}
		else
		{
			return $this->mapToObject($raw, $propertyClass, $propertyPath);
		}
	}

	protected function mapToArrayOfObjects($data, $outputClass, $pathPrefix = null)
	{
		$output = array();

		if (is_array($data))
		{
			foreach ($data as $key => $value)
			{
				$output[$key] = $this->mapToObject($value, $outputClass, $pathPrefix);
			}
		}
		else
		{
			$output[] = $this->mapToObject($data, $outputClass, $pathPrefix);
		}

		return $output;
	}
}

?>