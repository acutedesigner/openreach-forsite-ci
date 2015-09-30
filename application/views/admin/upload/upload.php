<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">

<h1>Upload files</h1>

<?php echo $error;?>

<?php echo form_open_multipart('admin/upload/do_upload');?>

<input type="file" name="userfile" size="20" />

<br /><br />

<input type="submit" value="upload" />

</form>

</div>

<?php $this->load->view('admin/footer'); ?>