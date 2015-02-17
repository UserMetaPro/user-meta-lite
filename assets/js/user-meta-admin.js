
function pfToggleMetaBox(toggleIcon) {
    jQuery(toggleIcon).parents('.postbox').children('.inside').toggle();
    
    if (jQuery(toggleIcon).parents('.postbox').hasClass('closed')) {
        jQuery(toggleIcon).parents('.postbox').removeClass("closed");
    } else {
        jQuery(toggleIcon).parents('.postbox').addClass("closed");
    }
}

function pfRemoveMetaBox(removeIcon) {
    if (confirm('Confirm to remove?')) {
        jQuery(removeIcon).parents('.postbox').parents('.meta-box-sortables').remove();
    }    
}

function umNewField(element) {
    newID = parseInt(jQuery("#last_id").val()) + 1;
    arg = 'id=' + newID + '&field_type=' + jQuery(element).attr('field_type');
    pfAjaxCall(element, 'um_add_field', arg, function(data) {
        jQuery("#um_fields_container").append(data);
        jQuery("#last_id").val(newID);
    });
}

function umUpdateField(element) {
    if (!jQuery(element).validationEngine("validate")) return;
    
    bindElement = jQuery(".pf_save_button");
    bindElement.parent().children(".pf_ajax_result").remove();
    arg = jQuery( element ).serialize();
    pfAjaxCall(bindElement, 'um_update_field', arg, function(data) {
        bindElement.after("<div class='pf_ajax_result'>"+data+"</div>");        
    });
}

function umChangeField(element, fieldID) {
    arg = jQuery( "#field_" + fieldID + " *" ).serialize();
    pfAjaxCall(element, "um_change_field", arg, function(data) {
        jQuery(element).parents(".meta-box-sortables").replaceWith(data);
    });
}

function umChangeFieldTitle(element) {
    title = jQuery(element).val();
    if (!title){ title = 'Untitled Field'; }
    jQuery(element).parents(".postbox").children("h3").children(".um_admin_field_title").text(title);
}

function umUpdateMetaKey(element) {
    if (jQuery(element).parents(".postbox").find(".um_meta_key_editor").length) {
        if (!jQuery(element).parents(".postbox").find(".um_meta_key_editor").val()) {
            title = jQuery(element).parents(".postbox").find(".um_field_title_editor").val();
            meta_key = title.trim().toLowerCase().replace(/[^a-z0-9 ]/g,'').replace(/\s+/g,'_');
            jQuery(element).parents(".postbox").find(".um_meta_key_editor").val(meta_key);
        }
    }
}

function umNewForm(element) {
    newID = parseInt(jQuery("#form_count").val()) + 1;
    pfAjaxCall(element, 'um_add_form', 'id='+newID, function(data) {
        jQuery("#um_fields_container").append(data);
        jQuery("#form_count").val(newID);
        
        jQuery('.um_dropme').sortable({
            connectWith: '.um_dropme',
            cursor: 'pointer'
        }).droppable({
            accept: '.postbox',
            activeClass: 'um_highlight'
        });
    });
}

function umUpdateForms(element) {
    if (!jQuery(element).validationEngine("validate")) return;
    
    jQuery(".um_selected_fields").each(function(index) {
        var length = jQuery(this).children(".postbox").size();
        n = index + 1;
        jQuery("#field_count_" + n).val(length); 
    });

    bindElement = jQuery(".pf_save_button");
    bindElement.parent().children(".pf_ajax_result").remove();
    arg = jQuery(element).serialize();
    pfAjaxCall(bindElement, 'um_update_forms', arg, function(data) {
        bindElement.after("<div class='pf_ajax_result'>"+data+"</div>");
    });
}

function umChangeFormTitle(element) {
    title = jQuery(element).val();
    if (!title){title = 'Untitled Form';}
    jQuery(element).parents(".postbox").children("h3").text(title);
}

function umAuthorizePro(element) {
    if (!jQuery(element).validationEngine("validate")) return;
    
    arg = jQuery(element).serialize();
    bindElement = jQuery("#authorize_pro");
    pfAjaxCall(bindElement, 'um_update_settings', arg, function(data) {
        bindElement.parent().children(".pf_ajax_result").remove();
        bindElement.after("<div class='pf_ajax_result'>"+data+"</div>");
    });    
}

function umWithdrawLicense(element) {
    bindElement = jQuery(element);
    arg = "method_name=withdrawLicense";
    bindElement.parent().children(".pf_ajax_result").remove();
    pfAjaxCall(bindElement, 'pf_ajax_request', arg, function(data) {
        bindElement.after("<div class='pf_ajax_result'>"+data+"</div>");
    });     
}

function umUpdateSettings(element) {
    bindElement = jQuery("#update_settings");
    
    jQuery(".um_selected_fields").each(function(index){
        var length = jQuery(this).children(".postbox").size();
        n = index + 1;
        jQuery("#field_count_" + n).val( length ); 
        
    });    
    
    arg = jQuery( element ).serialize();
    pfAjaxCall(bindElement, 'um_update_settings', arg, function(data) {
        bindElement.parent().children(".pf_ajax_result").remove();
        bindElement.after("<div class='pf_ajax_result'>"+data+"</div>");
    });
}

// Get Pro Message in admin section
function umGetProMessage( element ){
    alert(user_meta.get_pro_link);
}

// Toggle custom field in Admin Import Page
function umToggleCustomField(element) {
    if (jQuery(element).val() == 'custom_field' )
        jQuery(element).parent().children(".um_custom_field").fadeIn();
    else
        jQuery(element).parent().children(".um_custom_field").fadeOut();
}

/**
 * Export and Import
 */

