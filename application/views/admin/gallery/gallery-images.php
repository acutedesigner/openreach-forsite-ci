<?php $this->load->view('admin/header'); ?>
<script type="text/javascript">
$(document).ready(function(){

// LOAD COLOR BOX
		$("a[rel='view_image']").colorbox({transition:"elastic", maxWidth:"80%", maxHeight:"80%"});

		$('.remove_gallery_image').jConfirmAction({question : "Remove! Are you sure?"});  	
});	
	
</script>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">

<h1><?php echo $title; ?></h1>
<?php if ($this->message->display('success')): echo '<p class="success">'.$this->message->display('success').'</p>'; endif; ?>

<!-- Display the stored data here -->
<?php if(isset($images)): foreach( $images as $image ): ?>
	
<div class="image-holder">
	<div class="media-image">
		<a rel="view_image" href="<?php echo base_url().'media/'.$image->filename.$image->ext ?>" title="<?php echo $image->caption; ?>">
			<img src="<?php echo base_url().'media/'.$image->filename.'_150x150'.$image->ext ?>" alt="<?php echo $image->caption; ?>" />
		</a>
	</div>
	<ul class="media-buttons">
		<li><?php echo anchor('admin/media/edit/'.$image->filetype."/".$image->id, '&nbsp;', 'title="Edit image" class="edit_image"');?></li>
		<li class="right"><?php echo anchor('admin/gallery/remove/'.$image->id.'/'.$this->uri->segment(4), '&nbsp;', 'title="Remove image from gallery" class="remove_gallery_image"');?></li>
	</ul>
</div>
	
<?php endforeach; ?>

<?php echo $this->pagination->create_links(); ?>

<?php else: ?>
<p class="info">There is no data for this section</p>

<?php endif; ?>

</div>

<?php $this->load->view('admin/footer'); ?>