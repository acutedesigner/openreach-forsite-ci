<?php $this->load->view('admin/header'); ?>
<script type="text/javascript">
$(document).ready(function(){

// LOAD COLOR BOX
		$("a[rel='view_image']").colorbox({transition:"elastic", maxWidth:"80%", maxHeight:"80%"});
		
		$(".add_gallery_image").colorbox({transition:"elastic", width:"80%", height:"250px"});
		
		$('.delete_image').jConfirmAction({question : "Delete! Are you sure?"});  
		
		// Match all <A/> links with a title tag and use it as the content (default).
	   $('a[title]').qtip({
			position: {viewport: $(window)}
		});
});	
	
</script>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">

<h1>Manage Media</h1>
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
		<li><?php echo anchor('admin/gallery/select_gallery/'.$image->id, '&nbsp;', 'title="Add to gallery" class="add_gallery_image" rel="'.site_url().'admin/gallery/select_gallery/'.$image->id.'" ');?></li>
		<li class="right"><?php echo anchor('admin/media/delete/'.$image->filetype."/".$image->id, '&nbsp;', 'title="Delete image from library" class="delete_image"');?></li>
	</ul>
</div>
	
<?php endforeach; ?>
<?php echo $this->pagination->create_links(); ?>

<?php elseif(isset($files)): ?>
<?php echo form_open('admin/media/deletefiles/files'); ?>
<table>
	<thead>
		<th width="10px">&nbsp;</th>
		<th>Title</th>
		<th>File Uploaded</th>
		<th>Edit File</th>
		<th width="10px">&nbsp;</th>
	</thead>
	<tbody>

<?php foreach( $files as $file ): ?>

		<tr>
			<td align="center" bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $file->id; ?>" /></td>
			<td><p><?php echo anchor(base_url().'media/'.$file->filename.$file->ext, $file->display_name); ?></p></td>
			<td><p><?php echo date("Y/m/d", strtotime($file->created)); ?></p></td>
			<td><p><?php echo anchor('admin/media/edit/'.$file->id, "Edit file"); ?></p></td>
			<td>&nbsp;</td>
		</tr>
	
<?php endforeach; ?>
</table>
	<button type="submit" class="positive">Delete</button>
<?php echo form_close(); ?>
<?php echo $this->pagination->create_links(); ?>

<?php else: ?>
<p class="info">There is no data for this section</p>

<?php endif; ?>

</div>

<?php $this->load->view('admin/footer'); ?>