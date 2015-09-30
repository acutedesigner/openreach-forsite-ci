<div class="span-6 append-1 sidemenu">

	<ul id="menu" class="menu">
		<li><?php echo anchor('admin/dashboard/', 'Home');?></li>
		<li><h3 class="content">Website Content</h3></li>
		<li>
			<div class="menu-toggle">
				<img class="drop" src="<?php echo base_url();?>css/images/img-dropdown.png" />
			</div>			
			<?php echo anchor('admin/archive/', 'Archives');?>
			<ul <?php if(isset($blog)): echo $blog; endif;?>>
				<li><?php echo anchor('admin/archive/', 'View Archives'); ?></li>
				<li><?php echo anchor('admin/archive/create/', 'Create new Archive'); ?></li>
			</ul>
		</li>		
		<li>
			<div class="menu-toggle">
				<img class="drop" src="<?php echo base_url();?>css/images/img-dropdown.png" />
			</div>			
			<?php echo anchor('admin/content/tree/page/', 'Main Pages');?>
			<ul <?php if(isset($page)): echo $page; endif;?>>
				<li><?php echo anchor('admin/content/tree/page/', 'View website Pages'); ?></li>
				<li><?php echo anchor('admin/content/create/page/', 'Create new Page'); ?></li>
			</ul>
		</li>		
		<li>
			<div class="menu-toggle">
				<img class="drop" src="<?php echo base_url();?>css/images/img-dropdown.png" />
			</div>			
			<?php echo anchor('admin/tags/', 'Tags');?>
			<ul <?php if(isset($blog)): echo $blog; endif;?>>
				<li><?php echo anchor('admin/tags/', 'View Tags'); ?></li>
				<li><?php echo anchor('admin/tags/create/', 'Create new Tag'); ?></li>
			</ul>
		</li>		
		<li>
			<div class="menu-toggle">
				<img class="drop" src="<?php echo base_url();?>css/images/img-dropdown.png" />
			</div>			
			<?php echo anchor('admin/promos/', 'Promos');?>
			<ul>
				<li><?php echo anchor('admin/promos/', 'View Promo Offers'); ?></li>
				<li><?php echo anchor('admin/promos/create/', 'Setup new Promos'); ?></li>
			</ul>
		</li>		
		
		<li><h3 class="media">Media</h3></li>
		
		<li>
			<?php echo anchor('admin/media/view/images', 'Images');?>
		</li>
		<li>
			<div class="menu-toggle">
				<img class="drop" src="<?php echo base_url();?>css/images/img-dropdown.png" />
			</div>			
			<?php echo anchor('admin/gallery', 'Image Galleries');?>
			<ul <?php if(isset($gallery)): echo $gallery; endif;?>>
				<li><?php echo anchor('admin/gallery/create/', 'Create new gallery'); ?></li>
			</ul>
		</li>
		<li>
			<?php echo anchor('admin/media/view/files', 'Files');?>
		</li>
		<li>
			<?php echo anchor('admin/upload/upload', 'Upload Files');?>
		</li>

		<li><h3 class="settings">Settings</h3></li>

		<li>
			<div class="menu-toggle">
				<img class="drop" src="<?php echo base_url();?>css/images/img-dropdown.png" />
			</div>			
			<?php echo anchor('admin/users/view/', 'Users');?>
			<ul>
				<li><?php echo anchor('admin/users/view/', 'View Users');?></li>
				<li><?php echo anchor('admin/users/create/', 'Add Users'); ?></li>
			</ul>
		</li>
		<!-- <li><?php echo anchor('admin/settings/view/website/', 'Website Settings');?></li> -->
<!-- 		<li><?php echo anchor('admin/settings/view/feeds/', 'Feeds');?></li> -->
		
	</ul>

</div>
