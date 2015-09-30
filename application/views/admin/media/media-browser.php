<?php $this->load->view('admin/browser-header'); ?>

<div class="span-17 last">

<h1>Media Browser</h1>
<?php if ($this->message->display('success')): echo '<p class="success">'.$this->message->display('success').'</p>'; endif; ?>

<!-- Display the stored data here -->
<?php if(isset($images)): foreach( $images as $image ): ?>
	
<div class="image-holder">
	<div class="media-image">
		<a href="<?php echo site_url()."/admin/media/selectimage/".$image->id; ?>/?CKEditor=ckeditor&CKEditorFuncNum=2&langCode=en">
			<img src="<?php echo base_url().'media/'.$image->filename.'_150x150' ?>" alt="<?php echo $image->caption; ?>" />
		</a>
	</div>
</div>
	
<?php endforeach; ?>
<?php echo $this->pagination->create_links(); ?>

<?php elseif(isset($files)): ?>
<?php echo form_open('admin/media/delete/'.$this->uri->segment(4)); ?>
<table>
	<thead>
		<th width="10px">&nbsp;</th>
		<th>Title</th>
		<th>File Uploaded</th>
		<th width="10px">&nbsp;</th>
	</thead>
	<tbody>

<?php foreach( $files as $file ): ?>

		<tr>
			<td align="center" bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $file->id; ?>" /></td>
			<td><p><a href="#" OnClick="OpenFile('<?php echo base_url()."media/".$file->filename.$file->ext; ?>');return false;" ><?php echo $file->display_name; ?></a></p></td>
			<td><p><?php echo date("Y/m/d", strtotime($file->created)); ?></p></td>
			<td>&nbsp;</td>
		</tr>
	
<?php endforeach; ?>
</table>
	<button type="submit" class="positive">Delete</button>
<?php echo form_close(); ?>
<?php echo $this->pagination->create_links(); ?>

<?php else: ?>
<p class="info">There is no data for this section</p>

<?php endif; ?>

</div>

<script type="text/javascript">
function OpenFile( fileUrl )
{

	//PATCH: Using CKEditors API we set the file in preview window.	
	
	funcNum = GetUrlParam('CKEditorFuncNum') ;	
	
	//fixed the issue: images are not displayed in preview window when filename contain spaces due encodeURI encoding already encoded fileUrl	
	window.top.opener.CKEDITOR.tools.callFunction( funcNum, fileUrl);
	
	///////////////////////////////////
	window.top.close() ;
	window.top.opener.focus() ;
}

function GetUrlParam( paramName )
{
	var oRegex = new RegExp( '[\?&]' + paramName + '=([^&]+)', 'i' ) ;
	var oMatch = oRegex.exec( window.top.location.search ) ;

	if ( oMatch && oMatch.length > 1 )
		return decodeURIComponent( oMatch[1] ) ;
	else
		return '' ;
}

</script>

<?php $this->load->view('admin/footer'); ?>