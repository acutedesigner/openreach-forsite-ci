<?php $this->load->view('admin/header'); ?>

<?php $this->load->view('admin/side-menu'); ?>

<div class="span-17 last">

<h1><?php echo $title; ?></h1>

<?php if ($this->message->display('success')): echo '<p class="success">'.$this->message->display('success').'</p>'; endif; ?>

<!-- Display the stored data here -->
<?php if(isset($pages)): ?>
<?php echo form_open('admin/content/delete/'.$this->uri->segment(4)); ?>
<table>
	<thead>
		<th width="10px">&nbsp;</th>
		<th>Title</th>
		<th>Author</th>
		<th width="150px">Date Created</th>
		<th width="10px">&nbsp;</th>
		<th width="10px">&nbsp;</th>
	</thead>
	<tbody>

<?php foreach( $pages as $page ): ?>
	<?php if($page->locked == 0): ?>
		<tr>
			<td align="center" bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $page->lft; ?>" /></td>
			<td><p><?php echo anchor('admin/content/edit/'.$page->type.'/'.$page->id, $page->title); ?></p></td>
			<td><p><?php echo $page->firstname." ".$page->lastname; ?></p></td>
			<td><p><?php echo date("Y/m/d", strtotime($page->date_created)); ?></p></td>
			<td><p><?php if($page->status == '1'){ $status = 'tick'; } else { $status = 'cross'; } ?><a class="status" href="<?php echo site_url(); ?>/admin/content/post_status_update/<?php echo $page->status.'/'.$page->id; ?>"><img class="drop" src="<?php echo base_url();?>css/images/img-<?php echo $status?>.png" /></a></p></td>
			<td>&nbsp;</td>
		</tr>
	<?php endif; ?>
<?php endforeach; ?>
	</tbody>
</table>
	<button type="submit" class="positive">Delete</button>
<?php echo form_close(); ?>
<?php echo $this->pagination->create_links(); ?>
<?php else: ?>
<?php if(isset($info)){ echo '<p class="info">'.$info.'</p>'; } ?>
<?php if(isset($error)){ echo '<p class="error">'.$error.'</p>'; } ?>
<?php endif; ?>

<?php if(isset($children)){ ?>
<ul id="pagetree">
<?php display_tree($children); ?>
</ul>
<style>
	.ui-state-highlight { height: 30px; border: 1px solid #999999; background-color: #EEEEEE; margin: 0px 0px 1px 0px; }
	</style>


<?php } ?>

<?php
	function display_tree($children)
	{
		$root = NULL;
		foreach($children as $child)
		{
			$no_nest = ($child['nested'] == 0 ? "no-nest" : false);
			$root = ($child['lft'] == 1 ? "ignore" : false);
			$folder = (isset($child['children']) && $child['type'] != "blog" ? "ofolder" : "folder");
			$delete = ($child['locked'] == 0 ? anchor('admin/content/delete/page/'.$child['lft'], '&nbsp;', 'class="delete"') : NULL );

			echo "<li class=\"$root $no_nest\" id=\"".$child['lft']."\">\n";
			echo "<div class=\"item\">".$delete."<img src='".base_url()."css/images/img-page-".$folder.".png' class='folder' />".anchor('admin/content/edit/'.$child['type'].'/'.$child['id'], $child['title'])."</div>";
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
var methodurl = '<?php echo site_url('/admin/content/move_node');?>';
</script>

<script type="text/javascript">
$(document).ready(function(){

		$('.delete').jConfirmAction({question : "Remove! Are you sure?"});  	
});	
	
</script>

<?php $this->load->view('admin/footer'); ?>
