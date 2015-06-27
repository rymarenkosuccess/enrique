<!-- BEGIN PAGE -->
<div class="page-content">
		<!-- BEGIN PAGE CONTAINER-->			
		<div class="container-fluid">

			<!-- BEGIN PAGE HEADER-->
			<div class="row-fluid">
				<div class="span12">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
					<h3 class="page-title">
						Fans
					</h3>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<!-- END PAGE HEADER-->
		
			<!-- BEGIN PAGE CONTENT-->
			<div class="row-fluid">
				<div class="span12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box yellow">
						<div class="portlet-title">
							<h4><i class="icon-reorder"></i>Block Fans</h4>
							<div class="tools">
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-striped table-bordered table-hover" id="sample_1">
								<thead>
									<tr>
                                        <th ></th>
										<th>Username</th>
                                        <th class="">Last login</th>
										<th class="">Join Date</th>
                                        <th class="hidden-480">Block</th>
                                        <th class="hidden-480">Suspend</th>
										<th class="hidden-480">Credit</th>
                                        <!--<th class="hidden-480">Edit</th>-->
										<th class="hidden-480">Delete</th>
									</tr>
								</thead>
								<tbody>
	                            <?php foreach ($users as $user):?>

									<tr class="odd gradeX">
                                        <td class="photo-thumbnail-td">
                                            <img height="50" width="50" class='photo-thumbnail pull-right' src="<?php if(is_file(UPLOAD_DIR.$user['img_url'])){ echo UPLOAD_URL.$user['img_url']; }else{ echo UPLOAD_URL."photo/no-image-50.png"; } ?>">
                                        </td>
                                        <!--<td ><a href='<?php echo site_url('fan/view')."/".$user['id']; ?>'><?php echo $user['name'];?></a></td>-->
                                        <td ><?php echo $user['name'];?></td>
                                        <td class="span2"><?php echo $user['last_login'];?></td>
                                        <td class="span2"><?php echo $user['join_date'];?></td>
                                        <td class="span2 hidden-480"><?php 
                                            echo (!$user['is_block']) ? 
                                                '<span class="label label-success">'.anchor("fan/deactivate/".$user['id'], "unblocked").'</span>' : 
                                                '<span class="label label-warning">'.anchor("fan/activate/". $user['id'], "blocked").'</span>' ;
                                            ?></td>
										<td class="span2 hidden-480"><?php 
											echo (!$user['is_suspend']) ? 
												'<span class="label label-success">'.anchor("fan/suspend/".$user['id'], "unsuspended").'</span>' : 
												'<span class="label label-warning">'.anchor("fan/unsuspend/". $user['id'], "suspended").'</span>' ;
											?></td>
                                        <td class="span2 hidden-480"><?php echo $user['credit'];?></td>
                                        <!--<td class="span1 hidden-480 center"><?php 
                                            echo anchor("fan/edit_user/".$user['id'], 'Edit', 'class="btn mini purple"') ;
                                            //echo "&nbsp;";
                                            //echo anchor("auth/edit_user/".$user->id, 'Delete', 'class="btn mini black"') ;
                                            ?></td>-->
										<td class="span1 hidden-480 center">
                                            <a class="btn mini black" href="javascript:confirm_del('<?php echo $user['id']; ?>')">Delete</a>
                                        </td>
									</tr>
	                            <?php endforeach;?>

								</tbody>
							</table>
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
			<!-- END PAGE CONTENT-->
			
		</div>
		<!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->
<script type="">
    function confirm_del(id) {
        if(confirm("Do you want to delete this user?")) {
            document.location.href = "<?php echo site_url('fan/delete_user'); ?>/" + id;
        }
    }
</script>

