<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">

<h1><?php echo $title; ?></h1>

<?php if ($this->message->display('error')): echo '<p class="error">'.$this->message->display('error').'</p>'; endif; ?>

<!-- Display the stored data here -->
<?php if(isset($editions)): ?>

<table>
	<thead>
		<th>Edition title</th>
		<th>Edition status</th>
	</thead>
	<tbody>

<?php foreach( $editions as $edition ): ?>
		<tr>
			<td><p><?php echo anchor('admin/archive/create/'.$edition->edition_id, $edition->edition_title); ?></p></td>
			<td><p><?php echo ($edition->status == 0 ? "Deactivated" : "Activated");  ?></p></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>

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
