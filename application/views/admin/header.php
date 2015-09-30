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
<link rel="stylesheet" href="<?php echo base_url(); ?>css/editor_styles.css" type="text/css" media="screen, projection" /> 
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/fine-uploader/fine-uploader.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.min.css">

<?php echo $javascript; ?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/jConfirmAction/jconfirm.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/qtip/jquery.qtip.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/colorbox/colorbox.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/jcrop/jquery.Jcrop.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/jq-ui/jquery.ui.all.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/redactor/redactor.css" />

</head>

<body>

<div id="header">
	<div class="container">
		<p>Welcome: <?=$this->session->userdata('firstname'); ?> | <?php echo anchor('admin/login/logout_user', 'Logout');?></p>
	</div>
</div><!-- END OF HEADER -->

<div id="top-menu">
	<div class="container">
	</div>
</div><!-- END OF MENU -->

<div id="container" class="container">