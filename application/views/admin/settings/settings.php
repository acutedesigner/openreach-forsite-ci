<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">

<h1>Manage Website Settings</h1>

<!-- Display the stored data here -->
<?php if(isset($members)): ?>
<?php echo form_open('admin/users/delete/'); ?>
<table>
	<thead>
		<th>&nbsp;</th>
		<th>Name</th>
		<th>Username</th>
		<th>Email</th>
		<th>Active</th>
	</thead>
	<tbody>

<?php foreach( $members as $member ): ?>
<?php $active = ($member->active == 1 ? "Active" : "Not active"); ?>
		<tr>
			<td align="center" bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $member->userid; ?>" /></td>
			<td><?php echo anchor('admin/users/edit/'.$member->userid.'/', $member->firstname.' '.$member->lastname); ?></td>
			<td><p><?php echo $member->username; ?></p></td>
			<td><p><?php echo $member->email; ?></p></td>
			<td><p><?php echo $active; ?></p></td>
		</tr>
	
<?php endforeach; ?>
</table>
	<button type="submit" class="positive">Delete</button>
<?php echo form_close(); ?>

<?php else: ?>
<p class="info">There is no data for this section</p>
	</tbody>
<?php endif; ?>

</div>

<?php $this->load->view('admin/footer'); ?>