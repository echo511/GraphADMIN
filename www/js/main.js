$(function() {
	$('.node-hint').typeahead({
		hint: false,
		highlight: true,
		minLength: 1
	},
	{
		name: 'states',
		displayKey: 'value',
		source: function(query, process) {
			return $.get($("#nodeTypeaheadSignalUrl").attr('data-url'), {label: query}, function(data) {
				return process(data);
			});
		}
	});

	$.fn.editable.defaults.mode = 'inline';
	$('.node-label,.node-property,.edge-label').editable({
		success: function(response, value) {
			if (response.redirect) {
				window.location.replace(response.redirect);
			}
		}
	});
});
