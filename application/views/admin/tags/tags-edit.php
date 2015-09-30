<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">
<h1><?php echo $title; ?></h1>

<!-- CONTENT FORM -->

<?php echo validation_errors('<p class="error" >'); ?>
<?php if ($this->message->display('success')): echo '<p class="success">'.$this->message->display('success').'</p>'; endif; ?>

<?php echo form_open('admin/tags/update/'); ?>
	<p>
		<label for="title">Tag Name</label>
		<input type="text" class="title" name="tag_name" id="tag_name" value="<?php if(isset($formdata)){ echo $formdata->tag_name; } ?>" />
	</p>
	<p>
		<?php if(isset($formdata) && $formdata->id != NULL){ echo form_hidden('id', $formdata->id); } ?>
		<button type="submit" class="positive">Save</button>
	</p>
<?php echo form_close(); ?>
</div>

<?php $this->load->view('admin/footer'); ?>