<html>
<head>
<title>Wiki: <?php echo htmlentities($title) ?></title>
<script type="text/javascript" src="<?php echo base_url() ?>js/simpletree/simpletreemenu.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>js/simpletree/simpletree.css" />

</head>
<body>
<h1><?php echo htmlentities($title) ?></h1>

<ul id="treemenu2" class="treeview">
	<?php display_tree($children); ?>
</ul>

<?php 

/*
function display_tree($array)
{
	foreach($array as $child)
	{
		echo "<li>\n";
		echo $child['lft'].' : '.$child['title'].' : '.$child['rgt'];
		if(isset($child['children']))
		{
			echo "<ul>\n";
				tree($child['children']);
			echo "</ul>\n";
		}
		echo "\t</li>\n";
	}
}
*/


?>

<script type="text/javascript">

//ddtreemenu.createTree(treeid, enablepersist, opt_persist_in_days (default is 1))
ddtreemenu.createTree("treemenu2", true, 5)

</script>
</body>
</html>