<!DOCTYPE html>
<head>
	<meta charset="utf-8" />
	<title><?php echo SITE_NAME; ?></title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link href="<?php echo ASSETS_DIR; ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="<?php echo ASSETS_DIR; ?>/css/metro.css" rel="stylesheet" />
	<link href="<?php echo ASSETS_DIR; ?>/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
	<link href="<?php echo ASSETS_DIR; ?>/bootstrap-fileupload/bootstrap-fileupload.css" rel="stylesheet" />
	<link href="<?php echo ASSETS_DIR; ?>/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link href="<?php echo ASSETS_DIR; ?>/css/style.css" rel="stylesheet" />
	<link href="<?php echo ASSETS_DIR; ?>/css/style_responsive.css" rel="stylesheet" />
	<link href="<?php echo ASSETS_DIR; ?>/css/style_default.css" rel="stylesheet" id="style_color" />
	<link rel="stylesheet" type="text/css" href="<?php echo ASSETS_DIR; ?>/gritter/css/jquery.gritter.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo ASSETS_DIR; ?>/uniform/css/uniform.default.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo ASSETS_DIR; ?>/bootstrap-daterangepicker/daterangepicker.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo ASSETS_DIR; ?>/cleditor/jquery.cleditor.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo ASSETS_DIR; ?>/bootstrap-datepicker/css/datepicker.css" />
	<link href="<?php echo ASSETS_DIR; ?>/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />
	<link href="<?php echo ASSETS_DIR; ?>/jqvmap/jqvmap/jqvmap.css" media="screen" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" href="<?php echo ASSETS_DIR; ?>/jquery-file-upload/css/jquery.fileupload-ui.css">
    <noscript>
        <link rel="stylesheet" href="<?php echo ASSETS_DIR; ?>/jquery-file-upload/css/jquery.fileupload-ui-noscript.css">
    </noscript>
    <!-- BEGIN JAVASCRIPTS -->
    <!-- Load javascripts at bottom, this will reduce page load time -->
    <script src="<?php echo ASSETS_DIR; ?>/js/jquery-1.8.3.min.js"></script>    
    <script src="<?php echo ASSETS_DIR; ?>/bootstrap-fileupload/bootstrap-fileupload.js"></script>    
    
    <!--[if lt IE 9]>
    <script src="<?php echo ASSETS_DIR; ?>/js/excanvas.js"></script>
    <script src="<?php echo ASSETS_DIR; ?>/js/respond.js"></script>    
    <![endif]-->    
    <script src="<?php echo ASSETS_DIR; ?>/breakpoints/breakpoints.js"></script>        
    <script src="<?php echo ASSETS_DIR; ?>/jquery-ui/jquery-ui-1.10.1.custom.min.js"></script>    
    <script src="<?php echo ASSETS_DIR; ?>/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo ASSETS_DIR; ?>/fullcalendar/fullcalendar/fullcalendar.min.js"></script>
    <script src="<?php echo ASSETS_DIR; ?>/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo ASSETS_DIR; ?>/js/jquery.blockui.js"></script>    
    <script src="<?php echo ASSETS_DIR; ?>/js/jquery.cookie.js"></script>
    <script src="<?php echo ASSETS_DIR; ?>/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>    
    <script src="<?php echo ASSETS_DIR; ?>/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS_DIR; ?>/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS_DIR; ?>/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS_DIR; ?>/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS_DIR; ?>/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS_DIR; ?>/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>    
    <script src="<?php echo ASSETS_DIR; ?>/flot/jquery.flot.js"></script>
    <script src="<?php echo ASSETS_DIR; ?>/flot/jquery.flot.resize.js"></script>
    <script src="<?php echo ASSETS_DIR; ?>/flot/jquery.flot.pie.js"></script>
    <script src="<?php echo ASSETS_DIR; ?>/flot/jquery.flot.stack.js"></script>    
    <script type="text/javascript" src="<?php echo ASSETS_DIR; ?>/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="<?php echo ASSETS_DIR; ?>/uniform/jquery.uniform.min.js"></script>    
    <script type="text/javascript" src="<?php echo ASSETS_DIR; ?>/data-tables/jquery.dataTables.js"></script>
    <script type="text/javascript" src="<?php echo ASSETS_DIR; ?>/data-tables/DT_bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo ASSETS_DIR; ?>/js/jquery.pulsate.min.js"></script>
    <script type="text/javascript" src="<?php echo ASSETS_DIR; ?>/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="<?php echo ASSETS_DIR; ?>/bootstrap-daterangepicker/date.js"></script>
    <script type="text/javascript" src="<?php echo ASSETS_DIR; ?>/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!--<script type="text/javascript" src="<?php echo ASSETS_DIR; ?>/cleditor/jquery.cleditor.js"></script>-->    
    <script src="<?php echo ASSETS_DIR; ?>/js/app.js"></script>    
    <script>
        jQuery(document).ready(function() {        
            App.setPage("<?php echo $curPage = $this->router->fetch_class().'/'.$this->router->fetch_method(); ?>");  // set current page
            App.init(); // init the rest of plugins and elements
        });
    </script>

</head>
<input type='hidden' value="<?php echo site_url('ajax'); ?>" id="ajax_control_url">
<!-- END HEAD -->
<!-- BEGIN BODY -->
<?php 
     $designinfo = $this->ion_auth->getColors();
?>
<style >
    .backcolor, .backcolor .backcolor{
        background-color: <?php echo '#'.$designinfo['back_color'] ?> !important;
    }
    .btn, .backcolor .btn:hover, .backcolor .btn:focus{
        background-color: <?php echo '#'.$designinfo['button_color'] ?> !important;
    }
    span.title, ul.sub li a{
        color : <?php echo '#'.$designinfo['link_color'] ?> !important;
    }
    a:hover span.title, span.title:hover,.page-sidebar > ul > li > ul.sub > li > a:hover{
        color: <?php echo '#'.$designinfo['text_color'] ?> !important;
    }
</style>
<body class="fixed-top backcolor">
	<!-- BEGIN HEADER -->
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="navbar-inner backcolor">
			<div class="container-fluid">
				<!-- BEGIN LOGO -->
				<a class="brand" href="<?php echo site_url(); ?>">
				<img src="<?php if($designinfo['header_image']){
                    echo UPLOAD_URL.$designinfo['header_image'];
                }else{
                    echo ASSETS_DIR."/img/logo.png";
                }
                ?>" alt="logo" style="height:28px" />
				</a>
				<!-- END LOGO -->
<?php
	 if ($this->ion_auth->logged_in()):
?>
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
				<img src="<?php echo ASSETS_DIR; ?>/img/menu-toggler.png" alt="" />
				</a>          
				<!-- END RESPONSIVE MENU TOGGLER -->				
				<!-- BEGIN TOP NAVIGATION MENU -->					
				<ul class="nav pull-right">
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<li class="dropdown user">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img alt="" src="<?php echo ASSETS_DIR; ?>/img/avatar1_small.png" />
						<span class="username">
							<?php 
								echo $this->session->userdata('email'); 
							?>
						</span>
						<i class="icon-angle-down"></i>
						</a>
						<ul class="dropdown-menu">
							<li><a href="#"><i class="icon-user"></i> My Profile</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo site_url("auth/logout"); ?>"><i class="icon-key"></i> Log Out</a></li>
						</ul>
					</li>
					<!-- END USER LOGIN DROPDOWN -->
				</ul>
				<!-- END TOP NAVIGATION MENU -->	
<?php
	endif; 
?>
			</div>
		</div>
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->

	<!-- BEGIN CONTAINER -->
	<div class="page-container row-fluid">

	