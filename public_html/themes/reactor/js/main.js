$(document).ready(function(){
	// open dialog links
	$('a.opendialog').click(function(){
		var targ = $(this).attr('href');
		$(targ).dialog('open');
		return false;
	});
});