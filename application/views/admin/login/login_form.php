<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<title>CMS Login</title>

<meta name="description" content="Website Info" />
<meta name="keywords" content="Website Info" />

<link rel="stylesheet" href="<?php echo base_url(); ?>css/screen.css" type="text/css" media="screen, projection" /> 
<link rel="stylesheet" href="<?php echo base_url(); ?>css/admin_styles.css" type="text/css" media="screen, projection" /> 

</head>

<body>
<div id="container" class="container">
<div class="span-24 login-form-spacer">
<div class="span-4 last">&nbsp;</div>
<div class="span-17 last">
<?php if(isset($title)): echo '<h2>'.$title.'</h2>'; endif; ?>
<?php if(isset($failed)): echo '<p class="error">'.$failed.'</p>'; endif; ?></p>
<?php if(isset($success)): echo '<p class="success">'.$success.'</p>'; endif; ?></p>
<?php if(isset($email)): echo '<p class="info">'.$email.'</p>'; endif; ?></p>

	<?php
	echo form_open($login_link). "\n";
	 ?>

<div class="span-8 append-1">
	<p>
		<label for="username">Username</label>
		<input type="text" class="title" name="username" id="username" />
	</p>
		<?php echo form_error('username', '<p class="error">', '</p>'); ?>
	<p>Reset your password?	<?php echo anchor('admin/login/reset_password', 'Click here'); ?></p>
</div>
<div class="span-8 last">
	<p>
		<label for="password">Password</label>
		<input type="password" class="title" name="password" id="password" />
	</p>	
		<?php echo form_error('password', '<p class="error">', '</p>'); ?>

	<button type="submit" name="submit" value="submit" class="positive">Sign in</button>
	
	<? echo form_close(). "\n\n"; ?>
</div>		
</div>
</div>

</div>
	</body>
</html>