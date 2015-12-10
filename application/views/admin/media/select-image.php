<?php $this->load->view('admin/header'); ?>
<script type="text/javascript">
$(document).ready(function(){

// LOAD COLOR BOX
		
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

	   		window.opener.updateImage(image);
	   		window.close();
	   });
});	
	
</script>

<div class="span-17 last">

<?php if(isset($images)): foreach( $images as $image ): ?>
	
<div class="image-holder">
	<div class="media-image">
		<a class="image" data-image-id="<?php echo $image->id; ?>" href="<?php echo base_url().'media/'.$image->filename.$image->ext ?>" title="<?php echo $image->caption; ?>">
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
