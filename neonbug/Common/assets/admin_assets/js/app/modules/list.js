define([ 'global' ], function(global) {
	var exports = {};
	var app_data = {};
	
	function initDelete()
	{
		if (app_data.config.delete_route != null)
		{
			$('.delete-item').click(function() {
				var id_item = this.dataset.idItem;
				var modal = $('.delete-item-modal');
				
				modal.modal({
					blurring: true, 
					onApprove: function() {
						$('.ui.ok', modal).addClass('loading');
						
						$.post(app_data.config.delete_route, { id: id_item }, function(data) {
							//TODO check data.success
							//TODO also check for generic errors, like TokenMismatch (should catch it in Error handler of ajax response); do sth smart in that case .. like .. reload?
							
							modal.modal('hide');
							$('.ui.ok', modal).removeClass('loading');
							
							global.showToast('success', app_data.trans.messages.deleted);
							setTimeout(function() { document.location.reload(); }, 3000);
						}, 'json');
						
						return false;
					}
				}).modal('show');
			});
		}
	}
	
	exports.init = function(trans, config) {
		app_data.trans = trans;
		app_data.config = config;
		
		$(document).ready(function() {
			initDelete();
		});
	};
	
	return exports;	
});
