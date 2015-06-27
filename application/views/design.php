<!-- BEGIN PAGE -->
<div id="fancybox_container_add" style="width:500px;height:400px;display: none;">
    <h2 class="center">Add New Tab</h2>
    <div class="form-horizontal" style="width: 90%;">
        <div class="control-group " >
            <label class="control-label" for="url">Title</label>
            <div class="controls">
                <?php  echo form_input("title",'','class="alter_name medium m-wrap"');?>
            </div>
        </div>
        <div class="control-group " >
            <label class="control-label" for="url">Type</label>
            <div class="controls">
                <?php  echo form_dropdown("section_id", $defaultSectionOptions,'',' class="section_id medium m-wrap"');?>
            </div>
        </div>
        <div class="control-group " >
            <label class="control-label" for="url">Order</label>
            <div class="controls">
                <?php  echo form_input("ordering",'','class="ordering medium m-wrap"');?>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
            <label class="checkbox line" >
                <input type="checkbox" class='is_publish'>Published
            </label>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <?php echo form_submit('submit', "Save",' class="save_menu btn blue"');?>
            </div>
        </div>
   </div>
</div>
<div id="fancybox_container_edit" style="width:500px;height:400px;display: none;">
    <h2 class="center">Edit Tab<span class="pull-right " style="margin-right: 20px;"><a class="btn delete_menu" href="#" />Delete</a></span></h2>
    <input type="hidden" value="" class="section_menu_id">
    <div class="form-horizontal" style="width: 90%;">
        <div class="control-group " >
            <label class="control-label" for="url">Title</label>
            <div class="controls">
                <?php  echo form_input("title",'','class="alter_name medium m-wrap"');?>
            </div>
        </div>
        <div class="control-group " >
            <label class="control-label" for="url">Order</label>
            <div class="controls">
                <?php  echo form_input("ordering",'','class="ordering medium m-wrap"');?>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
            <label class="checkbox line" >
                <input type="checkbox" class="is_publish" >Published
            </label>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <?php echo form_submit('submit', "Save",' class="save_menu btn blue"');?>
            </div>
        </div>
   </div>
</div>
<div class="page-content">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title">
                    Channel Design
                    <small></small>
                </div>
                                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>    
        <div class="row-fluid">
            <div class="span12">
<!-- BEGIN SAMPLE FORM PORTLET-->   
                 <div class="portlet-body form">
                    <?php 
                    echo form_open_multipart("design", 'class="chanel-design form-horizontal"');
                        echo form_error("description");
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
                            <div class="span6">
                                <ul class="menu-subitems ">
                                    <?php foreach($submenus as $meun): ?>
                                        <li>
                                            <span class="span6 left"><?php echo $meun['alter_name']; ?></span>
                                            <span class="span6 right"><a class="fancybox_menu_edit" href="#">Edit</a></span>
                                            <input type="hidden" class='section_menu_id' value='<?php echo $meun['id']; ?>' />
                                        </li>
                                    <?php endforeach; ?>
                                    <?php if(count($submenus) < 7): ?>
                                        <li>
                                            <span class="span6 left"><a class="fancybox_menu_add" href="#">+ Add new</a></span>
                                            <input type="hidden" class='section_menu_id' value='<?php echo $meun['id']; ?>' />
                                        </li>
                                    <?php endif; ?>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="span6">
                                <div class="form-horizontal" style="width: 90%;">
                                    <div class="control-group " >
                                        <label class="control-label small m-wrap" for="url">Link Color:</label>
                                        <div class="controls">
                                            <input class="color m-wrap" name="link_color" value="<?php echo $post['link_color']; ?>" style="width: 50px;" >
                                        </div>
                                    </div>
                                </div>
                                <div class="form-horizontal" style="width: 90%;">
                                    <div class="control-group " >
                                        <label class="control-label small m-wrap" for="url">Button Color:</label>
                                        <div class="controls">
                                            <input class="color m-wrap" name="button_color" value="<?php echo $post['button_color']; ?>" style="width: 50px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-horizontal" style="width: 90%;">
                                    <div class="control-group " >
                                        <label class="control-label small m-wrap" for="url">Background Color:</label>
                                        <div class="controls">
                                            <input class="color m-wrap" name="back_color" value="<?php echo $post['back_color']; ?>" style="width: 50px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-horizontal" style="width: 90%;">
                                    <div class="control-group " >
                                        <label class="control-label small m-wrap" for="url">Text:</label>
                                        <div class="controls">
                                            <input class="color m-wrap" name="text_color" value="<?php echo $post['text_color']; ?>" style="width: 50px;">
                                        </div>
                                    </div>
                                </div>
                                    
                            </div>
                        </div>
                        <div class="span6">
                            <?php
                                if(isset($post['header_image']) && is_file(UPLOAD_DIR.$post['header_image']) ){
                                    $header_image = UPLOAD_URL.$post['header_image'];
//                                    echo $header_image;exit;
                                }else{
                                    $header_image = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image";
                                }
                            ?>
                            <div class="fileupload fileupload-new center" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 90%; height: 150px;">
                                    <img src="<?php echo $header_image; ?>" alt="" />
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="width: 90%; max-height: 150px; line-height: 20px;"></div>
                                <div class="image_label_container">
                                    <span class="image_label">Header image</span>
                                   <span class="btn btn-file"><span class="fileupload-new">Select image</span>
                                   <span class="fileupload-exists">Change</span>
                                   <input type="file" name="image1" class="default" /></span>
                                   <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                            </div>
                            <div class="fileupload fileupload-new center" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 90%; height: 150px;">
                                    <img src="<?php echo ((isset($post['watermark']) && $post['watermark']) ? UPLOAD_URL.$post['watermark'] : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image') ?>" alt="" />
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="width: 90%; max-height: 150px; line-height: 20px;"></div>
                                <div class="image_label_container">
                                    <span class="image_label">Watermark</span>
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
                        <span class="space7">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <?php echo form_submit('del_header', "Delete header",' class="btn blue"');?>
                        <span class="space7">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <?php echo form_submit('del_watermark', "Delete watermark",' class="btn blue"');?>
                    </div>
                    <?php echo form_close();?>
                    <!-- END FORM-->
                 </div>
              <!-- END SAMPLE FORM PORTLET-->
            </div>
        </div>
    </div>
</div>
<link href="<?php echo ASSETS_DIR; ?>/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
<script src="<?php echo ASSETS_DIR; ?>/fancybox/source/jquery.fancybox.pack.js" ></script>
<script src="<?php echo ASSETS_DIR; ?>/jscolor/jscolor.js" ></script>
