$(document).ready(function(){

		$("#pagetree").sortable({
            handle: 'div',
            items: "li:not(.ignore)",
			stop: function( event, ui ) {

				if($(ui.item).prev().is('li'))
				{
						
					$.post(methodurl, { node: $(ui.item).attr('id'), node_target: $(ui.item).prev().attr('id'), method: "next" },
					function(data) {
						window.location = siteurl;
	 				});
			
				}
				else if($(ui.item).next().is('li'))
				{
						
					$.post(methodurl, { node: $(ui.item).attr('id'), node_target: $(ui.item).next('li').attr('id'), method: "prev" },
					function(data) {
						window.location = siteurl;
	 				});
			
				}
			}
		});

});
