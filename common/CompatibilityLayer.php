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


if (version_compare(PHP_VERSION, '5.3.0') >= 0)
{
    if (__NAMESPACE__ !== '')
    {
        define('INVIPAY_COMPATIBILITY_LAYER_53', true);
        require_once "CompatibilityLayer.53.php";
    }
}

if(!function_exists('lcfirst'))
{
    function lcfirst($str)
    {
        $str = $str . '';
        if (strlen($str) > 0)
        {
            $str[0] = strtolower($str[0]);
            return $str;
        }

        return $str;
    }
}

if(!function_exists('ucfirst'))
{
    function ucfirst($str)
    {
        $str = $str . '';
        if (strlen($str) > 0)
        {
            $str[0] = strtoupper($str[0]);
            return $str;
        }

        return $str;
    }
}

function call_user_func_array_ns($callback, $param_arr)
{
    return call_user_func_array(fix_callback_ns($callback), $param_arr);
}

function call_user_func_ns($callback)
{
    $args = func_get_args();
    array_shift($args);
    return call_user_func_array_ns($callback, $args);
}

function is_callable_ns($callback)
{
    return is_callable(fix_callback_ns($callback));
}

function fix_callback_ns($callback)
{
    if (defined('INVIPAY_COMPATIBILITY_LAYER_53') && is_array($callback))
    {
        $callback[0] = "\\InviPay\\".$callback[0];
    }

    return $callback;
}

?>