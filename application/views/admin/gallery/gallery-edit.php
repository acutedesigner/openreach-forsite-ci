<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">
<h1><?php echo $title; ?></h1>

<!-- CONTENT FORM -->

<?php echo validation_errors('<p class="error" >'); ?>
<?php if ($this->message->display('success')): echo '<p class="success">'.$this->message->display('success').'</p>'; endif; ?>

<?php echo form_open('admin/gallery/save/'); ?>
	<p>
		<label for="title">Gallery Name</label>
		<input type="text" class="title" name="name" id="name" value="<?php if(isset($formdata)){ echo $formdata->name; } ?>" />
	</p>
<p>
		<?php if(isset($formdata->id) && $formdata->id != NULL){ echo form_hidden('id', $formdata->id); } ?>
		<?php if(isset($formdata)){ echo form_hidden('date_created', $formdata->date_created); } ?>
		<?php if(isset($formdata)){ echo form_hidden('locked', $formdata->locked); } ?>
		<button type="submit" class="positive">Save</button>
</p>
<?php echo form_close(); ?>
</div>
<script type="text/javascript">
	$(function() {
		$("#created").datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>      	

<?php $this->load->view('admin/footer'); ?>