if (!RedactorPlugins) var RedactorPlugins = {};
 
RedactorPlugins.addblockquote = function()
{
    return {
	    init: function()
	    {
			var button = this.button.add('my-button', 'My Button');
			
			// make your added button as Font Awesome's icon
            this.button.setAwesome('my-button', 'fa-quote-left');
            
			this.button.addCallback(button, this.addblockquote.show);
	    },
	    
        show: function()
        {

            // get the the element
			var htmlContent = this.selection.getHtml();

            // wrap it in the new blockquote
			var newContent = '<blcokquote>' + htmlContent + '</blockquote>';
			
			alert(newContent);
            
            //replace the selected element with the new html
            this.selection.replaceSelection(htmlContent);
            
        }
    };
};
