	$(document).ready(function(){

		$("#pagetree").sortable({
            handle: 'div',
            items: "li:not(.ignore)"
		});

        $('ul.pagetree').nestedSortable({

            disableNesting: 'no-nest',
            forcePlaceholderSize: true,
			listType: 'ul',
            handle: 'div',
            helper: 'clone',
            items: 'li:not(.ignore)',
            maxLevels: 2,
            opacity: 0.6,
            revert: 250,
            placeholder: 'ui-state-highlight',
            tabSize: 25,
            tolerance: 'pointer',
            connectWith: '.pagetree',
            toleranceElement: '> div'

        });

        $( ".pagetree" ).nestedSortable({

            start: function(event, ui) { 
				$('#pagetree').sortable("destroy");
            },

            stop: function(event, ui) { 
				$('#pagetree').sortable({
                     handle: 'div',
                     items: "li:not(.ignore)"
                });

			if($(ui.item).prev().is('li')){
			
				//alert("Sibling of: "+$(ui.item).prev().attr('id'));

				$.post(methodurl, { confirm: "true", new_dir: $(ui.item).prev().attr('id'), selitems: $(ui.item).attr('id'), point: "below" }, 						function(data) {
					window.location = siteurl;
 				}); 
				
			} else {
			
				//alert("Child of: "+$(ui.item).parents('li').attr('id') );

				$.post(methodurl, { confirm: "true", new_dir: $(ui.item).parents('li').attr('id'), selitems: $(ui.item).attr('id'), point: "append" },
				function(data) {
					window.location = siteurl;
 				}); 
			
			}

            }

		}); 
});
