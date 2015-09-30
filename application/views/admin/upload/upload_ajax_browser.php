<?php $this->load->view('admin/browser-header'); ?>

<div class="span-17 last">

<h1>Upload files</h1>
	<div id="target"></div>

	<?php echo form_open_multipart('admin/uploadify/index');?>
    
    <p>
        <?php echo form_upload(array('name' => 'Filedata', 'id' => 'upload'));?>
	<a class="button green" href="javascript:$('#upload').uploadifyUpload();">Upload Files</a>
	<a class="button orange" href="javascript:$('#upload').uploadifyClearQueue();">Clear Queue</a>
    </p>
    
    
    <?php echo form_close();?>




</div>

	<script type="text/javascript" language="javascript">
		$(document).ready(function(){
										
					$("#upload").uploadify({
							'uploader': '<?php echo base_url();?>js/uploadify/uploadify.swf',
							'script': '<?php echo base_url();?>js/uploadify/uploadify.php',
							'cancelImg': '<?php echo base_url();?>js/uploadify/cancel.png',
							'folder': '/media',
							'scriptAccess': 'always',
							'multi': true,
							'onError'     : function (event,ID,fileObj,errorObj) {
      												alert(errorObj.type + ' Error: ' + errorObj.info + fileObj.name);
    											},
    						'onComplete'   : function (event, queueID, fileObj, response, data) {
												//Post response back to controller
												$.post('<?php echo site_url('admin/upload/uploadify');?>',{filearray: response},function(info){
													$("#target").append(info);  //Add response returned by controller																		  
												});								 			
							}
					});				
		});
	</script>

<?php $this->load->view('admin/footer'); ?>