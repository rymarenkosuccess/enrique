function call_api(_cmd, _data, _func) {
    var ajaxUrl = $("#ajax_control_url").val();
	$.ajax({
//        url : '/index.php/ajax/'+_cmd,
		url : ajaxUrl +'/'+_cmd,
		type : "post",
		//	contentType: "application/json",
		dataType : 'json',
		data : _data,
		cache: false,
		crossDomain:true,
		async: true,
		timeout:8000,
		success : _func,
		error : function(jqXHR, ajaxOptions, thrownError) {
			console.log("Getting Json Dat Error : ======");
			console.log(JSON.stringify(jqXHR));
			
			App.unblockUI(jQuery("#dashboard"));
			
			if(jqXHR.status === 0) {
				console.log('Not connect.\n Verify Network.');
			} else if(jqXHR.status == 404) {
				console.log('Requested page not found. [404]');
			} else if(jqXHR.status == 500) {
				console.log('Internal Server Error [500].');
			} else if(thrownError === 'parsererror') {
				console.log('Requested JSON parse failed.');
			} else if(thrownError === 'timeout') {
				console.log('Time out error.');
			} else if(thrownError === 'abort') {
				console.log('Ajax request aborted.');
			} else {
				console.log('Uncaught Error.\n' + jqXHR.responseText);
			}
		}
	});
}

