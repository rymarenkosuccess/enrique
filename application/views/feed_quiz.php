<!-- BEGIN PAGE -->
<div class="page-content">
    <?php 
        $this->load->view('header_feed');
    ?>
    <div class="container-fluid">
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
                    echo form_open_multipart("content/feed/quiz", 'class="form-horizontal"');
                        echo form_error("description");
                        echo form_error("question0[0]");
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
                    <h4 class="alert-heading">Opening Page</h4>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label" for="url">Caption</label>
                                <div class="controls customarea">
                                <?php  echo form_textarea("description",$post['description'],'class="large m-wrap"');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="url">Tags</label>
                                <div class="controls">
                                <?php  echo form_input("tags",$post['tags'],'class="large m-wrap"');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="url">Credits</label>
                                <div class="controls">
                                <?php  echo form_input("credit",'','class="large m-wrap"');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                <label class="checkbox line" >
                                    <input type="checkbox" name="is_publish" <?php  if($post['is_publish']) echo "checked='true'"; ?>   >Published
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
                                   <input type="file" name="image1" class="default" /></span>
                                   <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                             </div>
                        </div>
                    </div>
                    <div style="border: 1px solid #e5e5e5;margin-bottom: 15px;"></div>
                    <h4 class="alert-heading">Questions</h4>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                               <div class="controls customarea">
                                 <h5 style="font-weight: bold;">Question</h5>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <table class="table answer">
                                <tr>
                                    <th>Possible Answers</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div id='question_container'>
                        <input type="hidden" id='qcount' value="1">
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                   <div class="controls customarea">
                                    <?php  echo form_textarea("question[0]",'','class="large m-wrap"');?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <table class="table answer">
                                    <tr>
                                        <td>
                                            <?php  echo form_input("answer[0][]",'','class="large m-wrap"');?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php  echo form_input("answer[0][]",'','class="large m-wrap"');?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php  echo form_input("answer[0][]",'','class="large m-wrap"');?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php  echo form_input("answer[0][]",'','class="large m-wrap"');?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">                    
                        <span class="add-more" onclick="return addMoreQuizQuestion();">Add More</span>
                    </div>
                    <div style="border: 1px solid #e5e5e5;margin-bottom: 15px;"></div>
                    <h4 class="alert-heading">Closing Page</h4>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label" for="url">Caption</label>
                                <div class="controls customarea">
                                <?php  echo form_textarea("end_description",$post['end_description'],'class="large m-wrap"');?>
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
                                   <input type="file" name="image2" class="default" /></span>
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
