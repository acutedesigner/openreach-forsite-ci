<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">
<h1><?php echo $title; ?></h1>

<!-- CONTENT FORM -->

<?php echo validation_errors('<p class="error" >'); ?>
<?php if ($this->message->display('success')): echo '<p class="success">'.$this->message->display('success').'</p>'; endif; ?>

<?php echo form_open('admin/content/save/'); ?>
	<p>
		<label for="title">Title</label>
		<input type="text" class="title" name="title" id="name" value="<?php if(isset($formdata)){ echo $formdata->title; } ?>" />
	</p>
	<p>
		<label for="title">Content</label>
		<textarea class="title" name="content" id="content"><?php if(isset($formdata)){ echo $formdata->content; } ?></textarea>
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
		<label for="status">Content status</label>
		<?php echo form_dropdown('status', $options, $selected); ?>
	</p>

<?php if(isset($tags)): ?>
	<p>
		<label for="title">Select Article Tag</label>
		<?php echo form_dropdown('tag_id', $tags, $formdata->tag_id); ?>
	</p>
<?php endif; ?>

<?php if(isset($headerimg)): ?>
	<p>
		<label for="title">Select Header Image</label>
		<?php echo form_dropdown('header_image', $headerimg, $formdata->header_image); ?>
	</p>
<?php endif; ?>

	<p>
		<label for="title">Author</label>
		<select name="author">
		<?php if($formdata->userid) {?>
		<?php echo "<option value=".$formdata->userid.">".$formdata->firstname." ".$formdata->lastname."</option>"; ?>
			<option value="">---------------</option>
		<?php } ?>
<?php if(isset($users)): foreach($users as $user) {
    echo "<option value=".$user->userid.">".$user->firstname." ".$user->lastname."</option>";
} endif; ?>
		</select>
	</p>
	<p>
		<label for="title">Post Date</label>
		<input type="text" class="text" name="date_created" id="date_created" value="<?php if(isset($formdata)){ echo date("Y-m-d", strtotime($formdata->date_created)); } ?>" />
	</p>
	<p>
		<?php if(isset($formdata) && isset($formdata->id)){ echo form_hidden('id', $formdata->id); } ?>
		<?php if(isset($formdata)){ echo form_hidden('type', $formdata->type); } ?>
		<?php if(isset($formdata)){ echo form_hidden('parent_id', $formdata->parent_id); } ?>
		<?php if(isset($formdata)){ echo form_hidden('lft', $formdata->lft); } ?>
		<button type="submit" class="positive">Save</button>
	</p>
<?php echo form_close(); ?>
</div>

<script type="text/javascript">
tinymce.init({
    selector: "#content",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>
<script type="text/javascript">
	$(function() {
		$("#date_created").datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>      	

<?php $this->load->view('admin/footer'); ?>