<script language="JavaScript" type="text/javascript" src="<?php echo base_url(); ?>static/jquery.js">
</script>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url(); ?>static/tree_admin/ext-jquery-adapter.js">
</script>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url(); ?>static/tree_admin/ext-all.js">
</script>

<STYLE TYPE="text/css">
@import url("<?php echo base_url(); ?>static/tree_admin/tree_css.css");
@import url("<?php echo base_url(); ?>static/tree_admin/menu_css.css");
* {
	margin: 0;
	padding: 0;
}
</STYLE>

<div id="tree-div"></div>

<script language="JavaScript" type="text/javascript">
function saveStatus(node){
	nodes = new Array();
	node.cascade(function(n){
		if(n.isExpanded()){
			nodes.push(n.attributes.db_id);
		}
		return 1;
	});
	return nodes;
}
function initStatus(node,nodes){
	if(nodes.length != 0){
		node.eachChild(function(n){
			var returnvar = 0;
			for (i=0; i < nodes.length; i++) {
				if (nodes[i] === n.attributes.db_id) {
					returnvar = 1;
					break;
				}
			}
			if(returnvar == 1){
				n.expand();
			}
		});
	}
}
Ext.onReady(function(){
	// shorthand
    var Tree = Ext.tree;
    var open_nodes = new Array();
    
    var tree = new Tree.TreePanel('tree-div', {
        animate:true, 
        loader: new Tree.TreeLoader({
            dataUrl:'<?php echo site_url(array($controller,'get_node'));?>'
        }),
        enableDD:true,
        containerScroll: true,
		lines: false
    });

    // set the root node
    var root = new Tree.AsyncTreeNode({
        text: '<?php echo $rootname;?>',
        draggable:false,
        id:'<?php echo $rootid;?>',
        db_id:'<?php echo $root_db_id; ?>'
    });
    tree.setRootNode(root);
	
	tree.on('load',
		function(n){
			n.expand();
			initStatus(n,open_nodes);
		});
	
	tree.on('beforenodedrop',
		function(e){
			dropEvent = e;

			action = e;
			// Dragged from inside the tree
			//alert('Move ' + dropEvent.data.node.id.replace( /_RRR_/g, '/' )+' to '+ dropEvent.target.id.replace( /_RRR_/g, '/' ));
			var requestParams = new Array();
			requestParams.new_dir = dropEvent.target.id.replace( /_RRR_/g, '/' );
			requestParams.new_dir = requestParams.new_dir.replace( /ext_root/g, '' );
			requestParams.selitems = Array( dropEvent.data.node.id.replace( /_RRR_/g, '/' ) );
			requestParams.point = dropEvent.point;
			requestParams.confirm = 'true';
			requestParams.action = action;
			open_nodes = {};
			Ext.Ajax.request({
				url : '<?php echo site_url(array($controller,'move_node'));?>' , 
				params : requestParams,
				method: 'POST',
				success: function ( result, request ) { 
					//alert('Success', 'Data return from the server: '+ result.responseText); 
					open_nodes = saveStatus(root);
					root.reload();
					//initStatus(root,a);
				},
				failure: function ( result, request) { 
					alert('Failed', 'Successfully posted form: '+action.date); 
				}
			});
		});
    tree.on('beforemove',
		function() {
			return false;
		});
	tree.on('contextmenu',
		function(node,e) {
		cmenu = new Ext.menu.Menu({
		items: [
			{	text: 'New Page',
				//observer;
				icon: '<?php echo base_url(); ?>static/tree_admin/treeimgs/drop-add.gif',
				menu: {
					items: [
						{	text: 'New Child Page',
							//observer;
							icon: '<?php echo base_url(); ?>static/tree_admin/treeimgs/drop-add.gif',
							handler: function(){
								var requestParams = new Array();
								requestParams.node = node.id;
								requestParams.type = 'child';
								requestParams.confirm = 'true';
								requestParams.action = e;
								open_nodes = {};
								Ext.Ajax.request({
									url : '<?php echo site_url(array($controller,'add_node'));?>' , 
									params : requestParams,
									method: 'POST',
									success: function ( result, request ) { 
										data = Ext.util.JSON.decode(result.responseText);
										if(data.success == 'false'){
											alert(data.errors.reason);
										}
										//alert('Success, Data return from the server: '+ result.responseText); 
										open_nodes = saveStatus(root);
										root.reload();
									},
									failure: function ( result, request) { 
										Ext.MessageBox.alert('Failed', 'Successfully posted form: '+action.date); 
									}
								});
							}
						},
						{	text: 'New Page Above',
							//observer;
							icon: '<?php echo base_url(); ?>static/tree_admin/treeimgs/drop-over.gif',
							handler: function(){
								var requestParams = new Array();
								requestParams.node = node.id;
								requestParams.type = 'above';
								requestParams.confirm = 'true';
								requestParams.action = e;
								open_nodes = {};
								Ext.Ajax.request({
									url : '<?php echo site_url(array($controller,'add_node'));?>' , 
									params : requestParams,
									method: 'POST',
									success: function ( result, request ) { 
										data = Ext.util.JSON.decode(result.responseText);
										if(data.success == 'false'){
											alert(data.errors.reason);
										}
										//alert('Success, Data return from the server: '+ result.responseText); 
										open_nodes = saveStatus(root);
										root.reload();
									},
									failure: function ( result, request) { 
										Ext.MessageBox.alert('Failed', 'Successfully posted form: '+action.date); 
									}
								});
							}
						},
						{	text: 'New Page Below',
							//observer;
							icon: '<?php echo base_url(); ?>static/tree_admin/treeimgs/drop-under.gif',
							handler: function(){
								var requestParams = new Array();
								requestParams.node = node.id;
								requestParams.type = 'below';
								requestParams.confirm = 'true';
								requestParams.action = e;
								open_nodes = {};
								Ext.Ajax.request({
									url : '<?php echo site_url(array($controller,'add_node'));?>' , 
									params : requestParams,
									method: 'POST',
									success: function ( result, request ) { 
										data = Ext.util.JSON.decode(result.responseText);
										if(data.success == 'false'){
											alert(data.errors.reason);
										}
										//alert('Success, Data return from the server: '+ result.responseText); 
										open_nodes = saveStatus(root);
										root.reload();
									},
									failure: function ( result, request) { 
										Ext.MessageBox.alert('Failed', 'Successfully posted form: '+action.date); 
									}
								});
							}
						}
					]
				}
			},
			{	text: 'Delete Page',
				//observer;
				icon: '<?php echo base_url(); ?>static/tree_admin/treeimgs/drop-no.gif',
				menu: {
					items: [
						{	text: 'Delete Promoting children',
							//observer;
							icon: '<?php echo base_url(); ?>static/tree_admin/treeimgs/drop-between.gif',
							handler: function(){
								var requestParams = new Array();
								requestParams.node = node.id;
								requestParams.type = 'promote';
								requestParams.confirm = 'true';
								requestParams.action = e;
								open_nodes = {};
								Ext.Ajax.request({
									url : '<?php echo site_url(array($controller,'del_node'));?>' , 
									params : requestParams,
									method: 'POST',
									success: function ( result, request ) {
										data = Ext.util.JSON.decode(result.responseText);
										if(data.success == 'false'){
											alert(data.errors.reason);
										}
										//alert('Success, Data return from the server: '+ result.responseText); 
										open_nodes = saveStatus(root);
										root.reload();
									},
									failure: function ( result, request) { 
										Ext.MessageBox.alert('Failed', 'Successfully posted form: '+action.date); 
									}
								});
							}
						},
						{	text: 'Delete whole branch',
							//observer;
							icon: '<?php echo base_url(); ?>static/tree_admin/treeimgs/drop-no.gif',
							handler: function(){
								var requestParams = new Array();
								requestParams.node = node.id;
								requestParams.type = 'del_all';
								requestParams.confirm = 'true';
								requestParams.action = e;
								open_nodes = {};
								Ext.Ajax.request({
									url : '<?php echo site_url(array($controller,'del_node'));?>' , 
									params : requestParams,
									method: 'POST',
									success: function ( result, request ) { 
										data = Ext.util.JSON.decode(result.responseText);
										if(data.success == 'false'){
											alert(data.errors.reason);
										}
										//alert('Success, Data return from the server: '+ result.responseText); 
										open_nodes = saveStatus(root);
										root.reload();
									},
									failure: function ( result, request) { 
										Ext.MessageBox.alert('Failed', 'Successfully posted form: '+action.date); 
									}
								});
							}
						}
					]
				},
				handler: function(){
					
				}
			}
		]
	})
			cmenu.showAt(new Array(e.getPageX(),e.getPageY()));
		}
	);
	
    // render the tree
    tree.render();
	Ext.QuickTips.init();
    root.expand();
});
</script>