var App = function () {
	
/* ============================================ */
	var g_range_time = {
		start: Date.today().add({days: -29 }),
		end: Date.today()
	};

/* ============================================*/
	
    var isIE8 = false; // IE8 mode
    var currentPage = ''; // current page

    // useful function to make equal height for contacts stand side by side
    var setEqualHeight = function(columns) { 
        var tallestColumn = 0;
        columns = jQuery(columns);
        columns.each(function() {
            var currentHeight = $(this).height();
            if (currentHeight > tallestColumn) {
                tallestColumn = currentHeight;
            }
        });
        columns.height(tallestColumn);
    }

    // this function handles responsive layout on screen size resize or mobile device rotate.
    var handleResponsive = function () {
        if (jQuery.browser.msie && jQuery.browser.version.substr(0, 1) == 8) {
            isIE8 = true; // checkes for IE8 browser version
            $('.visible-ie8').show(); //
        }

        var isIE10 = !! navigator.userAgent.match(/MSIE 10/);

        if (isIE10) {
            jQuery('html').addClass('ie10'); // set ie10 class on html element.
        }

        // loops all page elements with "responsive" class and applied classes for tablet mode
        // For metornic  1280px or less set as tablet mode to display the content properly
        var handleTabletElements = function () {
            if ($(window).width() <= 1280) {
                $(".responsive").each(function () {
                    var forTablet = $(this).attr('data-tablet');
                    var forDesktop = $(this).attr('data-desktop');
                    if (forTablet) {
                        $(this).removeClass(forDesktop);
                        $(this).addClass(forTablet);
                    }
                });
                handleTooltip();
            }
        }

        // loops all page elements with "responsive" class and applied classes for desktop mode
        // For metornic  higher 1280px set as desktop mode to display the content properly
        var handleDesktopElements = function () {
            if ($(window).width() > 1280) {
                $(".responsive").each(function () {
                    var forTablet = $(this).attr('data-tablet');
                    var forDesktop = $(this).attr('data-desktop');
                    if (forTablet) {
                        $(this).removeClass(forTablet);
                        $(this).addClass(forDesktop);
                    }
                });
                handleTooltip();
            }
        }

        // handle all elements which require to re-initialize on screen width change(on resize or on rotate mobile device)
        var handleElements = function () {
        	
            if (App.isPage("main/index")) {
                // handles for main page
            }
            
            if (App.isPage("maps_vector")) { // jqvector maps requires to fix the width on screen resized.
                jQuery('.vmaps').each(function () {
                    var map = jQuery(this);
                    map.width(map.parent().width());
                });
            }

            if ($(window).width() < 900) { // remove sidebar toggler
                $.cookie('sidebar-closed', null);
                $('.page-container').removeClass("sidebar-closed");
            }

            handleTabletElements();
            handleDesktopElements();
        }

        // handles responsive breakpoints.
        $(window).setBreakpoints({
            breakpoints: [320, 480, 768, 900, 1024, 1280]
        });

        $(window).bind('exitBreakpoint320', function () {
            handleElements();
        });
        $(window).bind('enterBreakpoint320', function () {
            handleElements();
        });

        $(window).bind('exitBreakpoint480', function () {
            handleElements();
        });
        $(window).bind('enterBreakpoint480', function () {
            handleElements();
        });

        $(window).bind('exitBreakpoint768', function () {
            handleElements();
        });
        $(window).bind('enterBreakpoint768', function () {
            handleElements();
        });

        $(window).bind('exitBreakpoint900', function () {
            handleElements();
        });
        $(window).bind('enterBreakpoint900', function () {
            handleElements();
        });

        $(window).bind('exitBreakpoint1024', function () {
            handleElements();
        });
        $(window).bind('enterBreakpoint1024', function () {
            handleElements();
        });

        $(window).bind('exitBreakpoint1280', function () {
            handleElements();
        });
        $(window).bind('enterBreakpoint1280', function () {
            handleElements();
        });
    }

    var handleTables = function () {
        if (!jQuery().dataTable) {
            return;
        }

        // begin first table
        $('#sample_1').dataTable({
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [0]
            }]
        });

        jQuery('#sample_1 .group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).attr("checked", true);
                } else {
                    $(this).attr("checked", false);
                }
            });
            jQuery.uniform.update(set);
        });

        jQuery('#sample_1_wrapper .dataTables_filter input').addClass("m-wrap medium"); // modify table search input
        jQuery('#sample_1_wrapper .dataTables_length select').addClass("m-wrap xsmall"); // modify table per page dropdown
    };
    
    var handleMainMenu = function () {
        jQuery('#alltime').click(function(){
           jQuery('.calendar-holder span').html('');
           document.location.href = $("#stats_url").val();
        });
        
        jQuery('.bx-pager-item').click(function(){
            var thumb_index = jQuery(this).find('a').text();
            jQuery(this).parent().find('.bx-pager-link').attr('class', 'bx-pager-link');
            jQuery(this).find('.bx-pager-link').attr('class', 'bx-pager-link active');
            jQuery(this).parent().parent().find('.product_thumbnail').hide();
            jQuery(this).parent().parent().find('.thumb'+thumb_index).show();
        })

        if(jQuery('a.fancybox').length){
            jQuery('a.fancybox').fancybox({
                'padding'        : 20,
                'scrolling'        : "no",
                'autoScale'        : false,
                'transitionIn'    : 'none',
                'transitionOut'    : 'none',
                'padding'         : 2,
                'width'            : 680,
                'height'        : 495,
                'href':"#fancybox_container"
                
            });
            jQuery('a.fancybox').click(function(e){
                $('video, audio').each(function() {
                      $(this)[0].player.pause();          
                });            
                var key = $(this).find('.video_info').val();
                jQuery('.video').hide();
                jQuery('#video_container_'+key).show();
            });
        }
        if(jQuery('a.fancybox_menu_add').length){
            jQuery('a.fancybox_menu_add').fancybox({
                'padding'        : 20,
                'scrolling'        : "no",
                'autoScale'        : false,
                'transitionIn'    : 'none',
                'transitionOut'    : 'none',
                'padding'         : 2,
                'width'            : 680,
                'height'        : 495,
                'href':"#fancybox_container_add"
            });
            jQuery('a.fancybox_menu_add').click(function(){
                jQuery('#fancybox_container_add .alter_name').val('');
                jQuery('#fancybox_container_add .ordering').val('');
                jQuery('#fancybox_container_add .is_publish').removeAttr('checked');
                jQuery('#fancybox_container_add .is_publish').parent().attr('class','');
            })
        }
        if(jQuery('a.fancybox_menu_edit').length){
            jQuery('a.fancybox_menu_edit').fancybox({
                'padding'        : 20,
                'scrolling'        : "no",
                'autoScale'        : false,
                'transitionIn'    : 'none',
                'transitionOut'    : 'none',
                'padding'         : 2,
                'width'            : 680,
                'height'        : 495,
                'href':"#fancybox_container_edit"
            });
            jQuery('a.fancybox_menu_edit').click(function(){
                var submenu_id = jQuery(this).parent().parent().find('.section_menu_id').val();
                jQuery("#fancybox_container_edit .section_menu_id").val(submenu_id);
                call_api("getMenuInfo",{'submenu_id':submenu_id}, function(data){
                    jQuery('#fancybox_container_edit .alter_name').val(data.alter_name);
                    jQuery('#fancybox_container_edit .ordering').val(data.ordering);
                    if(Number(data.is_publish)){
                        jQuery('#fancybox_container_edit .is_publish').attr('checked','checked');
                        jQuery('#fancybox_container_edit .is_publish').parent().attr('class','checked');
                    }else{
                        jQuery('#fancybox_container_edit .is_publish').removeAttr('checked');
                        jQuery('#fancybox_container_edit .is_publish').parent().attr('class','');
                     }
                } );
            })
        }
        jQuery('#fancybox_container_edit input.save_menu').click(function(){
            var submenu_id = jQuery("#fancybox_container_edit .section_menu_id").val();
            var alter_name = jQuery('#fancybox_container_edit .alter_name').val();
            var ordering = jQuery('#fancybox_container_edit .ordering').val();
            var is_publish = jQuery('#fancybox_container_edit .is_publish').is(":checked") ? 1 : 0;
            call_api("editSubmenu",{'alter_name':alter_name, 'ordering':ordering, 'is_publish':is_publish, 'submenu_id':submenu_id }, function(data){
                document.location.reload();
            })
        })
        jQuery('#fancybox_container_edit a.delete_menu').click(function(){
            var submenu_id = jQuery("#fancybox_container_edit .section_menu_id").val();
            call_api("deleteSubmenu",{'submenu_id':submenu_id }, function(data){
                document.location.reload();
            })
                
            
        });
        jQuery('#fancybox_container_add input.save_menu').click(function(){
            var alter_name = jQuery('#fancybox_container_add .alter_name').val();
            var ordering = jQuery('#fancybox_container_add .ordering').val();
            var section_id = jQuery('#fancybox_container_add .section_id').val();
            var is_publish = jQuery('#fancybox_container_add .is_publish').is(":checked") ? 1 : 0;
            call_api("addSubmenu",{'alter_name':alter_name, 'ordering':ordering, 'section_id':section_id, 'is_publish':is_publish }, function(data){
                document.location.reload();
            })
        })
        jQuery('.social_check').click(function(){
            $val = Number($(this).is(':checked'));
            $name = $(this).attr('name');
            call_api("setSocialValue", {'name':$name, 'value':$val});
            
        })
        jQuery('.video_tags_search').change(function(){
            var base_url = jQuery('#base_url').val();
            document.location.href = base_url + "/" + $(this).val();
        })
        jQuery('.photo_tags_search').change(function(){
            var base_url = jQuery('#base_url').val();
            document.location.href = base_url + "/" + $(this).val();
        })
        jQuery('.tourdate_search').change(function(){
            var base_url = jQuery('#base_url').val();
            document.location.href = base_url + "/" + $(this).val();
        })
        jQuery('.page-sidebar .has-sub > a').click(function () {

            var handleContentHeight = function() {
                var content = $('.page-content');
                var sidebar = $('.page-sidebar');

                if (!content.attr("data-height")) {
                    content.attr("data-height", content.height());
                }

                if (sidebar.height() > content.height()) {
                    content.height(sidebar.height() + 20);    
                } else {
                    content.height(content.attr("data-height"));
                }
            }

            var last = jQuery('.has-sub.open', $('.page-sidebar'));
            if (last.size() == 0) {
                //last = jQuery('.has-sub.active', $('.page-sidebar'));
            }
            last.removeClass("open");
            jQuery('.arrow', last).removeClass("open");
            jQuery('.sub', last).slideUp(200);

            var sub = jQuery(this).next();
            if (sub.is(":visible")) {
                jQuery('.arrow', jQuery(this)).removeClass("open");
                jQuery(this).parent().removeClass("open");
                sub.slideUp(200, function(){
                    handleContentHeight();
                });
            } else {
                jQuery('.arrow', jQuery(this)).addClass("open");
                jQuery(this).parent().addClass("open");
                sub.slideDown(200, function(){
                    handleContentHeight();
                });
            }
        });
    }

    var handleSidebarToggler = function () {

        var container = $(".page-container");

        if ($.cookie('sidebar-closed') == 1) {
            container.addClass("sidebar-closed");
        }

        // handle sidebar show/hide
        $('.page-sidebar .sidebar-toggler').click(function () {
            $(".sidebar-search").removeClass("open");
            var container = $(".page-container");
            if (container.hasClass("sidebar-closed") === true) {
                container.removeClass("sidebar-closed");
                $.cookie('sidebar-closed', null);
            } else {
                container.addClass("sidebar-closed");
                $.cookie('sidebar-closed', 1);
            }
        });

        // handle the search bar close
        $('.sidebar-search .remove').click(function () {
            $('.sidebar-search').removeClass("open");
        });

        // handle the search query submit on enter press
        $('.sidebar-search input').keypress(function (e) {
            if (e.which == 13) {
            //	window.location.href = "extra_search.html";
                return false; //<---- Add this line
            }
        });

        // handle the search submit
        $('.sidebar-search .submit').click(function () {
            if ($('.page-container').hasClass("sidebar-closed")) {
                if ($('.sidebar-search').hasClass('open') == false) {
                    $('.sidebar-search').addClass("open");
                } else {
                //    window.location.href = "extra_search.html";
                }
            } else {
            //	window.location.href = "extra_search.html";
            }
        });
    }

    var handlePortletTools = function () {
        jQuery('.portlet .tools a.remove').click(function () {
            var removable = jQuery(this).parents(".portlet");
            if (removable.next().hasClass('portlet') || removable.prev().hasClass('portlet')) {
                jQuery(this).parents(".portlet").remove();
            } else {
                jQuery(this).parents(".portlet").parent().remove();
            }
        });

        jQuery('.portlet .tools a.reload').click(function () {
            var el = jQuery(this).parents(".portlet");
            App.blockUI(el);
            window.setTimeout(function () {
                App.unblockUI(el);
            }, 1000);
        });

        jQuery('.portlet .tools .collapse, .portlet .tools .expand').click(function () {
            var el = jQuery(this).parents(".portlet").children(".portlet-body");
            if (jQuery(this).hasClass("collapse")) {
                jQuery(this).removeClass("collapse").addClass("expand");
                el.slideUp(200);
            } else {
                jQuery(this).removeClass("expand").addClass("collapse");
                el.slideDown(200);
            }
        });

        /*
        sample code to handle portlet config popup on close
        $('#portlet-config').on('hide', function (e) {
            //alert(1);
            //if (!data) return e.preventDefault() // stops modal from being shown
        });
        */            
    }

    var handleFancyBox = function () {

        if (!jQuery.fancybox) {
            return;
        }

        if (jQuery(".fancybox-button").size() > 0) {
            jQuery(".fancybox-button").fancybox({
                groupAttr: 'data-rel',
                prevEffect: 'none',
                nextEffect: 'none',
                closeBtn: true,
					type: "image",
            'scrolling'        : "no",
                helpers: {
                    title: {
                        type: 'inside'
                    }
                }
            });
        }
    }

    var handleFixInputPlaceholderForIE = function () {
        //fix html5 placeholder attribute for ie7 & ie8
        if (jQuery.browser.msie && jQuery.browser.version.substr(0, 1) <= 9) { // ie7&ie8
            jQuery('input[placeholder], textarea[placeholder]').each(function () {

                var input = jQuery(this);

                jQuery(input).val(input.attr('placeholder'));

                jQuery(input).focus(function () {
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                });

                jQuery(input).blur(function () {
                    if (input.val() == '' || input.val() == input.attr('placeholder')) {
                        input.val(input.attr('placeholder'));
                    }
                });
            });
        }
    }

    var handlePulsate = function () {
        if (!jQuery().pulsate) {
            return;
        }

        if (isIE8 == true) {
            return; // pulsate plugin does not support IE8 and below
        }

        if (jQuery().pulsate) {
            jQuery('#pulsate-regular').pulsate({
                color: "#bf1c56"
            });

            jQuery('#pulsate-once').click(function () {
                $(this).pulsate({
                    color: "#399bc3",
                    repeat: false
                });
            });

            jQuery('#pulsate-hover').pulsate({
                color: "#5ebf5e",
                repeat: false,
                onHover: true
            });

            jQuery('#pulsate-crazy').click(function () {
                $(this).pulsate({
                    color: "#fdbe41",
                    reach: 50,
                    repeat: 10,
                    speed: 100,
                    glow: true
                });
            });
        }
    }

    var handleIntro = function () {
        if ($.cookie('intro_show')) {
            return;
        }

        $.cookie('intro_show', 1);

        setTimeout(function () {
            var unique_id = $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'Meet Metronic!',
                // (string | mandatory) the text inside the notification
                text: 'Metronic is a brand new Responsive Admin Dashboard Template you have always been looking for!',
                // (string | optional) the image to display on the left
                image: '/assets/img/avatar1.jpg',
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: true,
                // (int | optional) the time you want it to be alive for before fading out
                time: '',
                // (string | optional) the class name you want to apply to that specific message
                class_name: 'my-sticky-class'
            });

            // You can have it return a unique id, this can be used to manually remove it later using
            setTimeout(function () {
                $.gritter.remove(unique_id, {
                    fade: true,
                    speed: 'slow'
                });
            }, 12000);
        }, 2000);

        setTimeout(function () {
            var unique_id = $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'Buy Metronic!',
                // (string | mandatory) the text inside the notification
                text: 'Metronic comes with a huge collection of reusable and easy customizable UI components and plugins. Buy Metronic today!',
                // (string | optional) the image to display on the left
                image: '/assets/img/avatar1.jpg',
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: true,
                // (int | optional) the time you want it to be alive for before fading out
                time: '',
                // (string | optional) the class name you want to apply to that specific message
                class_name: 'my-sticky-class'
            });

            // You can have it return a unique id, this can be used to manually remove it later using
            setTimeout(function () {
                $.gritter.remove(unique_id, {
                    fade: true,
                    speed: 'slow'
                });
            }, 13000);
        }, 8000);

        setTimeout(function () {

            $('#styler').pulsate({
                color: "#bb3319",
                repeat: 10
            });

            $.extend($.gritter.options, {
                position: 'top-left'
            });

            var unique_id = $.gritter.add({
                position: 'top-left',
                // (string | mandatory) the heading of the notification
                title: 'Customize Metronic!',
                // (string | mandatory) the text inside the notification
                text: 'Metronic allows you to easily customize the theme colors and layout settings.',
                // (string | optional) the image to display on the left
                image1: '/assets/img/avatar1.png',
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: true,
                // (int | optional) the time you want it to be alive for before fading out
                time: '',
                // (string | optional) the class name you want to apply to that specific message
                class_name: 'my-sticky-class'
            });

            $.extend($.gritter.options, {
                position: 'top-right'
            });

            // You can have it return a unique id, this can be used to manually remove it later using
            setTimeout(function () {
                $.gritter.remove(unique_id, {
                    fade: true,
                    speed: 'slow'
                });
            }, 15000);

        }, 23000);

        setTimeout(function () {

            $.extend($.gritter.options, {
                position: 'top-left'
            });

            var unique_id = $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'Notification',
                // (string | mandatory) the text inside the notification
                text: 'You have 3 new notifications.',
                // (string | optional) the image to display on the left
                image1: '/assets/img/image1.jpg',
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: true,
                // (int | optional) the time you want it to be alive for before fading out
                time: '',
                // (string | optional) the class name you want to apply to that specific message
                class_name: 'my-sticky-class'
            });

            setTimeout(function () {
                $.gritter.remove(unique_id, {
                    fade: true,
                    speed: 'slow'
                });
            }, 4000);

            $.extend($.gritter.options, {
                position: 'top-right'
            });

            var number = $('#header_notification_bar .badge').text();
            number = parseInt(number);
            number = number + 3;
            $('#header_notification_bar .badge').text(number);
            $('#header_notification_bar').pulsate({
                color: "#66bce6",
                repeat: 5
            });

        }, 40000);

        setTimeout(function () {

            $.extend($.gritter.options, {
                position: 'top-left'
            });

            var unique_id = $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'Inbox',
                // (string | mandatory) the text inside the notification
                text: 'You have 2 new messages in your inbox.',
                // (string | optional) the image to display on the left
                image1: '/assets/img/avatar1.jpg',
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: true,
                // (int | optional) the time you want it to be alive for before fading out
                time: '',
                // (string | optional) the class name you want to apply to that specific message
                class_name: 'my-sticky-class'
            });

            $.extend($.gritter.options, {
                position: 'top-right'
            });

            setTimeout(function () {
                $.gritter.remove(unique_id, {
                    fade: true,
                    speed: 'slow'
                });
            }, 4000);

            var number = $('#header_inbox_bar .badge').text();
            number = parseInt(number);
            number = number + 2;
            $('#header_inbox_bar .badge').text(number);
            $('#header_inbox_bar').pulsate({
                color: "#dd5131",
                repeat: 5
            });

        }, 60000);
    }

    var handleGritterNotifications = function () {
        if (!jQuery.gritter) {
            return;
        }
        $('#gritter-sticky').click(function () {
            var unique_id = $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'This is a sticky notice!',
                // (string | mandatory) the text inside the notification
                text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                // (string | optional) the image to display on the left
                image: '/assets/img/avatar1.jpg',
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: true,
                // (int | optional) the time you want it to be alive for before fading out
                time: '',
                // (string | optional) the class name you want to apply to that specific message
                class_name: 'my-sticky-class'
            });
            return false;
        });

        $('#gritter-regular').click(function () {

            $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'This is a regular notice!',
                // (string | mandatory) the text inside the notification
                text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                // (string | optional) the image to display on the left
                image: '/assets/img/avatar1.jpg',
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: false,
                // (int | optional) the time you want it to be alive for before fading out
                time: ''
            });

            return false;

        });

        $('#gritter-max').click(function () {

            $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'This is a notice with a max of 3 on screen at one time!',
                // (string | mandatory) the text inside the notification
                text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                // (string | optional) the image to display on the left
                image: '/assets/img/avatar1.jpg',
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: false,
                // (function) before the gritter notice is opened
                before_open: function () {
                    if ($('.gritter-item-wrapper').length == 3) {
                        // Returning false prevents a new gritter from opening
                        return false;
                    }
                }
            });
            return false;
        });

        $('#gritter-without-image').click(function () {
            $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'This is a notice without an image!',
                // (string | mandatory) the text inside the notification
                text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.'
            });

            return false;
        });

        $('#gritter-light').click(function () {

            $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'This is a light notification',
                // (string | mandatory) the text inside the notification
                text: 'Just add a "gritter-light" class_name to your $.gritter.add or globally to $.gritter.options.class_name',
                class_name: 'gritter-light'
            });

            return false;
        });

        $("#gritter-remove-all").click(function () {

            $.gritter.removeAll();
            return false;

        });
    }

    var handleTooltip = function () {
        if (App.isTouchDevice()) { // if touch device, some tooltips can be skipped in order to not conflict with click events
            jQuery('.tooltips:not(.no-tooltip-on-touch-device)').tooltip();
        } else {
            jQuery('.tooltips').tooltip();
        }
    }

    var handlePopover = function () {
        jQuery('.popovers').popover();
    }

    var handleChoosenSelect = function () {
        if (!jQuery().chosen) {
            return;
        }
        $(".chosen").chosen();
        $(".chosen-with-diselect").chosen({
            allow_single_deselect: true
        });
    }

    var handleUniform = function () {
        if (!jQuery().uniform) {
            return;
        }
        var test = $("input[type=checkbox]:not(.toggle), input[type=radio]:not(.toggle, .star)");
        if (test) {
            test.uniform();
        }
    }

    var initUniform = function (els) {
        jQuery(els).each(function(){
            if ($(this).parents(".checker").size() == 0) {
                $(this).show();
                $(this).uniform();
            }
        });
    }

    var handleWysihtml5 = function () {
    	/*
        if (!jQuery().wysihtml5) {
            return;
        }

        if ($('.wysihtml5').size() > 0) {
            $('.wysihtml5').wysihtml5();
        }
        */
//    	$("textarea").cleditor();
    }

    var handleToggleButtons = function () {
        if (!jQuery().toggleButtons) {
            return;
        }
        $('.basic-toggle-button').toggleButtons();
        $('.text-toggle-button').toggleButtons({
            width: 200,
            label: {
                enabled: "Lorem Ipsum",
                disabled: "Dolor Sit"
            }
        });
        $('.danger-toggle-button').toggleButtons({
            style: {
                // Accepted values ["primary", "danger", "info", "success", "warning"] or nothing
                enabled: "danger",
                disabled: "info"
            }
        });
        $('.info-toggle-button').toggleButtons({
            style: {
                enabled: "info",
                disabled: ""
            }
        });
        $('.success-toggle-button').toggleButtons({
            style: {
                enabled: "success",
                disabled: "info"
            }
        });
        $('.warning-toggle-button').toggleButtons({
            style: {
                enabled: "warning",
                disabled: "info"
            }
        });

        $('.height-toggle-button').toggleButtons({
            height: 100,
            font: {
                'line-height': '100px',
                'font-size': '20px',
                'font-style': 'italic'
            }
        });
    }

    var handleTagsInput = function () {
        if (!jQuery().tagsInput) {
            return;
        }
        $('#tags_1').tagsInput({
            width: 'auto',
            'onAddTag': function () {
                alert(1);
            },
        });
        $('#tags_2').tagsInput({
            width: 240
        });
    }     

    var handleDateTimePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker();
        }

        if (jQuery().timepicker) {
            $('.timepicker-default').timepicker();
            $('.timepicker-24').timepicker({
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false
            });
        }

        if (!jQuery().daterangepicker) {
            return;
        }

        $('.date-range').daterangepicker( 
        	{
	        	format: 'yyyy-MM-dd', 
	        	separator: ' to '
        	},
        	function (start, end) {
        		console.log("start="+start.toString('yyyy-MM-dd')+", end="+end.toString('yyyy-MM-dd'));
        	}
        );

        $('#dashboard-report-range').daterangepicker({
            ranges: {
                'Today': ['today', 'today'],
                'Yesterday': ['yesterday', 'yesterday'],
                'Last 7 Days': [Date.today().add({
                    days: -6
                }), 'today'],
                'Last 30 Days': [Date.today().add({
                    days: -29
                }), 'today'],
                'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
                'Last Month': [Date.today().moveToFirstDayOfMonth().add({
                    months: -1
                }), Date.today().moveToFirstDayOfMonth().add({
                    days: -1
                })]
            },
            opens: 'left',
            format: 'MM/dd/yyyy',
            separator: ' to ',
            startDate: Date.today().add({
                days: -29
            }),
            endDate: Date.today(),
            minDate: '01/01/2010',
            maxDate: '12/31/2094',
            locale: {
                applyLabel: 'Submit',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom Range',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            },
            showWeekNumbers: true,
            buttonClasses: ['btn-danger']
        },

        function (start, end) {          
            g_range_time.start = start;
            g_range_time.end = end;
            $('#dashboard-report-range span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString('MMMM d, yyyy'));

//            App.unblockUI(jQuery("#dashboard"));
//            App.scrollTo();
            start = start.getTime()/1000;
            end = end.getTime()/1000;
            end = end + 60*60*24;
            document.location.href = jQuery('#stats_url').val()+"/start_"+start+"/end_"+end;
        });

        $('#dashboard-report-range span').html(Date.today().add({
            days: -29
        }).toString('MMMM d, yyyy') + ' - ' + Date.today().toString('MMMM d, yyyy'));

        if($("#start_time").val()){
            var startObj = new Date($("#start_time").val()*1000);
            var endObj = new Date($("#end_time").val()*1000);
            $('#dashboard-report-range span').html(startObj.toString('MMMM d, yyyy') + ' - ' + endObj.toString('MMMM d, yyyy'));
        }

        if (!jQuery().datepicker || !jQuery().timepicker) {
            return;
        }
    }

    var handleClockfaceTimePickers = function () {

        if (!jQuery().clockface) {
            return;
        }

        $('#clockface_1').clockface();

        $('#clockface_2').clockface({
            format: 'HH:mm',
            trigger: 'manual'
        });

        $('#clockface_2_toggle-btn').click(function (e) {
            e.stopPropagation();
            $('#clockface_2').clockface('toggle');
        });

        $('#clockface_3').clockface({
            format: 'H:mm'
        }).clockface('show', '14:30');
    }

    var handleColorPicker = function () {
        if (!jQuery().colorpicker) {
            return;
        }
        $('.colorpicker-default').colorpicker({
            format: 'hex'
        });
        $('.colorpicker-rgba').colorpicker();
    }

    var handleAccordions = function () {
        $(".accordion").collapse().height('auto');

        var lastClicked;

        //add scrollable class name if you need scrollable panes
        jQuery('.accordion.scrollable .accordion-toggle').click(function () {
            lastClicked = jQuery(this);
        }); //move to faq section

        jQuery('.accordion.scrollable').on('shown', function () {
            jQuery('html,body').animate({
                scrollTop: lastClicked.offset().top - 150
            }, 'slow');
        });
    }

    var handleScrollers = function () {

        var setPageScroller = function () {
            $('.main').slimScroll({
                size: '12px',
                color: '#a1b2bd',
                height: $(window).height(),
                allowPageScroll: true,
                alwaysVisible: true,
                railVisible: true
            });
        }

        /*
        //if (isIE8 == false) {
            $(window).resize(function(){
               setPageScroller(); 
            });
            setPageScroller();
        //} else {
            $('.main').removeClass("main");
        //}
        */

        $('.scroller').each(function () {
            $(this).slimScroll({
                //start: $('.blah:eq(1)'),
                size: '7px',
                color: '#a1b2bd',
                height: $(this).attr("data-height"),
                alwaysVisible: ($(this).attr("data-always-visible") == "1" ? true : false),
                railVisible: ($(this).attr("data-rail-visible") == "1" ? true : false),
                disableFadeOut: true
            });
        });

    }

    var handleGoTop = function () {
        /* set variables locally for increased performance */
        jQuery('.footer .go-top').click(function () {
            App.scrollTo();
        });
    }

    var handleChat = function () {
        var cont = $('#chats');
        var list = $('.chats', cont);
        var form = $('.chat-form', cont);
        var input = $('input', form);
        var btn = $('.btn', form);

        var handleClick = function () {
            var text = input.val();
            if (text.length == 0) {
                return;
            }

            var time = new Date();
            var time_str = time.toString('MMM dd, yyyy HH:MM');
            var tpl = '';
            tpl += '<li class="out">';
            tpl += '<img class="avatar" alt="" src="/assets/img/avatar1.jpg"/>';
            tpl += '<div class="message">';
            tpl += '<span class="arrow"></span>';
            tpl += '<a href="#" class="name">Bob Nilson</a>&nbsp;';
            tpl += '<span class="datetime">at ' + time_str + '</span>';
            tpl += '<span class="body">';
            tpl += text;
            tpl += '</span>';
            tpl += '</div>';
            tpl += '</li>';

            var msg = list.append(tpl);
            input.val("");
            $('.scroller', cont).slimScroll({
                scrollTo: list.height()
            });
        }

        btn.click(handleClick);
        input.keypress(function (e) {
            if (e.which == 13) {
                handleClick();
                return false; //<---- Add this line
            }
        });
    }

    var handleStyler = function () {

        var panel = $('.color-panel');

        $('.icon-color', panel).click(function () {
            $('.color-mode').show();
            $('.icon-color-close').show();
        });

        $('.icon-color-close', panel).click(function () {
            $('.color-mode').hide();
            $('.icon-color-close').hide();
        });

        $('li', panel).click(function () {
            var color = $(this).attr("data-style");
            setColor(color);
            $('.inline li', panel).removeClass("current");
            $(this).addClass("current");
        });

        $('input', panel).change(function () {
            setLayout();
        });

        var setColor = function (color) {
            $('#style_color').attr("href", "/assets/css/style_" + color + ".css");
        }

        var setLayout = function () {
            if ($('input.header', panel).is(":checked")) {
                $("body").addClass("fixed-top");
                $(".header").addClass("navbar-fixed-top");
            } else {
                $("body").removeClass("fixed-top");
                $(".header").removeClass("navbar-fixed-top");
            }
        }
    }

    var handleFormWizards = function () {
        if (!jQuery().bootstrapWizard) {
            return;
        }

        // default form wizard
        $('#form_wizard_1').bootstrapWizard({
            'nextSelector': '.button-next',
            'previousSelector': '.button-previous',
            onTabClick: function (tab, navigation, index) {
                alert('on tab click disabled');
                return false;
            },
            onNext: function (tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                // set wizard title
                $('.step-title', $('#form_wizard_1')).text('Step ' + (index + 1) + ' of ' + total);
                // set done steps
                jQuery('li', $('#form_wizard_1')).removeClass("done");
                var li_list = navigation.find('li');
                for (var i = 0; i < index; i++) {
                    jQuery(li_list[i]).addClass("done");
                }

                if (current == 1) {
                    $('#form_wizard_1').find('.button-previous').hide();
                } else {
                    $('#form_wizard_1').find('.button-previous').show();
                }

                if (current >= total) {
                    $('#form_wizard_1').find('.button-next').hide();
                    $('#form_wizard_1').find('.button-submit').show();
                } else {
                    $('#form_wizard_1').find('.button-next').show();
                    $('#form_wizard_1').find('.button-submit').hide();
                }
                App.scrollTo($('.page-title'));
            },
            onPrevious: function (tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                // set wizard title
                $('.step-title', $('#form_wizard_1')).text('Step ' + (index + 1) + ' of ' + total);
                // set done steps
                jQuery('li', $('#form_wizard_1')).removeClass("done");
                var li_list = navigation.find('li');
                for (var i = 0; i < index; i++) {
                    jQuery(li_list[i]).addClass("done");
                }

                if (current == 1) {
                    $('#form_wizard_1').find('.button-previous').hide();
                } else {
                    $('#form_wizard_1').find('.button-previous').show();
                }

                if (current >= total) {
                    $('#form_wizard_1').find('.button-next').hide();
                    $('#form_wizard_1').find('.button-submit').show();
                } else {
                    $('#form_wizard_1').find('.button-next').show();
                    $('#form_wizard_1').find('.button-submit').hide();
                }

                App.scrollTo($('.page-title'));
            },
            onTabShow: function (tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                var $percent = (current / total) * 100;
                $('#form_wizard_1').find('.bar').css({
                    width: $percent + '%'
                });
            }
        });

        $('#form_wizard_1').find('.button-previous').hide();
        $('#form_wizard_1 .button-submit').click(function () {
            alert('Finished! Hope you like it :)');
        }).hide();
    }

    return {

        //main function to initiate template pages
        init: function () {
            handleResponsive(); // set and handle responsive
            // page level handlers
            if (App.isPage("main/index")) {
                // handles main page
            }

            if (App.isPage("auth/index")) {
                handleTables(); // handles data tables
            }

            // global handlers
            handleChoosenSelect(); // handles bootstrap chosen dropdowns
            handleScrollers(); // handles slim scrolling contents
            handleUniform(); // handles uniform elements
            handleTagsInput() // handles tag input elements
            handleDateTimePickers(); //handles form timepickers
            handleClockfaceTimePickers(); //handles form clockface timepickers
            handleColorPicker(); // handles form color pickers            
            handlePortletTools(); // handles portlet action bar functionality(refresh, configure, toggle, remove)
            handlePulsate(); // handles pulsate functionality on page elements
            handleGritterNotifications(); // handles gritter notifications
            handleTooltip(); // handles bootstrap tooltips
            handlePopover(); // handles bootstrap popovers
            handleToggleButtons(); // handles form toogle buttons
            handleWysihtml5(); //handles WYSIWYG Editor           
            handleFancyBox(); // handles fancy box image previews
            handleStyler(); // handles style customer tool
            handleMainMenu(); // handles main menu
            handleSidebarToggler() // handles sidebar hide/show
            handleFixInputPlaceholderForIE(); // fixes/enables html5 placeholder attribute for IE9, IE8
            handleGoTop(); //handles scroll to top functionality in the footer
            handleAccordions(); //handles accordions
            handleFormWizards(); // handles form wizards
        },

        // wrapper function for page element pulsate
        pulsate: function (el, options) {
            var opt = jQuery.extend(options, {
                color: '#d12610', // set the color of the pulse
                reach: 15, // how far the pulse goes in px
                speed: 300, // how long one pulse takes in ms
                pause: 0, // how long the pause between pulses is in ms
                glow: false, // if the glow should be shown too
                repeat: 1, // will repeat forever if true, if given a number will repeat for that many times
                onHover: false // if true only pulsate if user hovers over the element
            });
            jQuery(el).pulsate(opt);
        },

        plotWithOptions3 : function(usages) {
                var chart = [];
                var label = [];
                var obj = $('#usagebydate');
                if(usages.result != 'empty'){
                    for(var key in usages){
                        val = usages[key].gametime;
                        chart.push([key, val]);
                        label.push([key,usages[key].label]);
                    }
                }

                var plot = $.plot(obj, [{data: chart}], {
                    series: {
                        lines: {
                            show: true,
                            lineWidth: 2,
                            fill: true,
                            fillColor: {
                                colors: [{
                                    opacity: 0.05
                                }, {
                                    opacity: 0.01
                                }]
                            }
                        },
                        points: {
                            show: true
                        },
                        shadowSize: 2
                    },
                    grid: {
                        hoverable: true,
                        clickable: true,
                        tickColor: "#eee",
                        borderWidth: 0
                    },
                    colors: ["#d12610", "#37b7f3", "#52e136"],
                    xaxis: {
                        ticks: label,
                        tickDecimals: 0
                    },
                    yaxis: {
                        ticks: 11,
                        tickDecimals: 0
                    }
                });
//            $.plot(obj, [chart], {
//                series: {
//                    stack: stack,
//                    lines: {
//                        show: lines,
//                        fill: true,
//                        steps: steps
//                    },
//                    bars: {
//                        show: true,
//                        align: "center",
//                        barWidth: 0.2
//                    }
//                },
//                xaxis: {
//                    ticks: label
//                }
//                
//            });
        },

        // wrapper function to scroll to an element
        scrollTo: function (el, offeset) {
            pos = el ? el.offset().top : 0;
            jQuery('html,body').animate({
                scrollTop: pos + (offeset ? offeset : 0)
            }, 'slow');
        },

        // wrapper function to  block element(indicate loading)
        blockUI: function (el, loaderOnTop) {
            lastBlockedUI = el;
            jQuery(el).block({
                message: '<img src="/assets/img/loading.gif" align="absmiddle">',
                css: {
                    border: 'none',
                    padding: '2px',
                    backgroundColor: 'none'
                },
                overlayCSS: {
                    backgroundColor: '#000',
                    opacity: 0.05,
                    cursor: 'wait'
                }
            });
        },

        // wrapper function to  un-block element(finish loading)
        unblockUI: function (el) {
            jQuery(el).unblock({
                onUnblock: function () {
                    jQuery(el).removeAttr("style");
                }
            });
        },

        // public method to initialize uniform inputs
        initFancybox : function() {
            handleFancyBox();
        },

        initUniform : function(el) {
            initUniform(el);
        },

        // set map page
        setPage: function (name) {
            currentPage = name;
        },

        // check current page
        isPage: function (name) {
            return currentPage == name ? true : false;
        },

        // check for device touch support
        isTouchDevice: function () {
            try {
                document.createEvent("TouchEvent");
                return true;
            } catch (e) {
                return false;
            }
        }

    };

}();
function addMoreQuestion(){
    var qcount = $("#qcount").val();
    
    var contentHTML = '<div class="row-fluid">'+
                                '<div class="span6">'+
                                    '<div class="control-group">'+
                                       '<div class="controls customarea">'+
                                            '<textarea name="question['+qcount+']" cols="40" rows="10" class="large m-wrap"></textarea>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="span6">'+
                                    '<table class="table answer">'+
                                        '<tr>'+
                                            '<td>'+
                                                '<input type="text" name="answer['+qcount+'][]" class="large m-wrap" >'+
                                            '</td>'+
                                            '<td class="center" width="90px">'+
                                                '<span><input type="checkbox" name="is_correct_'+qcount+'_0" ></span>'+
                                            '</td>'+
                                        '</tr>'+
                                        '<tr>'+
                                            '<td>'+
                                                '<input type="text" name="answer['+qcount+'][]" class="large m-wrap" >'+
                                            '</td>'+
                                            '<td class="center" width="90px">'+
                                                '<span><input type="checkbox" name="is_correct_'+qcount+'_1" ></span>'+
                                            '</td>'+
                                        '</tr>'+
                                        '<tr>'+
                                            '<td>'+
                                                '<input type="text" name="answer['+qcount+'][]" class="large m-wrap" >'+
                                            '</td>'+
                                            '<td class="center" width="90px">'+
                                                '<span><input type="checkbox" name="is_correct_'+qcount+'_2" ></span>'+
                                            '</td>'+
                                        '</tr>'+
                                        '<tr>'+
                                            '<td>'+
                                                '<input type="text" name="answer['+qcount+'][]" class="large m-wrap" >'+
                                            '</td>'+
                                            '<td class="center" width="90px">'+
                                                '<span><input type="checkbox" name="is_correct_'+qcount+'_3" ></span>'+
                                            '</td>'+
                                        '</tr>'+
                                    '</table>'+
                                '</div>'+
                            '</div>';
    $("#qcount").val(Number(qcount)+1);
    $("#question_container").append(contentHTML);

}
function addMoreQuizQuestion(){
    var qcount = $("#qcount").val();
    
    var contentHTML = '<div class="row-fluid">'+
                                '<div class="span6">'+
                                    '<div class="control-group">'+
                                       '<div class="controls customarea">'+
                                            '<textarea name="question['+qcount+']" cols="40" rows="10" class="large m-wrap"></textarea>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="span6">'+
                                    '<table class="table answer">'+
                                        '<tr>'+
                                            '<td>'+
                                                '<input type="text" name="answer['+qcount+'][]" class="large m-wrap" >'+
                                            '</td>'+
                                        '</tr>'+
                                        '<tr>'+
                                            '<td>'+
                                                '<input type="text" name="answer['+qcount+'][]" class="large m-wrap" >'+
                                            '</td>'+
                                        '</tr>'+
                                        '<tr>'+
                                            '<td>'+
                                                '<input type="text" name="answer['+qcount+'][]" class="large m-wrap" >'+
                                            '</td>'+
                                        '</tr>'+
                                        '<tr>'+
                                            '<td>'+
                                                '<input type="text" name="answer['+qcount+'][]" class="large m-wrap" >'+
                                            '</td>'+
                                        '</tr>'+
                                    '</table>'+
                                '</div>'+
                            '</div>';
    $("#qcount").val(Number(qcount)+1);
    $("#question_container").append(contentHTML);

}