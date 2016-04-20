var currentInvipayData = null;

waitForNewAsyncData = function(paymentId, currentVersion, hideOnWait)
{
	if (hideOnWait)
	{
		$('.hideOnWait').hide();
		$('.showOnWait').show();
	}

	$.ajax({
	  
	  url: 'status_check.php?paymentId=' + paymentId + '&version=' + currentVersion,
	  dataType: 'text',
	  success: function(data)
	  {
	  	if (data != null)
	  	{
	  		var parsed = JSON.parse(data);

	  		if (parsed != null)
	  		{
		  		currentInvipayData = parsed['data'];
		  		fillUI(currentInvipayData);

				$('.hideOnWait').show();
				$('.showOnWait').hide();
		  		return;
	  		}
	  	}

	  	window.setTimeout(waitForNewAsyncData, 1000, paymentId, currentVersion);
	  },

	});
};

fillUI = function(paymentData)
{
	if (paymentData)
	{
		$('.documentNumber').text(paymentData.documentNumber);
		$('.issueDate').text(paymentData.issueDate);
		$('.priceGross').text(paymentData.priceGross);
		$('.currency').text(paymentData.currency);
		$('.buyer_name').text(paymentData.buyer.name);
		$('.buyer_taxPayerNumber').text(paymentData.buyer.taxPayerNumber);
		$('.buyer_companyGovId').text(paymentData.buyer.companyGovId);

		var usersList = $('.usersList');
		usersList.empty();

		if (paymentData.employees)
		{
			for (var i=0; i<paymentData.employees.length; i++)
			{
				var employee = paymentData.employees[i];
				var domId = 'e_' + employee['employeeId'];
				usersList.append('<li><input type="radio" name="employee_id" value="' + employee['employeeId'] + '" id="' + domId + '"><label for="' + domId + '">' + employee['firstName'] + ' ' + employee['lastName'] + '</label></li>');
			}
		}
	}
};