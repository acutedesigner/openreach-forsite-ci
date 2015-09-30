<div class="span-6 append-1 sidemenu">
	<ul id="menu" class="menu">
		<li>
			<?php echo anchor('admin/users/view/', 'Users');?>
			<ul>
				<li><?php echo anchor('admin/users/view/', 'View Users');?></li>
				<li><?php echo anchor('admin/users/create/', 'Add Users'); ?></li>
			</ul>
		</li>
		<li><?php echo anchor('admin/settings/view/website/', 'Website Settings');?></li>
		<li><?php echo anchor('admin/settings/view/feeds/', 'Feeds');?></li>
	</ul>
</div>