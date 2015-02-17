
var umAjaxRequest;

function pfAjaxCall(element, action, arg, handle) {
    if (action) data = "action=" + action;
    if (arg)    data = arg + "&action=" + action;
    if (arg && !action) data = arg;
    
    var n = data.search("pf_nonce");
    if (n<0) {
        data = data + "&pf_nonce=" + pf_nonce;
    }
        
    //data = data + "&pf_nonce=" + pf_nonce + "&is_ajax=true";    
    data = data + "&is_ajax=true";
    //if( typeof(ajaxurl) == 'undefined' ) ajaxurl = front.ajaxurl;

    umAjaxRequest = jQuery.ajax({
    type: "post",
    url: ajaxurl,
    data: data,
        beforeSend: function() { jQuery("<span class='pf_loading'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>").insertAfter(element); },
        success: function( data ){
            jQuery(".pf_loading").remove();
            handle(data);
        }
    });    
}

function pfAjaxRequest(element) {  
    bindElement = jQuery(element);
    bindElement.parent().children(".pf_ajax_result").remove();
    arg = jQuery( element ).serialize();
    pfAjaxCall( bindElement, 'pf_ajax_request', arg, function(data) {
        bindElement.after("<div class='pf_ajax_result'>"+data+"</div>");        
    });    
     
}

function umInsertUser(element) {
    if (typeof(tinyMCE) !== 'undefined') tinyMCE.triggerSave();
    if (!jQuery(element).validationEngine("validate")) return;
    
    bindElement = jQuery(element);
    bindElement.children(".pf_ajax_result").remove();
    arg = jQuery( element ).serialize();
    pfAjaxCall( bindElement, 'pf_ajax_request', arg, function(data) {
        if (jQuery(data).attr('action_type') == 'registration')
            jQuery(element).replaceWith(data);
        else
            bindElement.append("<div class='pf_ajax_result'>"+data+"</div>");        
    });    
}

function umLogin(element) {
    //if( !jQuery(element).validationEngine("validate") ) return;
        
    arg = jQuery( element ).serialize();
    bindElement = jQuery(element);
    bindElement.children(".pf_ajax_result").remove();
    pfAjaxCall(bindElement, 'pf_ajax_request', arg, function(data) {
        if ( jQuery(data).attr('status') == 'success' ) {
            //jQuery(element).replaceWith(data); //Commented from 1.1.5rc2, not showing anything while redirecting
            redirection = jQuery(data).attr('redirect_to');
            if ( redirection )
                window.location.href = redirection;
        }
        else
            bindElement.append("<div class='pf_ajax_result'>"+data+"</div>");
    });      
}

function umLogout(element) {
    arg = 'action_type=logout';
    
    pfAjaxCall(element, 'um_login', arg, function(data) {
        //alert(data);
        //jQuery("#" + jQuery() )
        jQuery(element).after(data);
        //jQuery(element).parents(".error").remove();    
    });    
}

function umPageNavi(pageID, isNext, element) {
    var haveError = false;
    
    if (typeof element == 'object')
        formID = "#" + jQuery(element).parents("form").attr("id");
    else
        formID = "#" + element;

    if (isNext) {
        checkingPage = parseInt(pageID) - 1;
        
        jQuery( formID + " #um_page_segment_" + checkingPage + " .um_input" ).each(function(){
            fieldID = jQuery(this).attr("id");  
            error = jQuery("#" + fieldID).validationEngine( "validate" );      
            if( error )
                haveError = true;           
        });

        // Not in use
        // Checking every um_unique class for error. (validateField not working for ajax)
        jQuery("#um_page_segment_" + checkingPage + " .um_unique").each(function(index){
            id = jQuery(this).attr("id");
            value = jQuery(this).attr("value");
        	jQuery.ajax({
        		type: "post",
                url: ajaxurl,
                //dataType: "json",
                async: false,
                data: "action=um_validate_unique_field&customCheck=ok&fieldId="+id+"&fieldValue=" + value,
        		success: function( data ){
                    if( data == "error" )
                        haveError = data;
        		}
        	});  
        });               
    } else
        jQuery(formID).validationEngine("hide");
    
    if ( haveError ) return false;
    
    jQuery(formID).children(".um_page_segment").hide();
    jQuery(formID).children("#um_page_segment_" + pageID ).fadeIn('slow');    
}

