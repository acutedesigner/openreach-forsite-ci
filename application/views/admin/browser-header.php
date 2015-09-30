<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<title>CMS Login</title>

<meta name="description" content="Website Info" />
<meta name="keywords" content="Website Info" />

<link rel="stylesheet" href="<?php echo base_url(); ?>css/screen.css" type="text/css" media="screen, projection" /> 
<!--[if lt IE 7]>
<link href="<?php echo base_url(); ?>css/ie6.css" type="text/css" rel="stylesheet" media="screen,projection" />
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/admin_styles.css" type="text/css" media="screen, projection" /> 
<?php echo $javascript; ?>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui-1.8.2.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/jq-ui/jquery.ui.all.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/uploadify/uploadify.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/ckeditor/skins/kama/editor.css" />

</head>

<body>

<div id="top-menu">
	<div class="browser-container">
	<ul>
		<li><a class="media" href="<?php echo site_url('admin/media/browse/images');?>">Images</a></li>
		<li><a class="content" href="<?php echo site_url('admin/media/browse/files');?>">Files</a></li>
		<li><a class="files" href="<?php echo site_url('admin/media/upload');?>">Upload New</a></li>
	</ul>
	</div>
</div><!-- END OF MENU -->

<div id="container" class="browser-container">