      <!-- BEGIN PAGE -->  
      <div class="page-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                  <h3 class="page-title">
                     Create Admin
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
                        
<?php echo form_open("auth/create_user",'class="form-horizontal"');?>

      <div class="control-group">
            <?php // echo lang('create_user_fname_label', 'first_name');?>
            <label class="control-label" for="question_name">First Name:</label>
            <div class="controls">
            <?php echo form_input($first_name,'','class="span6 m-wrap"');?>
            </div>
      </div>

      <div class="control-group">
            <?php //echo lang('create_user_lname_label', 'first_name');?> 
            <label class="control-label" for="last_name">Last Name:</label>
			<div class="controls">
            <?php echo form_input($last_name,'','class="span6 m-wrap"');?>
            </div>
      </div>

      <div class="control-group">
            <?php //echo lang('create_user_email_label', 'email');?> 
            <label class="control-label" for="email">Email:</label>
			<div class="controls">
            <?php echo form_input($email,'','class="span6 m-wrap"');?>
            </div>
      </div>

      <div class="control-group">
            <?php //echo lang('create_user_phone_label', 'phone');?> 
            <label class="control-label" for="phone">Phone:</label>
            <div class="controls">
            <?php echo form_input($phone,'','class="span6 m-wrap"');?>
            </div>
      </div>
      
      <div class="control-group">
            <?php //echo lang('create_user_phone_label', 'phone');?> 
            <label class="control-label" for="chanel">Channel:</label>
            <div class="controls">
            <?php echo form_dropdown('chanel', $chanel, $chanel_value, 'class="large m-wrap"');?>
            </div>
      </div>
      
      <!--<div class="control-group">
            <?php echo lang('edit_user_company_label', 'company');?>
            <label class="control-label" for="company">Company Name:</label>
            <div class="controls">
            <?php echo form_dropdown("company",$companies,0, 'class="span6 m-wrap"');?>
            </div>
      </div>-->

      <div class="control-group">
            <?php //echo lang('create_user_password_label', 'password');?> 
            <label class="control-label" for="password">Password:</label>
			<div class="controls">
            <?php echo form_input($password,'','class="span6 m-wrap"');?>
            </div>
      </div>

      <div class="control-group">
            <?php //echo lang('create_user_password_confirm_label', 'password_confirm');?> 
            <label class="control-label" for="password_confirm">Confirm Password:</label>
			<div class="controls">
            <?php echo form_input($password_confirm,'','class="span6 m-wrap"');?>
            </div>
      </div>


      <div class="form-actions">
      	<?php echo form_submit('submit', lang('create_user_submit_btn'),' class="btn blue"');?>
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

