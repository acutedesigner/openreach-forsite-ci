<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">

<h1><?php echo $title; ?></h1>

<?php if ($this->message->display('error')): echo '<p class="error">'.$this->message->display('error').'</p>'; endif; ?>

<!-- Display the stored data here -->
<?php if(isset($tags)): ?>
<?php echo form_open('admin/tags/delete/'.$this->uri->segment(4)); ?>
<table>
	<thead>
		<th width="10px">&nbsp;</th>
		<th>Tag name</th>
	</thead>
	<tbody>

<?php foreach( $tags as $tag ): ?>
		<tr>
			<td align="center" bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $tag->id; ?>" /></td>
			<td><p><?php echo anchor('admin/tags/create/'.$tag->id, $tag->tag_name); ?></p></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>

	<button type="submit" class="positive">Delete</button>
<?php echo form_close(); ?>

<?php else: ?>
<?php if(isset($info)){ echo '<p class="info">'.$info.'</p>'; } ?>
<?php if(isset($error)){ echo '<p class="error">'.$error.'</p>'; } ?>
<?php endif; ?>

</div>

<script type="text/javascript">
$(document).ready(function(){

		$('.delete').jConfirmAction({question : "Remove! Are you sure?"});  	
});	
	
</script>

<?php $this->load->view('admin/footer'); ?>
