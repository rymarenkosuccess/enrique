<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid" id="dashboard">

        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title">
                    <?php   echo $chanel['name']; ?>
                    <small></small>
                    <div class="btn-group pull-right">
                        <a id="sample_editable_1_new" href="<?php echo site_url('content/feed/text'); ?>" class="btn green">
                        New Post 
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
                            <th colspan="2">Post Type</th>
                            <th>Caption</th>
                            <th>Tags</th>
                            <th>Date</th>
                            <th>Credit</th>
                            <th>Published</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>     
                    <?php 
                    foreach($feeds as $feed): ?>
                        <tr>
                            <td class="feed-thumbnail-td ">
                            <?php if($feed['image_path']){  ?>
                                <img class='feed-thumbnail pull-right' src="<?php echo $feed['image_path']; ?>">
                            <?php } ?></td>
                            <td class="feed-thumbnail-noborder"><?php echo $feed['post_type']; ?></td>
                            <td class="span2"><div style='word-wrap:break-word; width: 100px'><?php echo $feed['caption']; ?></div></td>
                            <td><?php echo $feed['tags']; ?></td>
                            <td><?php echo $feed['date']; ?></td>
                            <td><?php echo $feed['credit']; ?></td>
                            <td><?php echo $feed['is_publish'] ? "YES" : "NO"; ?></td>
                            <td class="center">
                            <?php
                                $ids = explode("_", $feed['id']);
                                $post_controller_name = $ids[0];
                                echo 
                                '<a class="btn mini purple" href="'.site_url('content/feed/'.$post_controller_name.'_edit/'.$feed['id']).'">'.
                                '<i class="icon-edit"></i>Edit'.
                                '</a>';
                            ?>
                            </td>
                            <td class="center">
                            <?php
                                echo 
                                '<a class="btn mini black" href="javascript:confirm_del(\''.$feed['id'].'\')">'.
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
        if(confirm("Do you want to delete this feed?")) {
            document.location.href = "<?php echo site_url('content/delete_feed'); ?>/" + id;
        }
    }    
</script>