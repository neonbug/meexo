module.exports = {};

var app_data = {};

function initLogin()
{
	$('form').submit(function(e) {
		$('button[type="submit"]').addClass('loading');
	});
}

module.exports.init = function() {
	$(document).ready(function() {
		initLogin();
	});
};
