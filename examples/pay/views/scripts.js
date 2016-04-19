var currentInvipayData = null;

waitForNewAsyncData = function(paymentId, currentVersion, targetElement)
{
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
		  		fillUsersLists(targetElement, currentInvipayData.employees);
		  		return;
	  		}
	  	}

	  	window.setTimeout(waitForNewAsyncData, 5000, paymentId, currentVersion, targetElement);
	  },

	});
};

fillUsersLists = function(targetElement, list)
{
	var target = $(targetElement);
	target.empty();

	if (list)
	{
		for (var i=0; i<list.length; i++)
		{
			var employee = list[i];
			var domId = 'employee_' + i;
			target.append('<li><input type="radio" name="employeeId" value="' + employee['employeeId'] + '" id="' + domId + '"><label for="' + domId + '">' + employee['firstName'] + ' ' + employee['lastName'] + '</label></li>');
		}
	}
};