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
                    <h4><i class="icon-reorder"></i>Edit Form</h4>
                    <div class="tools">
                    </div>
                 </div>
                 <div class="portlet-body form">
                    <?php 
                    echo form_open_multipart("content/feed/video_edit/".$post['feed_id'], 'id="videofileupload" class="form-horizontal"');
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
                    <input type="hidden" id="ajaxuploadvideo" value="<?php echo site_url("content/upload_video"); ?>">
                     <div class="row-fluid" >
                        <div class="span6">
                           <div class="control-group">
                                <label class="control-label" for="url">Caption</label>
                                <div class="controls customarea">  
                                <?php  echo form_textarea("description",$post['description'],'class="large m-wrap"');?>
                                </div>
                            </div>
                            <div class="control-group" style="display: none;">
                                <label class="control-label" for="url">Video</label>
                                <div class="controls">
                                    <div class="row-fluid fileupload-buttonbar">
                                        <div class="span7">
                                            <!-- The fileinput-button span is used to style the file input field as button -->
                                            <span class="btn green fileinput-button">
                                            <i class="icon-plus icon-white"></i>
                                            <span>Add files...</span>
                                            <input id='' type="file" id="some-file-input-field" name="video" >
                                            </span>
                                        </div>
                                        <!-- The global progress information -->
                                        <div class="span5 fileupload-progress fade" style="display: none;">
                                            <!-- The global progress bar -->
                                            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                                <div class="bar" style="width:0%;"></div>
                                            </div>
                                            <!-- The extended global progress information -->
                                            <div class="progress-extended">&nbsp;</div>
                                        </div>
                                    </div>
                                    <table role="presentation" class="table table-striped" style='margin-bottom: 0px;'>
                                        <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">
                                            <?php if($post['destination']): ?>
                                                <tr class="template-download fade in">
                                                    <td class="name">
                                                        <a href="<?PHP if(is_file(UPLOAD_DIR.$post['destination'])) echo UPLOAD_URL.$post['destination']; ?>" title="" data-gallery="gallery" download="">Video</a>
                                                    </td>
                                                    <td class="size"><span><?php echo $post['format_size']; ?></span></td>
                                                    <td colspan="2"></td>
                                                    <td class="delete">
                                                        <input name="video" value="<?php echo $post['destination']; ?>" type="hidden">
                                                        <input type="hidden" name='destination' value='<?php echo $post['destination']; ?>'>
                                                        <input type="hidden" name='video_mime' value='<?php echo $post['video_mime']; ?>'>
                                                        <input type="hidden" name='video_size' value='<?php echo $post['video_size']; ?>'>
                                                        <button class="btn red" data-type="" data-url="">
                                                            <i class="icon-trash icon-white"></i>
                                                            <span>Delete</span>
                                                        </button>
                                                    </td>
                                                </tr>                                    
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                        <!-- The loading indicator is shown during file processing -->
                                        <div class="fileupload-loading"></div>
                                        <br>
                                        <!-- The table listing the files available for upload/download -->
                                </div>
                            </div>
                            <div class="control-group" style='display: none;'>
                                <label class="control-label" for="url">Video Url</label>
                                <div class="controls">
                                <?php  echo form_input("video_url",$post['video_url'],'class="large m-wrap"');?>
                                </div>
                            </div>

                            <div class="row-fluid">
                                <div class="span12">
                                    <script id="template-upload" type="text/x-tmpl">
                                        {% for (var i=0, file; file=o.files[i]; i++) { %}
                                            <tr class="template-upload fade">
                                                <td class="name"><span>{%=file.name%}</span></td>
                                                <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
                                                {% if (file.error) { %}
                                                    <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
                                                {% } else if (o.files.valid && !i) { %}
                                                    <td>
                                                        <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
                                                    </td>
                                                    <td class="start" >{% if (!o.options.autoUpload) { %}
                                                        <button class="btn">
                                                            <i class="icon-upload icon-white"></i>
                                                            <span>Start</span>
                                                        </button>
                                                    {% } %}</td>
                                                {% }  %}
                                                    
                                                <td class="cancel"></td>
                                            </tr>
                                        {% } %}
                                    </script>
                                    <!-- The template to display files available for download -->
                                    <script id="template-download" type="text/x-tmpl">
                                        {% for (var i=0, file; file=o.files[i]; i++) { %}
                                            <tr class="template-download fade">
                                                {% if (file.error) { %}
                                                    <td class="name"><span>{%=file.name%}</span></td>
                                                    <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
                                                    <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
                                                {% } else { %}
                                                    
                                                    <td class="name">
                                                        <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
                                                    </td>
                                                    <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
                                                    <td colspan="2"></td>
                                                {% } %}
                                                <td class="delete">
                                                    <input type="hidden" name='destination' value='{%=file.path%}'>
                                                    <input type="hidden" name='video_mime' value='{%=file.video_mime%}'>
                                                    <input type="hidden" name='video_size' value='{%=file.video_size%}'>
                                                    <button class="btn red" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                                        <i class="icon-trash icon-white"></i>
                                                        <span>Delete</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        {% } %}
                                    </script>
                                </div>
                            </div>
                            

                            <div class="control-group">
                                <label class="control-label" for="url">Tags</label>
                                <div class="controls">
                                <?php  echo form_input("tags",$post['tags'],'class="large m-wrap"');?>
                                </div>
                            </div>
                            <!--<div class="control-group">
                                <label class="control-label" for="url">Credits</label>
                                <div class="controls">
                                <?php  echo form_input("credit",$post['credit'],'class="large m-wrap"');?>
                                </div>
                            </div>-->
                            <div class="control-group">
                                <div class="controls">
                                <label class="checkbox line" >
                                    <input type="checkbox" name="is_publish" <?php  if($post['is_publish']) echo "checked='true'"; ?>   >Published
                                </label>
                                </div>
                            </div>
                        </div>
                        <div class="span6" style="display: none;">
                            <div class="fileupload fileupload-new center" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                    <input type="hidden" id="video_img_value" name="video_img_value" value="">
                                   <a href="<?php echo UPLOAD_URL.$post['destination']; ?>"><img id='video_img' style="max-height: 100%" src="<?php echo ((isset($post['image_path']) and $post['image_path']) ? UPLOAD_URL.$post['image_path'] : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image') ?>" alt="" /></a>
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                <div>
                                   <span class="btn btn-file"><span class="fileupload-new">Select image</span>
                                   <span class="fileupload-exists">Change</span>
                                   <input type="file" name="image" class="default" id="videoPhotoUpload" /></span>
                                   <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                             </div>
                        </div>
                    </div>
                        <div class="form-actions">
                            <?php echo form_submit('submit', "Post",' class="btn blue"');?>
                            <span class="space7">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            <?php // echo form_submit('delimg', "Delete image",' class="btn blue"');?>
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
<style type="">
    .btn.fileupload-exists{
        display: none;
    }
