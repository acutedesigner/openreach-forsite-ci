<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">

<h1>Edit File</h1>

<?php if(isset($file)): ?>

<?php 	if(file_exists("./media/".$file->filename."_620x620".$file->ext)): ?>
<?php if ($this->message->display('success_crop')): echo '<p class="success">'.$this->message->display('success_crop').'</p>'; endif; ?>
<div id="crop-area">
		<!-- This is the image we're attaching Jcrop to -->
		<img src="<?php echo base_url(); ?>media/<?php echo $file->filename.$file->ext; ?>" id="cropbox"/>

		<!-- This is the form that our event handler fills -->
		<form action="<?php echo site_url(); ?>admin/media/image_crop" method="post" onsubmit="return checkCoords();">
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<input type="hidden" id="<?php echo $file->id; ?>" name="id" value="<?php echo $file->id; ?>" />
			<input type="hidden" id="<?php echo $file->filename.$file->ext; ?>" name="filename" value="<?php echo $file->filename.$file->ext; ?>" />									
			<button class="positive" type="submit">Crop Image</button>
		</form>

</div>

<div class="span-4 last">
		<p>Cropped image preview</p>
		<div style="width:202px;height:97px;overflow:hidden; margin:0px;">
			<img src="<?php echo base_url(); ?>media/<?php echo $file->filename.$file->ext; ?>" id="preview" />
		</div>
</div>
<?php elseif($file->filetype == "files"): ?>



<?php else: ?>

		<img src="<?php echo base_url(); ?>media/<?php echo $file->filename.$file->ext; ?>"/>
		<p class="info">Your image file was not big enough for cropping.<br/>For cropping please ensure your image is larger than 620px in Height & Width.</p>

<?php endif; ?>
<hr/>

<h2>Edit image caption</h2>
<p>Edit image captions here.</p>
<?php echo validation_errors('<p class="error" >'); ?>
<?php if ($this->message->display('success_caption')): echo '<p class="success">'.$this->message->display('success_caption').'</p>'; endif; ?>

	<form action="<?php echo site_url(); ?>admin/media/caption" method="POST">
			<input type="hidden" name="id" value="<?php echo $file->id; ?>" />
			<p><input class="text" type="text" name="caption" value="<?php echo $file->caption; ?>" /></p>
			<button class="positive" type="submit">Update caption</button>

	</form>


<?php

$orig_w = $file->width;
$orig_h = $file->height;

?>

<?php endif; ?>

</div>

		<script language="Javascript">

function changeImage(str){

	var par = window.parent.document;
	var num = par.getElementsByTagName('iframe').length - 1;
	var iframe = par.getElementsByTagName('iframe')[num];
	iframe.setAttribute("src", "<?php echo site_url(); ?>media/images-edit.php?cropimg="+str);

}

			$(function(){

				$('#cropbox').Jcrop({
					aspectRatio: 2.083,
					onChange: showPreview,
					//onSelect: showPreview,
					onSelect: updateCoords,
					boxWidth: 625, boxHeight: 300 // this line will scale all images inside the crop box
				});

			});

			function updateCoords(c)
			{
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#w').val(c.w);
				$('#h').val(c.h);
			};

			function checkCoords()
			{
				if (parseInt($('#x').val())) return true;
				alert('Please select a crop region then press submit.');
				return false;
			};

			function showPreview(coords)
			{
				var rx = 202 / coords.w;
				var ry = 97 / coords.h;

				jQuery('#preview').css({ // this data needs to be able to be dynamically updated
					width: Math.round(rx * <?php echo $orig_w; ?>) + 'px',
					height: Math.round(ry * <?php echo $orig_h; ?>) + 'px',
					marginLeft: '-' + Math.round(rx * coords.x) + 'px',
					marginTop: '-' + Math.round(ry * coords.y) + 'px'
				});
			}

		</script>

<?php $this->load->view('admin/footer'); ?>