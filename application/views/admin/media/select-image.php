<?php $this->load->view('admin/header'); ?>
<?php if($action == 'select'): ?>
<script type="text/javascript">
$(document).ready(function(){
		
		// Match all <A/> links with a title tag and use it as the content (default).
	   $('a[title]').qtip({
			position: {viewport: $(window)}
		});

	   $('.image').on('click', function(e){
	   		e.preventDefault();
	   		var image = {
	   			imageId: $(this).data('image-id'),
	   			imageLink: $(this).find('img').attr('src')
	   		}
	   		win.document.getElementById(field_name).value = image.imageLink;
	   		window.opener.updateImage(image);
	   		window.close();
	   });
});
</script>
<?php elseif($action == 'insert'): ?>
<script type="text/javascript">
$(document).ready(function(){

	$('.image').on('click', function(e){
	   		e.preventDefault();

		var args 	= top.tinymce.activeEditor.windowManager.getParams();
  		win 		= (args.window);
  		input 		= (args.input);
  		win.document.getElementById(input).value = $(this).data('image-file');
		top.tinymce.activeEditor.windowManager.close();

	});

});
</script>
<?php endif; ?>

<div class="span-17 last">

<?php if(isset($images)): foreach( $images as $image ): ?>
	
<div class="image-holder">
	<div class="media-image">
		<a class="image" data-image-id="<?php echo $image->id; ?>" data-image-file="<?php echo base_url().'media/'.$image->filename.$image->ext ?>" href="<?php echo base_url().'media/'.$image->filename.$image->ext ?>" title="<?php echo $image->caption; ?>">
			<img src="<?php echo base_url().'media/'.$image->filename.'_150x150'.$image->ext ?>" alt="<?php echo $image->caption; ?>" />
		</a>
	</div>
</div>
	
<?php endforeach; ?>
<?php echo $this->pagination->create_links(); ?>

<?php else: ?>

<p class="info">There is no data for this section</p>

<?php endif; ?>

</div>

<?php $this->load->view('admin/footer'); ?>
