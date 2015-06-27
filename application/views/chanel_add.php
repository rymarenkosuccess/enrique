  <!-- BEGIN PAGE -->  
<div class="page-content">

     <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->   
        <div class="row-fluid">
           <div class="span12">
              <h3 class="page-title">
                 Add Channel
              </h3>
              <!--<ul class="breadcrumb">
                <li><a href="<?php echo site_url("question/question_list"); ?>">Question</a></li>
                <li>&raquo;</li>
                <li><a href="<?php echo site_url("question/question_edit/".$question[0]->qid); ?>">Edit Question</a></li>
              </ul>-->
           </div>
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->

        <div class="row-fluid">
           <div class="span12">
<!-- BEGIN SAMPLE FORM PORTLET-->   
              <div class="portlet box blue">
                 <div class="portlet-title">
                    <h4><i class="icon-reorder"></i>Add Form</h4>
                    <div class="tools">
                    </div>
                 </div>
                 <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <?php 
                        echo form_open_multipart("dashboard/chanel_add/", 'class="form-horizontal"');
                    //    
                            echo form_error("chanel_name");
                            echo form_error("chanel_admin");
                            echo form_error("chanel_password");
                            echo form_error("chanel_confirm_password");
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
                        <div class="span6">
                          <?php if(isset($success_message)): ?> 
                            <div class="alert alert-block alert-success fade in"><?php echo $success_message; ?></div>
                          <?php endif; ?>

                          <div class="control-group">
                                <label class="control-label" for="url">Channel Name</label>
                                <div class="controls">
                                <?php  echo form_input("chanel_name",$chanel['name'],'class="large m-wrap"');?>
                                </div>
                          </div>
                          <div class="control-group">
                                <label class="control-label" for="url">Channel Url</label>
                                <div class="controls">
                                <?php  echo form_input("chanel_url",$chanel['url'],'class="large m-wrap"');?>
                                </div>
                          </div>
                          <div class="control-group">
                                <label class="control-label" for="url">Admin email</label>
                                <div class="controls">
                                <?php  echo form_input("chanel_admin",$chanel['chanel_admin'],'class="large m-wrap"');?>
                                </div>
                          </div>
                          <div class="control-group">
                                <label class="control-label" for="url">Password</label>
                                <div class="controls">
                                <?php  echo form_input(array("name"=>"chanel_password", 'type'=>'password'),'','class="large m-wrap"');?>
                                </div>
                          </div>
                          <div class="control-group">
                                <label class="control-label" for="url">Confirm Password</label>
                                <div class="controls">
                                <?php  echo form_input(array('name'=>"chanel_confirm_password", 'type'=>'password'),'','class="large m-wrap"');?>
                                </div>
                          </div>
                          <div class="control-group">
                                <div class="controls">
                                <label class="checkbox line" >
                                    <input type="checkbox" name="is_publish" <?php if($chanel['is_publish']) echo "checked='true'"; ?>   >Published
                                </label>
                                </div>
                          </div>
                        </div>
                        <div class="span6">
                            <div class="fileupload fileupload-new center" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                   <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" />
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                <div>
                                   <span class="btn btn-file"><span class="fileupload-new">Select image</span>
                                   <span class="fileupload-exists">Change</span>
                                   <input type="file" name="image" class="default" /></span>
                                   <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                             </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <?php echo form_submit('submit', "Add Channel",' class="btn blue"');?>
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

