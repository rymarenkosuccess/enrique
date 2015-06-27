
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
                     <?php echo lang('reset_password_heading');?>
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
                        <h4><i class="icon-reorder"></i>Edit Form</h4>
                        <div class="tools">
                        </div>
                     </div>
                     <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        

<?php echo form_open('auth/reset_password/' . $code,'class="form-horizontal"');?>

	<div class="control-group">
		<label for="new_password" class="control-label"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
		<div class="controls">
		<?php echo form_input($new_password);?>
		</div>
	</div>

	<div class="control-group">
		<?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?>
		<div class="controls">
		<?php echo form_input($new_password_confirm);?>
		</div>
	</div>

	<?php echo form_input($user_id);?>
	<?php echo form_hidden($csrf); ?>

	<div class="form-actions">
		<?php echo form_submit('submit', lang('reset_password_submit_btn'),' class="btn blue"');?>
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
