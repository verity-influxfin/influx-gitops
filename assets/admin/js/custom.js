	$(document).ready(function () {
		
		
	});
	
	function ValidateNumber(e, pnumber)
	{
		if (!/^\d+$/.test(pnumber))
		{
			$(e).val(/^\d+/.exec($(e).val()));
		}
		return false;
	}