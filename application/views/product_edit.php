<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title">
                    Edit Product
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
                    <h4><i class="icon-reorder"></i>Edit Form</h4>
                    <div class="tools">
                    </div>
                 </div>
                 <div class="portlet-body form">
                    <?php 
                    echo form_open_multipart("content/product/edit/".$post['id'], 'class="form-horizontal" id="fileupload"');
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
                    <input type="hidden" id="ajaxuploadproductphoto" value="<?php echo site_url("content/upload_product_photo"); ?>">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label" for="url">Title</label>
                                <div class="controls customarea">
                                <?php  echo form_input("title",$post['title'],'class="large m-wrap"');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="url">Category</label>
                                <div class="controls customarea">
                                    <?php echo form_dropdown("category", $categoryOptions, $post['category'], 'class="large m-wrap"');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="url">Color Options</label>
                                <div class="controls customarea">
                                    <?php echo form_dropdown("color", $colorOptions, $post['color'], 'class="large m-wrap"');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="url">Size Options</label>
                                <div class="controls customarea">
                                    <?php echo form_dropdown("size", $sizeOptions, $post['size'], 'class="large m-wrap"');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="url">Tags</label>
                                <div class="controls customarea">
                                    <?php echo form_input("tags", $post['tags'], 'class="large m-wrap"');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="url">Price</label>
                                <div class="controls customarea">
                                    <?php echo form_input("price", $post['price'], 'class="large m-wrap"');?>
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
                            <table role="presentation" class="table table-striped" style='margin-bottom: 0px;'>
                                <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">
                                    <?php if(is_array($post['imageArr'])): ?>
                                        <?php foreach($post['imageArr'] as $path): ?>
                                        <tr class="template-download fade in">
                                            <td class="preview span4">
                                                <a class="fancybox-button" data-rel="fancybox-button" href="<?PHP if(is_file(UPLOAD_DIR.$path)) echo UPLOAD_URL.$path; ?>" title="">
                                                    <img class="span4" src="<?PHP if(is_file(UPLOAD_DIR.$path)) echo UPLOAD_URL.$path; ?>">
                                                </a>
                                            </td>
                                            <td class="name">
                                                <a href="<?PHP if(is_file(UPLOAD_DIR.$path)) echo UPLOAD_URL.$path; ?>" title="" data-gallery="gallery" download="">Product Photo</a>
                                            </td>
                                            <td class="size"><span></span></td>
                                            <td colspan="2"></td>
                                            <td class="delete">
                                                <input name="photos[]" value="<?php echo $path; ?>" type="hidden">
                                                <button class="btn red" data-type="" data-url="">
                                                    <i class="icon-trash icon-white"></i>
                                                    <span>Delete</span>
                                                </button>
                                            </td>
                                        </tr>                                    
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="row-fluid fileupload-buttonbar">
                                <div class="span7">
                                    <!-- The fileinput-button span is used to style the file input field as button -->
                                    <span class="btn green fileinput-button">
                                    <i class="icon-plus icon-white"></i>
                                    <span>Add files...</span>
                                    <input type="file" id="some-file-input-field" name="image" multiple>
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
                            <!-- The loading indicator is shown during file processing -->
                            <div class="fileupload-loading"></div>
                            <br>
                            <!-- The table listing the files available for upload/download -->
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
                <div class="row-fluid">
                    <div class="span12">
                        <script id="template-upload" type="text/x-tmpl">
                            {% for (var i=0, file; file=o.files[i]; i++) { %}
                                <tr class="template-upload fade">
                                    <td class="preview"><span class="fade"></span></td>
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
                                        <td></td>
                                        <td class="name"><span>{%=file.name%}</span></td>
                                        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
                                        <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
                                    {% } else { %}
                                        <td class="preview span4">
                                        {% if (file.thumbnail_url) { %}
                                            <a class="fancybox-button" data-rel="fancybox-button" href="{%=file.url%}" title="{%=file.name%}">
                                                <img class="span4" src="{%=file.thumbnail_url%}">
                                            </a>
                                        {% } %}</td>
                                        <td class="name">
                                            <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
                                        </td>
                                        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
                                        <td colspan="2"></td>
                                    {% } %}
                                    <td class="delete">
                                        <input type="hidden" name='photos[]' value='{%=file.path%}'>
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
    </div>
</div>


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