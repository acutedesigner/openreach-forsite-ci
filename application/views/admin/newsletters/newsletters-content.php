<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">

<h1><?php echo (isset($title) ? $title : NULL); ?></h1>

<?php if ($this->message->display('success')): echo '<p class="success">'.$this->message->display('success').'</p>'; endif; ?>

<!-- Display the stored data here -->

<h2>Newsletter articles</h2>
<?php echo anchor('admin/content/create/article/'.$articles_id, 'Create new article', 'class="button positive"'); ?>
<?php if(isset($articles)){ ?>
<ul id="pagetree">
<?php display_tree($articles, $parent_id); ?>
</ul>
<?php } ?>

<h2>Current Offers</h2>
<?php echo anchor('admin/content/create/offers/'.$current_offers_id, 'Create new offer', 'class="button positive"'); ?>
<?php if(isset($current_offers)){ ?>
<ul id="pagetree">
<?php display_tree($current_offers, $parent_id); ?>
</ul>
<?php } ?>
<?php echo (isset($articles) || isset($current_offers) ? '<style>.ui-state-highlight { height: 30px; border: 1px solid #999999; background-color: #EEEEEE; margin: 0px 0px 1px 0px; } </style>' : NULL ); ?>
<?php
	function display_tree($children, $parent_id)
	{
		$root = NULL;
		foreach($children as $child)
		{
			$no_nest = (isset($child['nested']) && $child['nested'] == 0 ? "no-nest" : false);
			$root = ($child['id'] == 1 ? "ignore" : false);
			$folder = (isset($child['children']) && $child['type'] != "blog" ? "ofolder" : "folder");
			$delete = anchor('admin/content/delete/'.$parent_id.'/'.$child['id'], '&nbsp;', 'class="delete"');

			echo "<li class=\"$root $no_nest\" id=\"".$child['id']."\">\n";
			echo "<div class=\"item\">".$delete."<img src='".base_url()."css/images/img-page-".$folder.".png' class='folder' />".anchor('admin/content/edit/'.$child['type'].'/'.$child['parent_id'].'/'.$child['id'], $child['title'])."</div>";
			if(isset($child['children']) && $child['type'] != "blog")
			{
				echo "<ul class=\"pagetree\">\n";
				display_tree($child['children']);
				echo "</ul>\n";
			}
			echo "\t</li>\n";
		}
	}
?>

</div>

<script type="text/javascript">
$('.status').click(function() {

	var url = $(this).attr('href');

	var form_data = {
		ajax: '1'	,
		current: '<?php echo uri_string(); ?>'
	};

	$.ajax({
		url: url,
		type: 'POST',
		data: form_data,
		success: function(msg) {
			//window.location = '<?php echo current_url(); ?>';
		}
	});

	return false;
});

var siteurl = '<?php echo current_url(); ?>';
var methodurl = '<?php echo site_url('/admin/newsletters/move_node');?>';
</script>

<script type="text/javascript">
$(document).ready(function(){

		$('.delete').jConfirmAction({question : "Remove! Are you sure?"});  	
});	
	
</script>

<?php $this->load->view('admin/footer'); ?>
