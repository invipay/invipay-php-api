<?php

require_once(dirname(__FILE__)."/../common/ApiUrl.class.php");

define('INVIPAY_LOGGER_LEVELS', 'INFO,DEBUG,ERROR,!TRACE');
define('INVIPAY_API_URL', ApiUrl::URL_LOCAL);
define('INVIPAY_API_KEY', '00000000-0000-0000-0000-000000000003');
define('INVIPAY_SIGNATURE_KEY', '00000000-0000-0000-0000-000000000004');

if (true) // Change to true if you want partner platform mode
{
	define('INVIPAY_PARTNER_API_KEY', '00000000-0000-0000-0000-000000000001');
	define('INVIPAY_PARTNER_SIGNATURE_KEY', '00000000-0000-0000-0000-000000000002');
}
else
{
	define('INVIPAY_PARTNER_API_KEY', null);
	define('INVIPAY_PARTNER_SIGNATURE_KEY', null);
}

?>