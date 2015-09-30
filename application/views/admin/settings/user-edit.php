<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">
<h1><?php echo $title; ?></h1>

<!-- CONTENT FORM -->

<?php echo validation_errors('<p class="error" >'); ?>
<?php if ($this->message->display('success')): echo '<p class="success">'.$this->message->display('success').'</p>'; endif; ?>

<?php echo form_open('admin/users/save/'); ?>
	<p>
		<label for="firstname">First name</label>
		<input type="text" class="title" name="firstname" id="firstname" value="<?php if(isset($formdata->firstname)){ echo $formdata->firstname; } ?>" />
	</p>
	<p>
		<label for="lastname">Last name</label>
		<input type="text" class="title" name="lastname" id="lastname" value="<?php if(isset($formdata->lastname)){ echo $formdata->lastname; } ?>" />
	</p>
	<p>
		<label for="username">User name</label>
		<input type="text" class="title" name="username" id="username" value="<?php if(isset($formdata->username)){ echo $formdata->username; } ?>" <?php if(isset($formdata->userid)){ echo "readonly=\"true\""; } ?> />
	</p>
	<p>
		<label for="email">Email</label>
		<input type="text" class="title" name="email" id="email" value="<?php if(isset($formdata->email)){ echo $formdata->email; } ?>" />
	</p>
	<p>
		<label for="password">Password</label>
		<input type="password" class="title" name="password" id="password" />
	</p>
	<p>
		<label for="password2">Password Confirm</label>
		<input type="password" class="title" name="password2" id="password2" />
	</p>
	<p>
		<label for="title">Status</label>
		<?php $active = NULL; $nonactive = NULL; if(isset($formdata->active) && $formdata->active == 1){ $active = TRUE; } else { $nonactive = TRUE; } ?>
		<?php echo form_radio('active', '1', $active); ?>
		Active
		<?php echo form_radio('active', '0', $nonactive); ?>
		Deactivated
	</p>
	<p>
		<?php if(isset($formdata->userid)){ echo form_hidden('userid', $formdata->userid); } ?>

		<button type="submit" class="positive">Save</button>
	</p>
<?php echo form_close(); ?>
</div>

<?php $this->load->view('admin/footer'); ?>