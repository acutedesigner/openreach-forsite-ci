<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">

<h1>Test Page</h1>
	
<ul id="pagetree">
<?php display_tree($children); ?>
</ul>
<style>
	.ui-state-highlight { height: 30px; border: 1px solid #999999; background-color: #EEEEEE; margin: 0px 0px 1px 0px; }
	</style>
	
<?php 

function display_tree($array)
{
	$root = NULL;
	foreach($array as $child)
	{
		if($child['lft'] == '1'){ $root = 'class="ignore"'; }
		echo "<li $root id=\"".$child['lft']."\">\n";
		echo "<div class=\"item\">".$child['lft'].' : '.$child['title'].' : '.$child['rgt'].anchor('delete', 'Delete')." ".anchor('edit', 'Edit')." ".anchor('move', 'Move', 'class="drop"')."</div>";
		if(isset($child['children']))
		{
			echo "<ul class=\"pagetree\">\n";
				tree($child['children']);
			echo "</ul>\n";
		}
		echo "\t</li>\n";
	}
}

?>
<?php $this->load->view('admin/footer'); ?>
