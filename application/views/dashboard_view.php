<!-- BEGIN PAGE -->
<div class="page-content">
        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <!--<div id="portlet-config" class="modal hide">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button"></button>
                <h3>Questions</h3>
            </div>
            <div class="modal-body">
                <p>Here will be a configuration form</p>
            </div>
        </div>-->
        <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

        <!-- BEGIN PAGE CONTAINER-->            
        <div class="container-fluid" id="dashboard">

            <!-- BEGIN PAGE HEADER-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                    <div class="page-title ">
                        Channel 
                        <small></small>
                        <?php if($user->superadmin){ ?>
                                <div class="btn-group pull-right">
                                    <a id="sample_editable_1_new" href="<?php echo site_url("dashboard/chanel_add"); ?>" class="btn green">
                                    Create New Channel <i class="icon-plus"></i>
                                    </a>
                                </div>
                        <?php } ?>
                        <div class="btn-group pull-right" style="margin-right: 20px;">
                            <form method="post" action="<?php echo site_url('dashboard/index'); ?>">
                                <div class="input-append">                      
                                    <input class="m-wrap" size="16" placeholder="keyword..." type="text" name="searchvalue" value="<?php echo $searchvalue; ?>">
                                    <button class="btn blue">Search</button>
                                </div>
                            </form>                                   
                        </div>
                    </div>
                    <?php // echo $message;?>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>
            <!-- END PAGE HEADER-->
        
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
                        <div class="portlet-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="2">Name</th>
                                        <th>Email</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>     
                                <?php 
                                foreach($chanels as $chanel): ?>
                                    <tr>
                                        <td class="feed-thumbnail-td ">
                                        <?php if(is_file(UPLOAD_DIR.$chanel->image_path)){  ?>
                                            <img class='feed-thumbnail pull-right' src="<?php echo UPLOAD_URL.$chanel->image_path; ?>">
                                        <?php } ?>
                                        </td>
                                        <td><a href="<?php echo site_url('dashboard/content/'.$chanel->id); ?>"><?php echo $chanel->name; ?></a></td>
                                        <td><?php echo $chanel->chanel_admin; ?></td>
                                        <td class="center">
                                        <?php
                                            echo 
                                            '<a class="btn mini purple" href="'.site_url('dashboard/chanel_edit/'.$chanel->id).'">'.
                                            '<i class="icon-edit"></i>Edit'.
                                            '</a> ';
                                        ?>
                                        </td>
                                        <td class="center">
                                        <?php
                                            echo 
                                            '<a class="btn mini black" href="javascript:confirm_del('.$chanel->id.')">'.
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
            <!--<div class="row-fluid">
                <div class="span6">
                    <div class="portlet box yellow">
                        <div class="portlet-title">
                            <h4><i class="icon-reorder"></i>Usage by geography</h4>
                            <div class="tools">
                                <a href="#portlet-config" data-toggle="modal" class="config"></a>
                                <a href="javascript:;" class="reload"></a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div id="graph_1" class="chart"></div>
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="portlet box purple">
                        <div class="portlet-title">
                            <h4><i class="icon-reorder"></i>Usage by role</h4>
                            <div class="tools">
                                <a href="#portlet-config" data-toggle="modal" class="config"></a>
                                <a href="javascript:;" class="reload"></a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div id="graph_2" class="chart"></div>
                        </div>
                    </div>
                </div>
                
                <!--<div class="span6">
                    <div class="portlet responsive" data-tablet="span6" data-desktop="">
                        <div class="dashboard-stat blue">
                            <div class="details">
                                <div class="number">
                                    <?php echo $unique_users; ?>
                                </div>
                                <div class="desc">                                    
                                    Unique Users
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet responsive" data-tablet="span6" data-desktop="">
                        <div class="dashboard-stat green">
                            <div class="details">
                                <div class="number"><?php echo $totaltimeofgame ?></div>
                                <div class="desc">Total time of game</div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet responsive" data-tablet="span6" data-desktop="">
                        <div class="dashboard-stat purple">
                            <div class="details">
                                <div class="number"><?php echo $avgtime; ?></div>
                                <div class="desc">Average time</div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet responsive" data-tablet="span6" data-desktop="">
                        <div class="dashboard-stat green">
                            <div class="details">
                                <div class="number"><?php echo $numberofquestions; ?></div>
                                <div class="desc">Number of questions submitted</div>
                                <div class="number"><?php echo $numberofanswered; ?></div>
                                <div class="desc">Number of correct answer</div>
                                <div class="number"><?php echo $numberofquestions-$numberofanswered; ?></div>
                                <div class="desc">Number of incorrect answer</div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet responsive" data-tablet="span6" data-desktop="">
                        <div class="dashboard-stat yellow">
                            <div id="difficulty_question"><?php echo $difficultquestion; ?></div>
                            <div class="details">
                                <div class="desc">Which questions are most difficult</div>
                            </div>
                        </div>
                    </div>
                </div>-->
            <!--</div>-->
        </div>
        <!-- END PAGE CONTAINER-->
</div>
<div style="display: none;">
    <textarea id="usagesbyregion" ><?php echo $usagesbyregion; ?></textarea>
    <textarea id="usagesbyusertype" ><?php echo $usagesbyusertype; ?></textarea>
</div>
<!-- END PAGE -->
<script>
    function confirm_del(id) {
        if(confirm("Do you want to delete this channel?")) {
            document.location.href = "<?php echo site_url('dashboard/chanel_del'); ?>/" + id;
        }
    }
</script>
