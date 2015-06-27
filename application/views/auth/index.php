<!-- BEGIN PAGE -->
<div class="page-content">
		<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<div id="portlet-config" class="modal hide">
			<div class="modal-header">
				<button data-dismiss="modal" class="close" type="button"></button>
				<h3>portlet Settings</h3>
			</div>
			<div class="modal-body">
				<p>Here will be a configuration form</p>
			</div>
		</div>
		<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

		<!-- BEGIN PAGE CONTAINER-->			
		<div class="container-fluid">

			<!-- BEGIN PAGE HEADER-->
			<div class="row-fluid">
				<div class="span12">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
					<h3 class="page-title">
                        Admins
						<small></small>
					</h3>
					<?php echo $message;?>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<!-- END PAGE HEADER-->

			<!-- BEGIN PAGE CONTENT-->
			<div class="row-fluid">
				<div class="span12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box light-grey">
						<div class="portlet-title">
							<h4><i class="icon-reorder"></i>Managed Table</h4>
							<div class="tools">
							</div>
						</div>
						<div class="portlet-body">
							<div class="clearfix" style="display: none;">
								<div class="btn-group">
									<a id="sample_editable_1_new" href="<?php echo site_url("auth/create_user"); ?>" class="btn green">
									Add New User <i class="icon-plus"></i>
									</a>
<!--  
									<a id="sample_editable_2_new" href="<?php echo site_url("auth/create_group"); ?>" class="btn blue">
									Create New Group <i class="icon-plus"></i>
									</a>
-->
								</div>
							</div>
							<table class="table table-striped table-bordered table-hover" id="sample_1">
								<thead>
									<tr>
										<th style="width:8px;"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
										<!--<th>Username</th>-->
                                        <th class="hidden-480">Email</th>
										<th class="hidden-480">Channel</th>
										<th class="hidden-480" style="display: none;">Group</th>
										<th class="hidden-480">Status</th>
                                        <th class="hidden-480">Edit</th>
										<th class="hidden-480">Delete</th>
									</tr>
								</thead>
								<tbody>
	<?php foreach ($users as $user):?>

									<tr class="odd gradeX">
										<td><input type="checkbox" class="checkboxes" value="<?php echo $user->id; ?>" /></td>
										<!--<td><?php echo $user->username;?></td>-->
                                        <td class="hidden-480"><a href="mailto:<?php echo $user->email;?>"><?php echo $user->email;?></a></td>
										<td class="hidden-480"><?php echo $user->chanel;?></td>
										<td class="hidden-480" style="display: none;">
											<?php foreach ($user->groups as $group):?>
												<?php //echo anchor("auth/edit_group/".$group->id, $group->name) ;?><?php echo $group->name; ?><br />
							                <?php endforeach?>
										</td>
										<td class="hidden-480"><?php 
											echo ($user->active) ? 
												'<span class="label label-success">'.anchor("auth/deactivate/".$user->id, lang('index_active_link')).'</span>' : 
												'<span class="label label-warning">'.anchor("auth/activate/". $user->id, lang('index_inactive_link')).'</span>' ;
											?></td>
                                        <td ><?php 
                                            echo anchor("auth/edit_user/".$user->id, 'Edit', 'class="btn mini purple"') ;
                                            //echo "&nbsp;";
                                            //echo anchor("auth/edit_user/".$user->id, 'Delete', 'class="btn mini black"') ;
                                            ?></td>
										<td >
                                        <?php if(!$user->superadmin): ?>
                                            <a class="btn mini black" href="javascript:confirm_del('<?php echo $user->id; ?>')">Delete</a>
                                        <?php else: ?>
                                            Super admin
                                        <?php endif; ?>
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
            document.location.href = "<?php echo site_url('auth/delete_user'); ?>/" + id;
        }
    }
</script>

