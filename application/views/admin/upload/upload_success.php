<?php $this->load->view('admin/header'); ?>

<?php echo anchor('admin/login/logout_user', 'Logout');?>

<hr />
	<ul>
		<li><?php echo anchor('admin/content', 'Content');?></li>
		<li><?php echo anchor('admin/media', 'Media');?></li>
		<li><?php echo anchor('admin/settings', 'Settings');?></li>
	</ul>

<hr />

<?php $this->load->view('admin/media/media-menu'); ?>

<div class="span-18 last">

<h1>Upload files</h1>

<h3>Your file was successfully uploaded!</h3>

<ul>
<?php foreach ($upload_data as $item => $value):?>
<li><?php echo $item;?>: <?php echo $value;?></li>
<?php endforeach; ?>
</ul>

<p><?php echo anchor('admin/upload', 'Upload Another File!'); ?></p>

</div>

<?php $this->load->view('admin/footer'); ?>