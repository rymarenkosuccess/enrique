<?php

	$curPage = $this->router->fetch_class().'/'.$this->router->fetch_method();
    $chanel = $this->session->userdata('chanel') ? $this->session->userdata('chanel') : 0;

    $user_id = $this->session->userdata('user_id');
    $selfuser = $this->ion_auth->user($user_id)->row();
    $submenus = $this->ion_auth->getContentMenuList();
    if($submenus){
        $content_menus = array();
        foreach($submenus as $submenu){
            $content_menus[] = array($submenu['url'], $submenu['alter_name']);
        }
    }
    
    if(!$chanel){
        $side_menus = array(
            0    =>    array( 'dashboard/index',     'Dashboard', 'icon-home'    ),
            11    =>    array( 'auth/logout',     'Logout', 'icon-user'    ),
        ); 
    }elseif($selfuser->superadmin){
//        $this->session->unset_userdata('chanel');
        $side_menus = array(
            0    =>    array( 'dashboard/index',     'Dashboard', 'icon-home'    ),
            4    =>    array( '#', 'Content', 'icon-th-list', 
                            $content_menus
//                        array(
//                            0    =>    array('content/feed',    'Home Feed'),
//                            1    =>    array('content/community',    'Community'),
//                            2    =>    array('content/photo_gallery',    'Photo Gallery'),
//                            3    =>    array('content/video_gallery',    'Video Gallery'),
//                            4    =>    array('content/tour_date',    'Tour Dates'),
//                            5    =>    array('content/music_player',    'Music Player'),
//                            6    =>    array('content/product',    'Shop'),
//                        ) 
                    ),
            5    =>    array( '#',     'Fans', 'icon-th-list'    , 
                        array(
                            0    =>    array('fan/active',    'Active Fans'),
                            1    =>    array('fan/block',    'Blocked Fans')
                        ) 
                    ),
            6    =>    array( 'design/index',     'Design', 'icon-th-list'    ),
            7    =>    array( 'currency/index',     'Currency', 'icon-th-list'    ),
            8    =>    array( 'stats/index',     'Stats', 'icon-th-list'    ),
            9    =>    array( 'social/index',     'Social', 'icon-th-list'    ),
            10    =>    array( '#', 'Attribute', 'icon-th-list', 
                        array(
                            0    =>    array('attribute/category',    'Category'),
                            1    =>    array('attribute/color',    'Color'),
                            2    =>    array('attribute/size',    'Size')
                        ) 
                    ),
            11    =>    array( '#', 'Admins', 'icon-th-list', 
                        array(
                            0    =>    array('auth/index',            'Admin List'),
//                            1    =>    array('auth/create_user',    'Create Admin'),
                        ) 
                    ),
            12    =>    array( 'auth/logout',     'Logout', 'icon-user'    ),
        ); 
    }else{
        $side_menus = array(
            0    =>    array( 'dashboard/index',     'Dashboard', 'icon-home'    ),
            4    =>    array( '#', 'Content', 'icon-th-list', 
                        array(
                            0    =>    array('content/feed',    'Home Feed'),
                            1    =>    array('content/community',    'Community'),
                            2    =>    array('content/photo_gallery',    'Photo Gallery'),
                            3    =>    array('content/video_gallery',    'Video Gallery'),
                            4    =>    array('content/tour_date',    'Tour Dates'),
                            5    =>    array('content/music_player',    'Music Player'),
                            6    =>    array('content/product',    'Shop'),
                        ) 
                    ),
            5    =>    array( '#',     'Fans', 'icon-th-list'    , 
                        array(
                            0    =>    array('fan/active',    'Active Fans'),
                            1    =>    array('fan/block',    'Blocked Fans')
                        ) 
                    ),
            6    =>    array( 'design/index',     'Design', 'icon-th-list'    ),
            7    =>    array( 'currency/index',     'Currency', 'icon-th-list'    ),
            8    =>    array( 'stats/index',     'Stats', 'icon-th-list'    ),
            9    =>    array( 'social/index',     'Social', 'icon-th-list'    ),
//            10    =>    array( '#', 'Attribute', 'icon-th-list', 
//                        array(
//                            0    =>    array('attribute/category',    'Category'),
//                            1    =>    array('attribute/color',    'Color'),
//                            2    =>    array('attribute/size',    'Size')
//                        ) 
//                    ),
            12    =>    array( 'auth/logout',     'Logout', 'icon-user'    ),
        ); 
    }

?>
		<!-- BEGIN SIDEBAR -->
		<div class="page-sidebar nav-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->        	
			<ul>
				<li>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler hidden-phone"></div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				<li>
					<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
					<form class="sidebar-search">
						<div class="input-box">
							<a href="javascript:;" class="remove"></a>
							<input type="text" placeholder="Search..." />
							<input type="button" class="submit" value=" " />
						</div>
					</form>
					<!-- END RESPONSIVE QUICK SEARCH FORM -->
				</li>
<?php
	
//	print_r($side_menus);exit;
	 foreach($side_menus as $item) {
	 	
		$cls1 = "";
		$cls2 = "";
		$sel1 = "";
		$lnk = "";
		
	 	if($item[0] == $curPage) {
	 		$cls1 = 'active';
	 		$sel1 = '<span class="selected"></span>';
	 		$lnk = site_url($item[0]);
	 	}else{
	 		$cls1 = '';
	 		$sel1 = '';
	 		if($item[0]=="#") {
	 			$lnk = "javascript:;";
	 			foreach ($item[3] as $subitem) {
	 				if($subitem[0] == $curPage) {
	 					$cls1 = 'active';
	 					$cls2 = 'open';
	 					$sel1 = '<span class="selected"></span>';
	 					break;
	 				}
	 			}
	 		}else{
	 			$lnk = site_url($item[0]);
	 		}
	 	}
	 	
	 	if(count($item)<4) {
				echo '<li class="'.$cls1.'">'.
							'<a href="'.$lnk.'">'.
							'<i class="'.$item[2].'"></i> '.
							'<span class="title">'.$item[1].'</span>'.
							$sel1.
							'</a>'.
						'</li>';
	 	}else{
	 		$cls1 .= " has-sub";
	 		echo '<li class="'.$cls1.'">'.
						'<a href="'.$lnk.'">'.
						'<i class="'.$item[2].'"></i>'.
						'<span class="title">'.$item[1].'</span>'.
	 					$sel1.
						'<span class="arrow '.$cls2.'"></span>'.
						'</a>'.
						'<ul class="sub">';
				 		foreach ($item[3] as $subitem) {
				 			$cls1 =	$subitem[0]==$curPage? 'class="active"' : '';
							echo '<li '.$cls1.'><a href="'.site_url($subitem[0]).'">'.$subitem[1].'</a></li>';
				 		}
						echo '</ul>'.
					'</li>';
	 	}
	 }
?>

			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
		<!-- END SIDEBAR -->
