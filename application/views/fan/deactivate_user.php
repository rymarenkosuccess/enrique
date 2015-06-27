
      <!-- BEGIN PAGE -->  
      <div class="page-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                  <h3 class="page-title">
                     Block Fan
                  </h3>
                  <ul class="breadcrumb">
                    <li><a href="<?php echo site_url("fan/active"); ?>">Active Fans</a></li>
                  </ul>
               </div>
            </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->


            <div class="row-fluid">
               <div class="span12">

<!-- BEGIN SAMPLE FORM PORTLET-->   
                  <div class="portlet box blue">
                     <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>Block Form</h4>
                        <div class="tools">
                        </div>
                     </div>
                     <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        
	                    <?php echo form_open("fan/deactivate/".$user->id);?>
			                    

		                    <div class="alert fade in">

			                    <h4 class="alert-heading">Warning!</h4>
			                    <p>
                                     
				                    <?php echo "Are you sure you want to block the fan '{$user->username}'"; ?>
			                    </p>
			                    
			                    <div class="controls">
			                    
				                    <label class="radio">
			                        <input type="radio" name="confirm" value="yes" checked="checked" />
			                        <?php echo lang('deactivate_confirm_y_label');?>
			                        </label>
			                        
			                        <label class="radio">
			                        <input type="radio" name="confirm" value="no" />
			                        <?php echo lang('deactivate_confirm_n_label');?>
			                        </label>
		                        
			                      <?php echo form_hidden($csrf); ?>
			                      <?php echo form_hidden(array('id'=>$user->id)); ?>
			                      
		                        </div>
		                        
		                        <br /><br />
			                    <?php echo form_submit('submit', lang('deactivate_submit_btn'), ' class="btn blue"');?>
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

