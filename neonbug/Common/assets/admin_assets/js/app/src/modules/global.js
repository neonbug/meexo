module.exports = {};

function showToast(type, message)
{
	noty({
		layout: 'customTopRight', 
		type: type, 
		text: message, 
		timeout: 5000, 
		theme: 'custom_relax', 
		animation: {
			open: 'animated bounceInDown', // Animate.css class names
			close: 'animated fadeOutUp' // Animate.css class names
		}
	});
}

module.exports.showToast = function(type, message) {
	showToast(type, message);
};
