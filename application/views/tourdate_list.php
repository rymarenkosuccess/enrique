<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid" id="dashboard">

        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title form-horizontal">
                    Tour Date
                    <small></small>
                    <div class="btn-group pull-right">
                        <a id="sample_editable_1_new" href="<?php echo site_url('content/tour_date/add'); ?>" class="btn green">
                        Add New 
                        </a>
                    </div>
                    <div class="btn-group pull-right" style="margin-right: 20px;">
                        <form method="post" action="<?php echo site_url('content/tour_date')."/".$selectedDate; ?>">
                            <div class="input-append">                      
                                <input class="m-wrap" size="16" placeholder="keyword..." type="text" name="searchvalue" value="<?php echo $searchvalue; ?>">
                                <button class="btn blue">Search</button>
                            </div>
                        </form>                                   
                    </div>
                    <div class="btn-group pull-right control-group" style="margin-right: 30px;">
                        <label class="control-label">
                        Filter By Date
                        </label>
                        <div class="controls">
                            <?php echo form_dropdown("tourdate_search", $dateOptions, $selectedDate, 'class="tourdate_search medium m-wrap"');?>
                        </div>
                    </div>
                </div>
                <?php // echo $message;?>
                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>
        <input type="hidden" value="<?php echo site_url('content/tour_date'); ?>" id='base_url'>
        <div class="row-fluid">
            <div class="portlet-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Location</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Price</th>
                            <th>Ref URL</th>
                            <th>See Stats</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>     
                    <?php 
                    foreach($events as $event): ?>
                        <tr>
                            <!--<td class="feed-thumbnail-td ">
                            <?php if(is_file(UPLOAD_DIR.$event['image_path'])){  ?>
                                <img class='feed-thumbnail pull-right' src="<?php echo UPLOAD_URL.$feed['image_path']; ?>">
                            <?php } ?>
                            </td>-->
                            <td><?php echo $event['title']; ?></td>
                            <td><?php echo $event['position']; ?></td>
                            <td><?php echo $event['start_date']; ?></td>
                            <td><?php echo $event['end_date']; ?></td>
                            <td><?php echo $event['price']; ?></td>
                            <td><?php echo $event['url']; ?></td>
                            <td></td>
                            <td class="center">
                            <?php
                                echo 
                                '<a class="btn mini purple" href="'.site_url('content/tour_date/edit/'.$event['id']).'">'.
                                '<i class="icon-edit"></i>Edit'.
                                '</a>';
                            ?>
                            </td>
                            <td class="center">
                            <?php
                                echo 
                                '<a class="btn mini black" href="javascript:confirm_del(\''.$event['id'].'\')">'.
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
        if(confirm("Do you want to delete this event?")) {
            document.location.href = "<?php echo site_url('content/tourdate_delete'); ?>/" + id;
        }
    }    
</script>