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

class Logger
{
	const ERROR = "ERROR";
	const DEBUG = "DEBUG";
	const INFO = "INFO";
	const TRACE = "TRACE";

	protected static $enabledLevels = null;
	protected static $writer = null;

	private function __construct()
	{
	}

	public static function getWriter()
	{
		if (self::$writer === null)
		{
			self::$writer = new StandardLoggerWriter();
		}

		return self::$writer;
	}

	public static function setWriter($writer)
	{
		self::$writer = $writer;
	}

	public static function getEnabledLevels()
	{
		if (self::$enabledLevels === null)
		{
			self::setEnabledLevels(defined('INVIPAY_LOGGER_LEVELS') ? explode(',', INVIPAY_LOGGER_LEVELS) : array());
		}

		return self::$enabledLevels;
	}

	public static function setEnabledLevels(array $levels)
	{
		self::$enabledLevels = $levels;
	}


	public static function info($message, Exception $exception = null)
	{
		self::write(self::INFO, self::findMessageSource(), $message, $exception);
	}

	public static function error($message, Exception $exception = null)
	{
		self::write(self::ERROR, self::findMessageSource(), $message, $exception);
	}

	public static function debug($message, Exception $exception = null)
	{
		self::write(self::DEBUG, self::findMessageSource(), $message, $exception);
	}

	public static function trace($message, Exception $exception = null)
	{
		self::write(self::TRACE, self::findMessageSource(), $message, $exception);
	}

	public static function write($level, $messageSource, $message, Exception $exception = null)
	{
		if (self::isLogLevelEnabled($level))
		{
			$logEntryText = self::formatEntry($level, $messageSource, $message, $exception);

			self::getWriter()->write($logEntryText);
		}
	}

	protected static function formatEntry($level, $messageSource, $message, Exception $exception = null)
	{
		$logEntryText = $level . ' [' . $messageSource .'] - ' . self::format('{0}', $message);

		if ($exception !== null)
		{
			$logEntryText .= self::formatException($exception);
		}

		$logEntryText .= self::getWriter()->getNewLine();

		return $logEntryText;
	}

	protected static function formatException(Exception $exception)
	{
		$output = self::getWriter()->getNewLine() . 'Exception message: ' . $exception->getMessage();
		$output .= self::getWriter()->getNewLine() . 'Exception trace: ' . $exception->getTraceAsString();

		return $output;
	}

	public static function format($format)
	{
		$output = $format;

		for ($i = 1; $i < func_num_args(); $i++)
		{
			$arg = func_get_arg($i);
			$txt = $arg;

			if (is_array($arg))
			{
				$txt = print_r($arg, true);
			}
			else if (is_object($arg))
			{
				ob_start();
				var_dump($arg);
				$txt = ob_get_contents();
				ob_end_clean();
			}
			else if (is_bool($arg))
			{
				$txt = $arg ? 'true' : 'false';
			}
			else if ($arg === null)
			{
				$txt = 'null';
			}

			$output = str_replace('{'.($i - 1).'}', $txt, $output);
		}

		return $output;
	}

	protected static function findMessageSource()
	{
		$trace = debug_backtrace();
		if ($trace !== null && is_array($trace) && count($trace) > 0)
		{
			$caller = $trace[1];
			return basename($caller['file']) . ':' . $caller['line'];
		}

		return "N/A";
	}

	protected static function isLogLevelEnabled($logLevel)
	{
		return in_array($logLevel, self::getEnabledLevels());
	}
}

class StandardLoggerWriter
{
	public function getNewLine()
	{
		return get_environment_new_line();
	}

	public function write($text)
	{
		echo $text;
	}
}

class FileLoggerWriter
{
	protected $filePath;

	public function __construct($filePath)
	{
		$this->filePath = $filePath;
	}

	public function getNewLine()
	{
		return "\r\n";
	}

	public function write($text)
	{
		file_put_contents($this->filePath, $text, FILE_APPEND);
	}
}

?>