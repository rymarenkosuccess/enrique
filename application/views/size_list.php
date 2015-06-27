<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid" id="dashboard">

        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title">
                    Size List
                    <small></small>
                    <div class="btn-group pull-right">
                        <a id="sample_editable_1_new" href="<?php echo site_url('attribute/size/add'); ?>" class="btn green">
                        Add New 
                        </a>
                    </div>
                </div>
                <?php // echo $message;?>
                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>
        <div class="row-fluid">
            <div class="portlet-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="span2">ID</th>
                            <th>Name</th>
                            <th class="span2">Edit</th>
                            <th class="span2">Delete</th>
                        </tr>
                    </thead>
                    <tbody>     
                    <?php 
                    foreach($sizes as $size): ?>
                        <tr>
                            <!--<td class="feed-thumbnail-td ">
                            <?php if(is_file(UPLOAD_DIR.$event['image_path'])){  ?>
                                <img class='feed-thumbnail pull-right' src="<?php echo UPLOAD_URL.$feed['image_path']; ?>">
                            <?php } ?>
                            </td>-->
                            <td><?php echo $size['id']; ?></td>
                            <td><?php echo $size['name']; ?></td>
                            <td class="center">
                            <?php
                                echo 
                                '<a class="btn mini purple" href="'.site_url('attribute/size/edit/'.$size['id']).'">'.
                                '<i class="icon-edit"></i>Edit'.
                                '</a>';
                            ?>
                            </td>
                            <td class="center">
                            <?php
                                echo 
                                '<a class="btn mini black" href="javascript:confirm_del(\''.$size['id'].'\')">'.
                                '<i class="icon-trash"></i>Delete'.
                                '</a>';
                            ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function confirm_del(id) {
        if(confirm("Do you want to delete this size item?")) {
            document.location.href = "<?php echo site_url('attribute/size_delete'); ?>/" + id;
        }
    }    
</script>