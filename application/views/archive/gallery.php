<!-- MAIN COLUMN -->

<div class="row">

<div class="eight columns">
	<div class="cream-block">
		<h1>Image Gallery</h1>

<!-- Display the stored data here -->
<?php if(isset($images)):?>
	<ul class="block-grid five-up">
	<?php foreach( $images as $image ): ?>
	<li>	
		<a class="gallery-image" rel="view_image" href="<?php echo base_url().'media/'.$image->filename.$image->ext ?>">
			<img src="<?php echo base_url().'media/'.$image->filename.'_thumb'.$image->ext ?>" alt="<?php echo $image->caption; ?>" />
		</a>
	</li>	
	<?php endforeach; ?>
	</ul>
<?php echo $this->pagination->create_links(); ?>

<?php else: ?>
<p class="info">There is no data for this section</p>

<?php endif; ?>

	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){

// LOAD COLOR BOX
		$("a[rel='view_image']").colorbox({transition:"elastic", maxWidth:"80%", maxHeight:"80%"});

});	
	
</script>
