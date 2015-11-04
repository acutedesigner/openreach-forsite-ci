<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">
<h1><?php echo $title; ?></h1>

<!-- CONTENT FORM -->

<?php echo validation_errors('<p class="error" >'); ?>
<?php if ($this->message->display('success')): echo '<p class="success">'.$this->message->display('success').'</p>'; endif; ?>

<?php echo form_open('admin/archive/update/'); ?>

	<p>
		<label for="edition_title">Edition title</label>
		<input type="text" class="title" name="edition_title" id="edition_title" value="<?php if(isset($formdata)){ echo $formdata->edition_title; } ?>" />
	</p>

<?php
	
	$options = array(
                '2'	=>	'Draft Edition',
                '0'	=>	'De-activated',
                '1'	=>	'Activated Edition'
			);

?>
<?php $selected = NULL; if(isset($formdata)){ $selected = $formdata->status; } ?>

	<p>
		<label for="status">Edition status</label>
		<?php echo form_dropdown('status', $options, $selected); ?>
	</p>

	<p>
		<?php if(isset($formdata) && isset($formdata->edition_id)){ echo form_hidden('edition_id', $formdata->edition_id); } ?>
		<?php if(isset($formdata) && $formdata->old_title != NULL){ echo form_hidden('old_title', $formdata->old_title); } ?>
		<button type="submit" class="positive">Save</button>
	</p>
<?php echo form_close(); ?>
</div>

<?php $this->load->view('admin/footer'); ?>