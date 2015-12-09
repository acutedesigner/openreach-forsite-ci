<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">
<h1><?php echo $title; ?></h1>

<!-- CONTENT FORM -->

<?php echo validation_errors('<p class="error" >'); ?>
<?php if ($this->message->display('success')): echo '<p class="success">'.$this->message->display('success').'</p>'; endif; ?>

<?php echo form_open('admin/newsletters/update/'); ?>

	<p>
		<label for="edition_title">Newsletter title</label>
		<input type="text" class="title" name="title" id="title" value="<?php if(isset($formdata) && isset($formdata->title)){ echo $formdata->title; } ?>" />
	</p>

<?php	
	$options = array(
                '2'	=>	'Draft',
                '1'	=>	'Active',
                '0'	=>	'De-activated',
			);
?>

<?php $selected = NULL; if(isset($formdata) && isset($formdata->status)){ $selected = $formdata->status; } ?>

	<p>
		<label for="status">Newsletter status</label>
		<?php echo form_dropdown('status', $options, $selected); ?>
	</p>

	<p>
		<?php if(isset($formdata) && isset($formdata->id)){ echo form_hidden('id', $formdata->id); } ?>
		<button type="submit" class="positive">Save</button>
	</p>
<?php echo form_close(); ?>
</div>

<?php $this->load->view('admin/footer'); ?>