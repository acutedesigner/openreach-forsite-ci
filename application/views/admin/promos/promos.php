<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">

<h1><?php echo $title; ?></h1>

<?php if ($this->message->display('error')): echo '<p class="error">'.$this->message->display('error').'</p>'; endif; ?>

<!-- Display the stored data here -->
<?php if(isset($promos)): ?>
<?php echo form_open('admin/promos/delete/'.$this->uri->segment(4)); ?>
<table>
	<thead>
		<th width="10px">&nbsp;</th>
		<th>Promo offer</th>
	</thead>
	<tbody>

<?php foreach( $promos as $promo ): ?>
		<tr>
			<td align="center" bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $promo->id; ?>" /></td>
			<td><p><?php echo anchor('admin/promos/create/'.$promo->id, $promo->promo_title); ?></p></td>
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