</style>
<script src="<?php echo ASSETS_DIR; ?>/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
    <!-- The Templates plugin is included to render the upload/download listings -->
    <script src="<?php echo ASSETS_DIR; ?>/jquery-file-upload/js/vendor/tmpl.min.js"></script>
    <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
    <script src="<?php echo ASSETS_DIR; ?>/jquery-file-upload/js/vendor/load-image.min.js"></script>
    <!-- The Canvas to Blob plugin is included for image resizing functionality -->
    <script src="<?php echo ASSETS_DIR; ?>/jquery-file-upload/js/vendor/canvas-to-blob.min.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="<?php echo ASSETS_DIR; ?>/jquery-file-upload/js/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="<?php echo ASSETS_DIR; ?>/jquery-file-upload/js/jquery.fileupload.js"></script>
    <!-- The File Upload file processing plugin -->
    <script src="<?php echo ASSETS_DIR; ?>/jquery-file-upload/js/jquery.fileupload-fp.js"></script>
    <!-- The File Upload user interface plugin -->
    <script src="<?php echo ASSETS_DIR; ?>/jquery-file-upload/js/jquery.fileupload-ui.js"></script>
    <!-- The main application script -->
    <script src="<?php echo ASSETS_DIR; ?>/jquery-file-upload/js/main.js"></script>
    <script>

</script>