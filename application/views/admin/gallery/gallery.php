<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">

<h1><?php echo $title; ?></h1>

<!-- Display the stored data here -->
<?php if(isset($pages)): ?>
<?php echo form_open('admin/gallery/delete/'); ?>
<table>
	<thead>
		<th width="10px">&nbsp;</th>
		<th>Gallery Title</th>
		<th>Date Created</th>
		<th>Edit</th>
	</thead>
	<tbody>

<?php foreach( $pages as $page ): ?>
		<tr>
			<td align="center" bgcolor="#FFFFFF"><?php if($page->locked == 0): ?><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $page->id; ?>" /><?php endif; ?></td>
			<td><p><?php echo anchor('admin/gallery/view/'.$page->id, $page->name); ?></p></td>
			<td><p><?php echo date("Y/m/d", strtotime($page->date_created)); ?></p></td>
			<td><p><?php echo anchor('admin/gallery/edit/'.$page->id, "Edit Gallery"); ?></p></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
	<button type="submit" class="positive">Delete</button>
<?php echo form_close(); ?>
<?php echo $this->pagination->create_links(); ?>
<?php else: ?>
<?php if(isset($info)){ echo '<p class="info">'.$info.'</p>'; } ?>
<?php if(isset($error)){ echo '<p class="error">'.$error.'</p>'; } ?>
<?php endif; ?>


</div>

<script type="text/javascript">
$('.status').click(function() {

	var url = $(this).attr('href');
			
	var form_data = {
		ajax: '1'	,
		current: '<?php echo uri_string(); ?>'
	};
	
	$.ajax({
		url: url,
		type: 'POST',
		data: form_data,
		success: function(msg) {
			window.location = '<?php echo current_url(); ?>';
		}
	});
	
	return false;
});	

var siteurl = '<?php echo current_url(); ?>';
var methodurl = '<?php echo site_url('/admin/content/move_node');?>';	
</script>
<script>

</script>
<?php $this->load->view('admin/footer'); ?>
