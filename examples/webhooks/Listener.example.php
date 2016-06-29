<?php

// http://addons.invipay.localhost/PHP%20API/examples/webhooks/Listener.example.php

require_once(dirname(__FILE__)."/../../common/WebHooksClient.class.php");
require_once(dirname(__FILE__)."/../config.php");

require_once(dirname(__FILE__)."/../../contractors/ContractorsApiWebHooks.class.php");
require_once(dirname(__FILE__)."/../../transactions/TransactionsApiWebHooks.class.php");
require_once(dirname(__FILE__)."/../../protection/ProtectionApiWebHooks.class.php");
require_once(dirname(__FILE__)."/../../liabilities/LiabilitiesApiWebHooks.class.php");

Logger::setWriter(new FileLoggerWriter(dirname(__FILE__). '/Listener.log.txt'));

$client = new WebHooksClient(INVIPAY_PARTNER_API_KEY, INVIPAY_PARTNER_SIGNATURE_KEY); // Note that here we're using PARTNER account so we can listen to events issued on behalf of other Contractors related to our platform

$client->onEvent(ContractorsApiWebHooks::getDefinition('ContractorVerificationCompletedEvent'), 'onContractorVerificationCompletedEvent');
$client->onEvent(ContractorsApiWebHooks::getDefinition('AccountCreationCompletedEvent'), 'onAccountCreationCompletedEvent');
$client->onEvent(ContractorsApiWebHooks::getDefinition('ContractorProfileChangedEvent'), 'onContractorProfileChangedEvent');

$client->onEvent(TransactionsApiWebHooks::getDefinition('TransactionDeletedEvent'), 'onTransactionDeletedEvent');

$client->onEvent(ProtectionApiWebHooks::getDefinition('TransactionProtectionCompletedEvent'), 'onTransactionProtectionCompletedEvent');

$client->onEvent(LiabilitiesApiWebHooks::getDefinition('NewCommissionInvoiceCreatedEvent'), 'onNewCommissionInvoiceCreatedEvent');
$client->onEvent(LiabilitiesApiWebHooks::getDefinition('NewInterestInvoiceCreatedEvent'), 'onNewInterestInvoiceCreatedEvent');
$client->onEvent(LiabilitiesApiWebHooks::getDefinition('NewInterestNoteCreatedEvent'), 'onNewInterestNoteCreatedEvent');

$client->handle();

function onContractorVerificationCompletedEvent($data)
{
	Logger::info('Contractor verification completed with data: {0}', $data);
}

function onAccountCreationCompletedEvent($data)
{
	Logger::info('Account creation completed with data: {0}', $data);
}

function onContractorProfileChangedEvent($data)
{
	Logger::info('Contractor profile changed with data: {0}', $data);
}

function onTransactionDeletedEvent($data)
{
	Logger::info('Transaction deleted with data: {0}', $data);
}

function onTransactionProtectionCompletedEvent($data)
{
	Logger::info('Transaction protection completed with data: {0}', $data);
}

function onNewCommissionInvoiceCreatedEvent($data)
{
	Logger::info('Commission invoice created with data: {0}', $data);
}

function onNewInterestInvoiceCreatedEvent($data)
{
	Logger::info('Interest invoice created with data: {0}', $data);
}

function onNewInterestNoteCreatedEvent($data)
{
	Logger::info('Interest note created with data: {0}', $data);
}

?>