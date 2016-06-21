<?php

require_once(dirname(__FILE__)."/../../common/WebHooksClient.class.php");
require_once(dirname(__FILE__)."/../config.php");

require_once(dirname(__FILE__)."/../../contractors/ContractorsApiWebHooks.class.php");
require_once(dirname(__FILE__)."/../../transactions/TransactionsApiWebHooks.class.php");
require_once(dirname(__FILE__)."/../../protection/ProtectionApiWebHooks.class.php");

Logger::setWriter(new FileLoggerWriter(dirname(__FILE__). '/Listener.log.txt'));

$client = new WebHooksClient(INVIPAY_API_URL, INVIPAY_API_KEY, INVIPAY_SIGNATURE_KEY, INVIPAY_PARTNER_API_KEY, INVIPAY_PARTNER_SIGNATURE_KEY);

$client->onEvent(ContractorsApiWebHooks::getDefinition('ContractorVerificationCompletedEvent'), 'onContractorVerificationCompletedEvent');
$client->onEvent(ContractorsApiWebHooks::getDefinition('AccountCreationCompletedEvent'), 'onAccountCreationCompletedEvent');
$client->onEvent(ContractorsApiWebHooks::getDefinition('AccountProfileChangedEvent'), 'onAccountProfileChangedEvent');
$client->onEvent(TransactionsApiWebHooks::getDefinition('TransactionDeletedEvent'), 'onTransactionDeletedEvent');
$client->onEvent(ProtectionApiWebHooks::getDefinition('TransactionProtectionCompletedEvent'), 'onTransactionProtectionCompletedEvent');

$client->handle();

function onContractorVerificationCompletedEvent($data)
{
	Logger::info('Contractor verification completed with data: {0}', $data);
}

function onAccountCreationCompletedEvent($data)
{
	Logger::info('Account creation completed with data: {0}', $data);
}

function onAccountProfileChangedEvent($data)
{
	Logger::info('Account profile changed with data: {0}', $data);
}

function onTransactionDeletedEvent($data)
{
	Logger::info('Transaction deleted with data: {0}', $data);
}

function onTransactionProtectionCompletedEvent($data)
{
	Logger::info('Transaction protection completed with data: {0}', $data);
}

?>