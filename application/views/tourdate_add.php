<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title">
                    Tour Date
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
                    <h4><i class="icon-reorder"></i>Add Form</h4>
                    <div class="tools">
                    </div>
                 </div>
                 <div class="portlet-body form">
                    <?php 
                    echo form_open_multipart("content/tour_date/add", 'class="form-horizontal"');
                        echo form_error("title");
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
                            <div class="control-group">
                                <label class="control-label" for="url">Title</label>
                                <div class="controls customarea">
                                <?php  echo form_input("title",$post['title'],'class="large m-wrap"');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="url">Position</label>
                                <div class="controls customarea">
                                <?php  echo form_input("position",$post['position'],'class="large m-wrap"');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="email_date">Date of Concert</label>
                                <div class="controls">
                                    <?php  echo form_input("concert_date", $post['concert_date'],'class="large m-wrap m-ctrl-medium date-picker" ');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="email_date">Start Date</label>
                                <div class="controls">
                                    <?php  echo form_input("start_date", $post['start_date'],'class="large m-wrap m-ctrl-medium date-picker" ');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="email_date">End Date</label>
                                <div class="controls">
                                    <?php  echo form_input("end_date", $post['end_date'],'class="large m-wrap m-ctrl-medium date-picker" ');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="url">Price</label>
                                <div class="controls customarea">
                                <?php  echo form_input("price",$post['price'],'class="large m-wrap"');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="url">Ref URL</label>
                                <div class="controls customarea">
                                <?php  echo form_input("url",$post['url'],'class="large m-wrap"');?>
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
