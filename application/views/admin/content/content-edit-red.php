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

	<p>
		<label for="title">Select Header Image</label>
		
		<div class="select-header-image">
			<?php if(isset($header_image)): echo $header_image; else: echo "none"; endif; ?>
		</div>
		<a id="select-image" class="button positive" href="#">Select image</a>
		<input class="header_image" type="hidden" name="header_image" <?php if(isset($formdata)){ echo  'value="'.$formdata->header_image.'"'; } ?>
 />

	</p>


<?php if(isset($tags)): ?>
	<p>
		<label for="title">Select Article Tag</label>
		<?php echo form_dropdown('tag_id', $tags, $formdata->tag_id); ?>
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
    convert_urls: false,
    forced_root_block : false,
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    file_browser_callback: function(field_name, url, type, win) { 

        tinymce.activeEditor.windowManager.open({
            title: "Select an image",
            file: "<?php echo site_url('admin/media/select/images/insert'); ?>",
            width: 680,
            height: 680
        }, {
	        window 	: win,
	        input 	: field_name
	    });
    }
});

</script>
<script type="text/javascript">
	$(function() {
		$("#date_created").datepicker({ dateFormat: 'yy-mm-dd' });

		$('#select-image').on('click', function(e){
			e.preventDefault();
			window.open("<?php echo site_url('admin/media/select/images/select'); ?>", "_blank", "width=680, height=680");
		});
	});

	function updateImage(imageInfo)
	{
		$('.select-header-image').empty();
		image = '<img src="'+imageInfo.imageLink+'" >';
		$('.select-header-image').append(image);
		$('.header_image').val(imageInfo.imageId);
	}
</script>      	

<?php $this->load->view('admin/footer'); ?>