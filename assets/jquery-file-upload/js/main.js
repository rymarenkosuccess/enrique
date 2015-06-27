/*
 * jQuery File Upload Plugin JS Example 7.0
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*jslint nomen: true, unparam: true, regexp: true */
/*global $, window, document */

$(function () {
    'use strict';
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
//             Uncomment the following to send cross-domain cookies:
//            xhrFields: {withCredentials: true},
        url: $("#ajaxuploadproductphoto").val(),
        autoUpload : true
        
    })
    $('#fileupload').bind('fileuploadchange', function (e) {
        if($('[name="photos[]"]').length >= 3 ){
            alert("Unable to upload 3 more images.");
            return false;
        }
    })
    var is_photo = false;
    $('#videoPhotoUpload').click(function(){
        is_photo = true;
    })
    $('#videofileupload').submit(function(){
        $('#video_img_value').val($('.fileupload-preview img').attr('src'));
        return true;
    })
    $('#videofileupload').fileupload({
//             Uncomment the following to send cross-domain cookies:
//            xhrFields: {withCredentials: true},
        url: $("#ajaxuploadvideo").val(),
        autoUpload : true
        
    })
    $('#videofileupload').bind('fileuploadchange', function (e) {
        if(is_photo){
            is_photo = false;
            return false;
        }
        if($('[name="destination"]').length >= 1 ){
            return false;
        }
    })

    $('#musicfileupload').fileupload({
        url: $("#ajaxuploadmusic").val(),
        autoUpload : true
        
    })
    $('#musicfileupload').bind('fileuploadchange', function (e) {
        if(is_photo){
            is_photo = false;
            return false;
        }
        if($('[name="destination"]').length >= 1 ){
            return false;
        }
    })

});