var umAjaxRequest;

function umUserImportDialog(element) {
    jQuery("#import_user_dialog").html( '<center>' + user_meta.please_wait + '</center>' );
    jQuery("#dialog:ui-dialog").dialog("destroy");
	jQuery("#import_user_dialog").dialog({
		modal: true,
        beforeClose: function(event, ui) {
            umAjaxRequest.abort();
            jQuery(".pf_loading").remove();
        },
		buttons: {
			Cancel: function() {
				jQuery( this ).dialog( "close" );
			}
		}
	});   
    umUserImport( element, 0, 1 );  
}

function umUserImport(element, file_pointer, init) {
    arg = jQuery( element ).serialize();    
    arg = arg + '&step=import&file_pointer=' + file_pointer;
    if ( init ) arg = arg + '&init=1';
    pfAjaxCall( element, 'um_user_import', arg, function(data){
        jQuery( "#import_user_dialog" ).html( data );
        if ( jQuery(data).attr('do_loop') == 'do_loop' ){
            umUserImport( element, jQuery(data).attr('file_pointer') );
        } 
    });
}

function umUserExport(element, type) {
    var arg = jQuery( element ).parent("form").serialize();
    arg = arg.replace(/\(/g, "%28").replace(/\)/g, "%29");//Replace "()"
    var field_count = jQuery( element ).parent("form").children(".um_selected_fields").children(".postbox").size();
        
    arg = arg + "&action_type=" + type + "&field_count=" + field_count;
       
    if ( type == 'export' || type == 'save_export' ) {
        document.location.href = ajaxurl + "?action=pf_ajax_request&" + arg;
    }else if( type == 'save' ){
        pfAjaxCall( element, 'pf_ajax_request', arg, function(data){
            alert('Form saved');
        });          
    }
}

function umNewUserExportForm(element) {
    var formID = jQuery("#new_user_export_form_id").val();
    incID = formID + 1;
    jQuery("#new_user_export_form_id").val( parseInt(formID) + 1 );  
    
    arg = 'method_name=userExportForm&form_id=' + formID;
    
    pfAjaxCall( element, 'pf_ajax_request', arg, function(data){
        jQuery(element).before(data);        
        
        jQuery('.um_dropme').sortable({
            connectWith: '.um_dropme',
            cursor: 'pointer'
        }).droppable({
            accept: '.postbox',
            activeClass: 'um_highlight'
        });  
        jQuery(".um_date").datepicker({ dateFormat: 'yy-mm-dd', changeYear: true });
    });    
}

function umAddFieldToExport(element) {
    var metaKey = jQuery(element).parent().children(".um_add_export_meta_key").val();
    if(metaKey){
        var button  = '<div class="postbox">Title:<input type="text" style="width:50%" name="fields['+metaKey+']" value="'+metaKey+'" /> ('+metaKey+')</div>';
        jQuery(element).parents("form").children(".um_selected_fields").append(button);
    }else{
        alert( 'Please provide Meta Key.' );
    }
}

function umDragAllFieldToExport(element) {
    jQuery(element).parents("form").children(".um_selected_fields").append(
        jQuery(element).parents("form").children(".um_availabele_fields").html()
    );
    jQuery(element).parents("form").children(".um_availabele_fields").empty()
}

function umRemoveFieldToExport(element, formID) {
    if( confirm( "This form will removed permanantly. Confirm to Remove?" ) ){ 
        var arg = 'method_name=RemoveExportForm&form_id=' + formID;
        pfAjaxCall( element, 'pf_ajax_request', arg, function(data){

        });  
        jQuery( element ).parents(".meta-box-sortables").hide('slow').empty();
    }
}

function umToggleVisibility(condition, result, reverse) {
    reverse = typeof reverse == 'undefined' ? true : false;
    val = jQuery(condition).val();
    val = reverse ? !val : val;
    val ? jQuery(result).fadeIn() : jQuery(result).fadeOut();
}

function umSettingsRegistratioUserActivationChange() {
    var userActivationType = jQuery('.um_registration_user_activation:checked').val();
    if( userActivationType == 'auto_active' ){
        jQuery('#um_settings_registration_block_2').hide();
        jQuery('#um_settings_registration_block_1').fadeIn();
    }else if( userActivationType == 'email_verification' ){
        jQuery('#um_settings_registration_block_1').hide();
        jQuery('#um_settings_registration_block_2').fadeIn();
    }else if( userActivationType == 'admin_approval' ){
        jQuery('#um_settings_registration_block_1').hide();
        jQuery('#um_settings_registration_block_2').hide();
    }else if( userActivationType == 'both_email_admin' ){
        jQuery('#um_settings_registration_block_1').hide();
        jQuery('#um_settings_registration_block_2').fadeIn();
    }
}

function umSettingsToggleCreatePage() {
    umToggleVisibility('#um_login_login_page', '#um_login_login_page_create');
    umToggleVisibility('#um_login_login_page', '#um_login_disable_wp_login_php_block', false);
    
    umToggleVisibility('#um_registration_email_verification_page', '#um_registration_email_verification_page_create');
    umToggleVisibility('#um_login_resetpass_page', '#um_login_resetpass_page_create');
}

function umSettingsToggleError() {
   umToggleVisibility('#um_registration_email_verification_page', '.um_required_email_verification_page');
   
    showError = false;
    if( jQuery('#um_login_disable_wp_login_php:checked').val() ){
        if( ! jQuery('#um_login_resetpass_page').val() )
            showError = true;
    }
    if( showError )
        jQuery('.um_required_resetpass_page_page').fadeIn();
    else
        jQuery('.um_required_resetpass_page_page').fadeOut();
}