function umFileUploader(uploadScript) {
    jQuery(".um_file_uploader_field").each(function(index) {

        var divID = jQuery(this).attr("id");
        var fieldID = jQuery(this).attr("um_field_id");
        
        allowedExtensions = jQuery(this).attr("extension");
        allowedExtensions = allowedExtensions.replace(/\s+/g,"");
        maxSize = jQuery(this).attr("maxsize")
        if ( !allowedExtensions )
            allowedExtensions = "jpg,jpeg,png,gif";
        if ( !maxSize )
            maxSize = 1 * 1024 * 1024;            

        var uploader = new qq.FileUploader({
            // pass the dom node (ex. $(selector)[0] for jQuery users)
            element: document.getElementById(divID),
            // path to server-side upload script
            action: uploadScript,
            params: {"field_name":jQuery(this).attr("name"), field_id:fieldID, "pf_nonce":pf_nonce },
            allowedExtensions: allowedExtensions.split(","),
            sizeLimit: maxSize,
            onComplete: function(id, fileName, responseJSON){
                if( !responseJSON.success ) return;
                
                // responseJSON comes from uploader.php return
                handle = jQuery('#'+fieldID);
                arg = 'field_name=' + responseJSON.field_name + '&filepath=' + responseJSON.filepath + '&field_id=' + fieldID;

                // Check if it is used by User Import Upload
                if ( responseJSON.field_name == 'txt_upload_ump_import' ) {
                    arg = arg + '&method_name=ImportUmp';
                    pfAjaxCall( handle, 'pf_ajax_request', arg, function(data){
                        jQuery('#'+fieldID+'_result').empty().append( data );      
                    });                                     
                } else if ( responseJSON.field_name == 'csv_upload_user_import' ) {
                    arg = arg + '&step=one';                   
                    pfAjaxCall( handle, 'um_user_import', arg, function(data){
                        //jQuery('#'+fieldID+'_result').empty().append( data );   
                        jQuery(handle).parents(".meta-box-sortables").replaceWith(data);    
                    });                    
                } else {
                    pfAjaxCall( handle, 'um_show_uploaded_file', arg, function(data) {
                        jQuery('#'+divID+'_result').empty().append( data );       
                    });                    
                }                
                
                

            }
        });         
    });
}


function umShowImage(element) {
    url = jQuery(element).val();
    if (!url) {
        jQuery(element).parents(".um_field_container").children(".um_field_result").empty();
        return;
    }
    
    arg = 'showimage=true&imageurl=' + url;
    pfAjaxCall( element, 'um_show_uploaded_file', arg, function(data){
        jQuery(element).parents(".um_field_container").children(".um_field_result").empty().append(data);     
    });
}
  
  
function umRemoveFile(element) {
    if (confirm("Confirm to remove? ")) {
        fieldName = jQuery(element).attr("name");
        jQuery(element).parents(".um_field_result").empty().append("<input type='hidden' name='"+fieldName+"' value='' />");         
    }   
}    

function umUpgradeFromPrevious(element) {
    arg = 'typess=xx';
    pfAjaxCall( element, 'um_common_request', arg, function(data) {
        jQuery(element).parents(".error").remove();    
    }); 
}

function umRedirection(element) {
    var arg = jQuery( element ).parent("form").serialize();       
    document.location.href = ajaxurl + "?action=pf_ajax_request&" + arg;  
}

function umConditionalRequired(field, rules, i, options) {
    var baseField = field.attr('id').split('_');
    baseField.pop();
    baseField = baseField.join('_');
 
    if (jQuery('#' + baseField).val().length > 0 && field.val().length == 0)
        rules.push('required'); 
}