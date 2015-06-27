      <!-- BEGIN PAGE -->  
      <div class="page-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
<?php
	$this->load->view("style_v.php"); 
?>
                  <h3 class="page-title">
                     <?php echo lang('create_group_heading');?>
                     <small><?php echo lang('create_group_subheading');?></small>
                  </h3>
				<?php echo $message;?>
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->


            <div class="row-fluid">
               <div class="span12">

<!-- BEGIN SAMPLE FORM PORTLET-->   
                  <div class="portlet box blue">
                     <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>Create Form</h4>
                        <div class="tools">
                        </div>
                     </div>
                     <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        
                        
<?php echo form_open("auth/create_group", 'class="form-horizontal"');?>

      <div class="control-group">
            <?php echo lang('create_group_name_label', 'group_name');?>
			<div class="controls">
            <?php echo form_input($group_name,'','class="span6 m-wrap"');?>
            </div>
      </div>

      <div class="control-group">
            <?php echo lang('create_group_desc_label', 'description');?>
            <div class="controls">
            <?php echo form_input($description,'','class="span6 m-wrap"');?>
            </div>
      </div>

      <div class="form-actions">
      	<?php echo form_submit('submit', lang('create_group_submit_btn'),' class="btn blue"');?>
      </div>

<?php echo form_close();?>


                        <!-- END FORM-->           
                     </div>
                  </div>
                  <!-- END SAMPLE FORM PORTLET-->
		</div>
	</div>
    <!-- END PAGE CONTENT--> 
	
	</div>
	<!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE --> 
