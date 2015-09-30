<div class="span-6 append-1 sidemenu">

<ul id="menu" class="menu">
	<li class="menu"> <!-- This LI is positioned inside the main UL -->
		<ul> <!-- This UL holds the section title and content  -->
		<!-- The click-able section title with a unique background: -->
			<li class="button"><a href="#" class="green">Kiwis <span></span></a></li>

			<!-- The dropdown list, hidden by default and shown on title click: -->
			<li class="dropdown">

			<ul> <!-- This list holds the options that are slid down by jQuery -->

				<!-- Each option is in its own LI -->
				<li><a href="http://en.wikipedia.org/wiki/Kiwifruit">Read on Wikipedia</a></li>
				<li><a href="http://www.flickr.com/search/?w=all&amp;q=kiwi&amp;m=text">Flickr Stream</a></li>

			</ul> <!-- Closing the elements -->

		</li>
</ul>

</li>

</ul>

	<ul id="menu" class="menu">
		<li>
			<?php echo anchor('admin/content/view/page/', 'Main Pages', 'class="open"');?>
			<ul>
				<li><?php echo anchor('admin/content/view/page/', 'View website Pages'); ?></li>
				<li><?php echo anchor('admin/content/create/page/', 'Create new Page'); ?></li>
			</ul>
		</li>		
		<li>
			<?php echo anchor('admin/content/view/case_studies/', 'Case Studies');?>
			<ul id="pages">
				<li><?php echo anchor('admin/content/view/case_studies/', 'View Case Studies'); ?></li>
				<li><?php echo anchor('admin/content/create/case_studies/', 'Create new Case Study'); ?></li>
			</ul>
		</li>		
		<li>
			<?php echo anchor('admin/content/view/blog/', 'Blog/News');?>
			<ul id="pages">
				<li><?php echo anchor('admin/content/view/blog/', 'View Blog/News'); ?></li>
				<li><?php echo anchor('admin/content/create/blog/', 'Create new Blog/News'); ?></li>
			</ul>
		</li>		
		<li>
			<?php echo anchor('admin/content/view/events/', 'Events');?>
			<ul id="pages">
				<li><?php echo anchor('admin/content/view/events/', 'Events'); ?></li>
				<li><?php echo anchor('admin/content/create/events/', 'Create new Events'); ?></li>
			</ul>
		</li>		
	</ul>
</div>
