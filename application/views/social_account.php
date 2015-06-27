<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title">
                    <?php
                        switch($post['social_type']){
                            case 'twitter':
                                $type_name = "Twitter";
                            break;
                            case 'google':
                                $type_name = "Google Plus";
                            break;
                            case 'facebook':
                                $type_name = "Facebook";
                            break;
                        }
                        echo $type_name;
                    ?>
                    
                    <small></small>
                </div>
                                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div> 
        <div class="row-fluid">
           <div class="span12">

<!-- BEGIN SAMPLE FORM PORTLET-->   
              <div class="portlet box blue">
                 <div class="portlet-title">
                    <h4><i class="icon-reorder"></i>Social Account Form</h4>
                    <div class="tools">
                    </div>
                 </div>
                 <div class="portlet-body form">
                    <?php 
                    echo form_open_multipart("social_account/index/".$post['social_type'], 'class="form-horizontal"');
                        echo form_error("username");
                        if(isset($show_errors)) {
                            if (is_array($show_errors)) {
                                foreach($show_errors as $error) {
                                    echo "<div class='alert alert-error'>".$error."</div>";
                                }
                            }
                            else{
                                echo "<div class='alert alert-error'>".$show_errors."</div>";
                            }
                        }
                    ?>
                    <div class="row-fluid">
                        <div class="control-group">
                            <label class="control-label" for="url">Username</label>
                            <div class="controls customarea">
                            <?php  echo form_input("username",$post['username'],'class="large m-wrap"');?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group">
                            <label class="control-label" for="url">Password</label>
                            <div class="controls customarea">
                            <?php  echo form_input(array("name"=>"password", 'type'=>'password'),'','class="large m-wrap"');?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group">
                            <label class="control-label" for="url">Confirm Password</label>
                            <div class="controls customarea">
                            <?php  echo form_input(array("name"=>"confirm_password", 'type'=>'password'),'','class="large m-wrap"');?>
                            </div>
                        </div>
                    </div>
                        <div class="form-actions">
                            <?php echo form_submit('submit', "Post",' class="btn blue"');?>
                        </div>
                    <?php echo form_close();?>
                    <!-- END FORM-->
                 </div>
              </div>
              <!-- END SAMPLE FORM PORTLET-->
              </div>
        </div>
    </div>
</div>
