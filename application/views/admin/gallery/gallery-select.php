<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<title>Select Gallery</title>

<link rel="stylesheet" href="<?php echo base_url(); ?>css/screen.css" type="text/css" media="screen, projection" /> 
<!--[if lt IE 7]>
<link href="<?php echo base_url(); ?>css/ie6.css" type="text/css" rel="stylesheet" media="screen,projection" />
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/admin_styles.css" type="text/css" media="screen, projection" /> 

</head>

<body>

<div class="span-17 last">
<h1>Select a Gallery</h1>

<!-- CONTENT FORM -->
<div id="target"></div>

<?php if(isset($image)): ?>
<?php echo form_open('admin/gallery/add/'); ?>
	<p>
		<select name="gallery_id">
<?php foreach($galleries as $gallery) {
echo "<option value=\"".$gallery->id."\">".$gallery->name."</option>"; 
} ?>
		</select>
	</p>
<p>
		<?php echo form_hidden('image_id', $image);?>
		<button type="submit" class="positive" id="formsubmit">Save</button>
</p>

<?php echo form_close(); ?>

<?php endif; ?>
<script type="text/javascript">
$(document).ready(function(){
	
	//Ajax form submit
	$("#formsubmit").click(function(event){

		$.post('<?php echo site_url('admin/gallery/add/');?>',$("form").serialize(),function(info){
			$("#target").empty();
			$("#target").append(info);  //Add response returned by controller																		  
		});								 			

		return false;
	});

});	
</script>


<?php $this->load->view('admin/footer'); ?>