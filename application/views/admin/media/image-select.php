<?php $this->load->view('admin/browser-header'); ?>

<div class="span-17 last">

<h1>Select Image</h1>

<div class="image-select">
	<img src="<?php echo base_url().$file ?>" />
</div>
<div class="image-select-button">
	<h2>Select image size:</h2>
<?php foreach( $filelist as $size => $link ): ?>

	<a href="#" OnClick="OpenFile('<?php echo base_url().$link; ?>');return false;" ><?=$size ?></a>
	
<?php endforeach; ?>
</div>

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