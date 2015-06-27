<!-- BEGIN PAGE -->
<div class="page-content">

        <!-- BEGIN PAGE CONTAINER-->            
        <div class="container-fluid" id="dashboard">

            <!-- BEGIN PAGE HEADER-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                    <h3 class="page-title">
                        <small></small>
                    </h3>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>
            <!-- END PAGE HEADER-->
        
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
                <div class="portlet box yellow">
                    <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>Auto email list</h4>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="hidden-480">ID</th>
                                    <th>Subject</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th class="hidden-480">Gender</th>
                                    <th class="hidden-480">Email Date</th>
                                    <th class="hidden-480">Sent</th>
                                    <th class="hidden-480">Edit</th>
                                    <th class="hidden-480">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($events as $event): ?>
                                    <tr>
                                        <td class="span1 hidden-480"><?php echo $event['id']; ?></td>
                                        <td class="span2"><?php echo $event['subject']; ?></td>
                                        <td class="span2"><?php echo $event['state']=="" ? "All" : $event['state']; ?></td>
                                        <td class="span2"><?php echo $event['city'] == "" ? "All" : $event['city']; ?></td>
                                        <td class="span2 hidden-480"><?php echo $event['gender']=='2' ? "All" : ($event['gender'] == "1" ? "Female" : "Male"); ?></td>
                                        <td class="span2 hidden-480"><?php echo $event['email_date']; ?></td>
                                        <td class="span2 hidden-480"><?php echo $event['sent'] ? "Yes" : "NO"; ?></td>
                                        <td class="center span2 hidden-480">
                                        <?php
                                            echo 
                                            '<a class="btn mini purple" href="'.site_url('configuration/email_edit/'.$event['id']).'">'.
                                            '<i class="icon-edit"></i>Edit'.
                                            '</a> ';
                                        ?>
                                        </td>
                                        <td class="center span2 hidden-480">
                                        <?php
                                            echo 
                                            '<a class="btn mini purple" href="javascript:confirm_del('.$event['id'].')">'.
                                            '<i class="icon-edit"></i>Delete'.
                                            '</a> ';
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
        <!-- END PAGE CONTAINER-->
</div>
<div style="display: none;">
    <textarea id="usagesbyregion" ><?php echo $usagesbyregion; ?></textarea>
    <textarea id="usagesbyusertype" ><?php echo $usagesbyusertype; ?></textarea>
</div>
<!-- END PAGE -->
<script type="">
    function confirm_del(id) {
        if(confirm("Do you want to delete?")) {
            document.location.href = "<?php echo site_url("configuration/email_delete/"); ?>" + id;
        }
    }
</script>