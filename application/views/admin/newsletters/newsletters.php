<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">

<h1><?php echo $title; ?></h1>

<?php if ($this->message->display('error')): echo '<p class="error">'.$this->message->display('error').'</p>'; endif; ?>

<?php echo '<p>'.anchor('admin/newsletters/edit', 'Create new Newsletter', 'class="button positive"').'</p>'; ?>
<!-- Display the stored data here -->
<?php if(isset($newsletters)): ?>
<?php echo form_open('admin/newsletters/delete/'); ?>
<table>
	<thead>
		<th>Newsletter title</th>
		<th>Newsletter status</th>
		<th>Newsletter Content</th>
		<th>Delete</th>
	</thead>
	<tbody>

<?php foreach( $newsletters as $newsletter ): ?>
		<tr>
			<td><p><?php echo anchor('admin/newsletters/edit/'.$newsletter->id, $newsletter->title); ?></p></td>
			<?php $status = array( 'Deactivated','Active', 'Draft'); ?>
			<td><p><?php echo $status[$newsletter->status]; ?></p></td>
			<td><p><?php echo anchor('admin/newsletters/content/'.$newsletter->id, 'Manage content'); ?></p></td>
			<td align="center" bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $newsletter->id; ?>" /></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
<button type="submit" class="positive">Delete</button>
<?php echo form_close(); ?>
<?php //echo $this->pagination->create_links(); ?>

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
