<?php

define('URL_ROOT', 'http://kp.invipay.com/pay');

define('DIR_ACTIONS', realpath(dirname(__FILE__) . '/actions'));
define('DIR_VIEWS', realpath(dirname(__FILE__) . '/views'));
define('DIR_REPOSITORY', realpath(dirname(__FILE__) . '/repository'));
define('DIR_SDK', realpath(dirname(__FILE__) . '/../..'));
define('DIR_ROOT', realpath(dirname(__FILE__)));

define('FILE_LOG', DIR_ROOT . '/api.log');

define('SESSION_KEY', 'invipay_payment_id');

require_once(DIR_SDK . '/PayApiClient.class.php');

define('INVIPAY_LOGGER_LEVELS', 'INFO,DEBUG,ERROR,!TRACE');
define('INVIPAY_API_URL', ApiUrl::URL_DEMO);
define('INVIPAY_API_KEY', 'ca526d99-9681-47f7-b7e4-a60d853c9c5b');
define('INVIPAY_SIGNATURE_KEY', '84a1215b-2462-4f2b-99a6-5d8181874ac2');

Logger::setWriter(new FileLoggerWriter(FILE_LOG));

?>