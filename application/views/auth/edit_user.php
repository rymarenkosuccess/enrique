      <!-- BEGIN PAGE -->  
      <div class="page-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                  <h3 class="page-title">
                    Edit Admin
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

<?php echo form_open(uri_string(),'class="form-horizontal"');?>

      <div class="control-group" style="display: none;">
            <?php echo lang('edit_user_fname_label', 'first_name');?>
			<div class="controls">
            <?php echo form_input($first_name,'','class="span6 m-wrap"');?>
            </div>
      </div>

      <div class="control-group" style="display: none;">
            <?php echo lang('edit_user_lname_label', 'last_name');?>
			<div class="controls">
            <?php echo form_input($last_name,'','class="span6 m-wrap"');?>
            </div>
      </div>

      <div class="control-group">
            <?php echo lang('create_user_email_label', 'email');?> 
			<div class="controls">
            <?php echo form_input($email,'','class="span6 m-wrap"');?>
            </div>
      </div>
      
      <div class="control-group">
            <?php echo lang('edit_user_phone_label', 'phone');?>
            <div class="controls">
            <?php echo form_input($phone,'','class="span6 m-wrap"');?>
            </div>
      </div>
      
      <div class="control-group" style="display: none;">
            <?php //echo lang('create_user_phone_label', 'phone');?> 
            <label class="control-label" for="chanel">Channel:</label>
            <div class="controls">
            <?php echo form_dropdown('chanel', $chanel, $chanel_value, 'class="large m-wrap"');?>
            </div>
      </div>
      
      <!--<div class="control-group">
            <?php echo lang('edit_user_company_label', 'company');?>
            <div class="controls">
            <?php echo form_dropdown("company",$companies,$user->company, 'class="span6 m-wrap"');?>
            </div>
      </div>-->

      <div class="control-group">
            <?php echo lang('edit_user_password_label', 'password');?>
            <div class="controls">
            <?php echo form_input($password,'','class="span6 m-wrap"');?>
            <span class="help-inline"> (if changing password) </span>
            </div>
      </div>

      <div class="control-group">
            <?php echo lang('edit_user_password_confirm_label', 'password_confirm');?>
            <div class="controls">
            <?php echo form_input($password_confirm,'','class="span6 m-wrap"');?>
            <span class="help-inline"> (if changing password) </span>
            </div>
      </div>
      
	<div class="control-group" style="display: none;">
		 <h3><?php echo lang('edit_user_groups_heading');?></h3>
		 <div class="controls">
		<?php foreach ($groups as $group):?>
		<label class="checkbox line" >
		<?php
			$gID=$group['id'];
			$checked = null;
			$item = null;
			foreach($currentGroups as $grp) {
				if ($gID == $grp->id) {
					$checked= ' checked="checked"';
				break;
				}
			}
		?>
		<input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
		<?php echo $group['name'];?>
		</label>
		<?php endforeach?>
		</div>
	</div>
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

