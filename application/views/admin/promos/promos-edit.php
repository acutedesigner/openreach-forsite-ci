<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">
<h1><?php echo $title; ?></h1>

<!-- CONTENT FORM -->

<?php echo validation_errors('<p class="error" >'); ?>
<?php if ($this->message->display('success')): echo '<p class="success">'.$this->message->display('success').'</p>'; endif; ?>

<?php echo form_open('admin/promos/update/'); ?>

	<p>
		<label for="promo_title">Promotion title</label>
		<input type="text" class="title" name="promo_title" id="promo_title" value="<?php if(isset($formdata)){ echo $formdata->promo_title; } ?>" />
	</p>

	<p>
		<label for="promo_link">Promotion link</label>
		<input type="text" class="title" name="promo_link" id="promo_link" value="<?php if(isset($formdata)){ echo $formdata->promo_link; } ?>" />
	</p>

<?php if(isset($promo_offers)): ?>
<?php $promo = (isset($formdata) ? $formdata->promo : NULL); ?>
	<p>
		<label for="promo">Select Offer</label>
		<?php echo form_dropdown('promo', $promo_offers, $promo); ?>
	</p>
<?php endif; ?>

	<p>
		<?php if(isset($formdata) && $formdata->id != NULL){ echo form_hidden('id', $formdata->id); } ?>
		<button type="submit" class="positive">Save</button>
	</p>
<?php echo form_close(); ?>
</div>

<?php $this->load->view('admin/footer'); ?>