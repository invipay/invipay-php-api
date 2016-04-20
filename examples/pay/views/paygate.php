<?php include "header.php"; ?>
<!-- 
<?php var_dump($paymentData); ?> -->

<div id="pleaseWait" class="showOnWait">Proszę czekać...</div>

<div class="hideOnWait">
	<h1>Wybierz pracownika:</h1>

	<form method="POST" action="?action=confirm_sms_input">
		<ul class="usersList">
			<?php if ($paymentData != null): ?>
				<?php foreach ($paymentData->getEmployees() as $employee): ?>
					<?php $domId = 'e_' . $employee->getEmployeeId(); ?>
					<li>
						<input type="radio" name="employee_id" value="<?= $employee->getEmployeeId(); ?>" id="<?= $domId; ?>">
						<label for="<?= $domId; ?>"><?= $employee->getFirstName(); ?> <?= $employee->getLastName(); ?></label>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>

		<button type="submit" name="action" value="confirm_sms_input">Potwierdź płatność</button>
		<button type="submit" name="action" value="add_employees">Dodaj nowego pracownika</button>
	</form>
</div>

<div class="hideOnWait">
	<h1>Dane płatności:</h1>
	<table class="paymentDetails">
		<tbody>
			<tr><td>Firma:</td><td class="buyer_name"><?= $paymentData != null ? $paymentData->getBuyer()->getName() : ''; ?></td></tr>
			<tr><td>NIP:</td><td class="buyer_taxPayerNumber"><?= $paymentData != null ? $paymentData->getBuyer()->getTaxPayerNumber() : ''; ?></td></tr>
			<tr><td>REGON:</td><td class="buyer_companyGovId"><?= $paymentData != null ? $paymentData->getBuyer()->getCompanyGovId() : ''; ?></td></tr>
			<tr><td>Numer dokumentu:</td><td class="documentNumber"><?= $paymentData != null ? $paymentData->getDocumentNumber() : ''; ?></td></tr>
			<tr><td>Data wystawienia:</td><td class="issueDate"><?= $paymentData != null ? $paymentData->getIssueDate() : ''; ?></td></tr>
			<tr><td>Kwota:</td><td><span class="priceGross"><?= $paymentData != null ? $paymentData->getPriceGross() : ''; ?></span>&nbsp;<span class="currency"><?= $paymentData != null ? $paymentData->getCurrency() : ''; ?></span></td></tr>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	var paymentId = '<?= $_SESSION[SESSION_KEY]; ?>';

	$(document).ready(function()
	{
		waitForNewAsyncData(paymentId, <?= $paymentDataVersion; ?>, <?= (int)($paymentData == null) ?>);
	});
</script>

<?php include "footer.php"; ?>