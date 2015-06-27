      <!-- BEGIN PAGE -->  
      <div class="page-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                  <h3 class="page-title">
                     Edit Fan
                  </h3>
                  <ul class="breadcrumb">
                    <li><a href="<?php echo site_url("fan/active"); ?>">Active Fans</a></li>
                  </ul>
				<?php echo $message;?>
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
               <div class="span12">
<!-- BEGIN SAMPLE FORM PORTLET-->   
                  <div class="portlet box yellow">
                     <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>Edit Form</h4>
                        <div class="tools">
                        </div>
                     </div>
                     <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <?php echo form_open(uri_string(),'class="form-horizontal"');?>
                              <div class="control-group">
                                  <label class="control-label" for="first_name">Firstname:</label>
                                    <div class="controls">
                                    <?php echo form_input($first_name,'','class="span6 m-wrap"');?>
                                    </div>
                              </div>
                              <div class="control-group">
                                  <label class="control-label" for="last_name">Lastname:</label>
                                    <div class="controls">
                                    <?php echo form_input($last_name,'','class="span6 m-wrap"');?>
                                    </div>
                              </div>
                              <div class="control-group">
                                  <label class="control-label" for="email">Email:</label>
			                        <div class="controls">
                                    <?php echo form_input($email,'','class="span6 m-wrap"');?>
                                    </div>
                              </div>
                              <div class="control-group">
                                  <label class="control-label" for="Phone">Phone:</label>
                                    <div class="controls">
                                    <?php echo form_input($phone,'','class="span6 m-wrap"');?>
                                    </div>
                              </div>
                              <!--<div class="control-group">
                                    <?php echo lang('edit_user_company_label', 'company');?>
                                    <div class="controls">
                                    <?php echo form_dropdown("company",$companies,$user->company, 'class="span6 m-wrap"');?>
                                    </div>
                              </div>-->
                              <?php echo form_hidden('id', $user->id);?>
                              <?php echo form_hidden($csrf); ?>

                              <div class="form-actions">
      	                        <?php echo form_submit('submit', lang('edit_user_submit_btn'),' class="btn blue"');?>
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

