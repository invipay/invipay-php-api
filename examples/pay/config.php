<?php

define('URL_ROOT', 'http://phpapi.invipay.localhost/pay');

define('DIR_ACTIONS', realpath(dirname(__FILE__) . '/actions'));
define('DIR_VIEWS', realpath(dirname(__FILE__) . '/views'));
define('DIR_REPOSITORY', realpath(dirname(__FILE__) . '/repository'));
define('DIR_SDK', realpath(dirname(__FILE__) . '/../..'));
define('DIR_ROOT', realpath(dirname(__FILE__)));

define('FILE_LOG', DIR_ROOT . '/api.log');

define('SESSION_KEY', 'invipay_payment_id');

require_once(DIR_SDK . '/PayApiClient.class.php');
require_once(DIR_SDK . '/TransactionsApiClient.class.php');

define('INVIPAY_LOGGER_LEVELS', 'INFO,DEBUG,ERROR,!TRACE');
define('INVIPAY_API_URL', ApiUrl::URL_LOCAL);
define('INVIPAY_API_KEY', '00000000-0000-0000-0000-000000000001');
define('INVIPAY_SIGNATURE_KEY', '00000000-0000-0000-0000-000000000002');

Logger::setWriter(new FileLoggerWriter(FILE_LOG));

?>