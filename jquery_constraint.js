$(document).ready(function() {
	$('#upload_text_file').bind('submit', function() {
		var action = confirm('This will overwrite the current constraint list and database table.  Do you want to proceed?');
		
		return action;
	});
	
	$('table').delegate('form', 'focusout', function(event) {
		processForm(event);
	});
	
	$('table').delegate('form', 'submit', function(event) {
		processForm(event);
	});
});

function processForm(event) {
	event.preventDefault();
	
	var whichForm = event.target;
	var formName = $(whichForm).attr('name');
	var formId = formName.split('_')[1];
	var formSuffix = '_' + formId;
	var constrain = $('input:radio[name=constrain' + formSuffix + ']:checked').val();
	var seed = $('input:radio[name=seed' + formSuffix + ']:checked').val();
	var url = $('input[name=url' + formSuffix + ']').val();
	var url_id = $('input[name=url_id' + formSuffix + ']').val();
	var messsage = '';
	
	$.ajax({
		url: "ajax_forms.php",
		dataType: "json",
		type: "post",
		data: ({ constrain: constrain, seed: seed, url: url, id: url_id }),
		success: function(data) {
			if(data == true) {
				message = "Entry updated";	
			} else {
				message = "Entry could not be updated"
			}
			
			$('span#save_message' + formSuffix).text(message)
				.css('display', 'inline')
				.fadeOut(2000);
		},
		error: function() {
			alert("There was an error loading your data");
		}
	});
}