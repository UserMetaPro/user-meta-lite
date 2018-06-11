<?php
namespace UserMeta;

class AdminAjaxController
{

    function __construct()
    {
        add_action('wp_ajax_um_add_field', array(
            $this,
            'ajaxAddField'
        ));
        add_action('wp_ajax_um_add_form_field', array(
            $this,
            'ajaxAddFormField'
        ));
        add_action('wp_ajax_um_change_field', array(
            $this,
            'ajaxChangeField'
        ));
        add_action('wp_ajax_um_update_field', array(
            $this,
            'ajaxUpdateFields'
        ));
        
        add_action('wp_ajax_um_add_form', array(
            $this,
            'ajaxAddForm'
        ));
        add_action('wp_ajax_um_update_forms', array(
            $this,
            'ajaxUpdateForms'
        ));
        
        /**
         * Add wp_ajax_user_meta_ajax action hook
         */
        (new RouteResponse())->initHooks();
    }

    function ajaxAddField()
    {
        global $userMeta;
        $userMeta->verifyAdminNonce('add_field');
        
        if (empty($_POST['id']))
            die();
        
        if (! empty($_POST['field_type'])) {
            $arg = $_POST;
            $arg['is_new'] = true;
            $fieldBuilder = new FieldBuilder($arg);
            $fieldBuilder->setEditor('fields_editor');
            echo $fieldBuilder->buildPanel();
        }
        
        die();
    }

    function ajaxAddFormField()
    {
        global $userMeta;
        $userMeta->verifyAdminNonce('add_field');
        
        if (empty($_POST['id']))
            die();
        
        if (! empty($_POST['is_shared'])) {
            
            $fields = $userMeta->getData('fields');
            
            if (isset($fields[$_POST['id']])) {
                $field = $fields[$_POST['id']];
                $field['id'] = $_POST['id'];
                $field['is_shared'] = true;
                $fieldBuilder = new FieldBuilder($field);
                $fieldBuilder->setEditor('form_editor');
                echo $fieldBuilder->buildPanel();
            } else {
                echo "<div class=\"alert alert-warning\" role=\"alert\">Field id {$_POST['id']} is not exists!</div>";
            }
        } elseif (! empty($_POST['field_type'])) {
            $arg = $_POST;
            $arg['is_new'] = true;
            $fieldBuilder = new FieldBuilder($arg);
            $fieldBuilder->setEditor('form_editor');
            echo $fieldBuilder->buildPanel();
        }
        
        die();
    }

    function ajaxChangeField()
    {
        global $userMeta;
        $userMeta->verifyNonce(true);
        
        if (isset($_POST['field_type']) && isset($_POST['id']) && $_POST['editor']) {
            $field = $_POST;
            $fieldBuilder = new FieldBuilder($field);
            $fieldBuilder->setEditor($_POST['editor']);
            echo $fieldBuilder->buildPanel();
        }
        
        die();
    }

    function ajaxUpdateFields()
    {
        global $userMeta;
        $userMeta->verifyAdminNonce('updateFields');
        
        $fields = array();
        if (isset($_POST['fields']))
            $fields = $userMeta->arrayRemoveEmptyValue($_POST['fields']);
        
        $formBuilder = new FormBuilder();
        
        $fields = $formBuilder->sanitizeFieldsIDs($fields);
        
        $fields = apply_filters('user_meta_pre_configuration_update', $fields, 'fields_editor');
        $userMeta->updateData('fields', $fields);
        
        $formBuilder->setMaxFieldID();
        
        if (! empty($formBuilder->redirect_to)) {
            echo json_encode(array(
                'redirect_to' => $formBuilder->redirect_to
            ));
            die();
        }
        
        echo 1;
        die();
    }

    function ajaxAddForm()
    {
        global $userMeta;
        $userMeta->verifyNonce(true);
        
        $fields = $userMeta->getData('fields');
        $userMeta->render('form', array(
            'id' => $_POST['id'],
            'fields' => $fields
        ));
        die();
    }

    function ajaxUpdateForms()
    {
        global $userMeta; // $userMeta->dump($_REQUEST);die();
        $userMeta->verifyAdminNonce('formEditor');
        
        $parse = parse_url($_SERVER['HTTP_REFERER']);
        parse_str($parse['query'], $query);
        
        if (empty($query['action'])) {
            echo 'Something went wrong!';
            die();
        }
        
        if (! empty($_POST['form_key'])) {
            $formKey = $_POST['form_key'];
        } else {
            echo 'Form name is required.';
            die();
        }
        
        $forms = $userMeta->getData('forms');
        
        $formBuilder = new FormBuilder();
        
        if ('edit' == $query['action']) {
            if (empty($query['form']) || empty($_POST['form_key'])) {
                echo 'Something went wrong!';
                die();
            }
            
            if ($query['form'] != $_POST['form_key']) {
                if (isset($forms[$_POST['form_key']])) {
                    echo 'Form: "' . $_POST['form_key'] . '" already exists!';
                    die();
                }
                
                unset($forms[$query['form']]);
                $query['form'] = $_POST['form_key'];
                $formBuilder->redirect_to = $parse['scheme'] . '://' . $parse['host'] . $parse['path'] . '?' . http_build_query($query);
            }
        } elseif ('new' == $query['action']) {
            if (isset($forms[$_POST['form_key']])) {
                echo 'Form: "' . $_POST['form_key'] . '" already exists!';
                die();
            }
            
            $query['form'] = $_POST['form_key'];
            $query['action'] = 'edit';
            $formBuilder->redirect_to = $parse['scheme'] . '://' . $parse['host'] . $parse['path'] . '?' . http_build_query($query);
        }
        
        $fields = $formBuilder->getSharedFields();
        
        $form = $_POST;
        
        $form = stripslashes_deep($_POST);
        
        // $form = $userMeta->arrayRemoveEmptyValue( $_POST );
        
        $formFields = isset($form['fields']) ? $form['fields'] : array();
        
        $formFields = $formBuilder->sanitizeFieldsIDs($formFields);
        
        foreach ($formFields as $id => $field) {
            if (is_array($field)) {
                foreach ($field as $key => $val) {
                    // Process shared fields
                    if (isset($fields[$id][$key])) {
                        if ($fields[$id][$key] == $val)
                            unset($formFields[$id][$key]);
                    } else {
                        if (empty($val))
                            unset($formFields[$id][$key]);
                    }
                }
            }
            
            if (! empty($field['make_field_shared']) && ! isset($fields[$id])) {
                unset($formFields[$id]['make_field_shared']);
                $fields[$id] = $formFields[$id];
                $formFields[$id] = array();
                $triggerFieldsUpdate = true;
            }
        }
        
        $form['fields'] = $formFields;
        
        $form = $userMeta->removeAdditional($form);
        
        $forms[$formKey] = $form;
        
        $forms = apply_filters('user_meta_pre_configuration_update', $forms, 'forms_editor');
        
        $userMeta->updateData('forms', $forms);
        
        // $userMeta->dump($fields);
        if (! empty($triggerFieldsUpdate)) {
            $userMeta->updateData('fields', $fields);
            if (empty($formBuilder->redirect_to))
                $formBuilder->redirect_to = $parse['scheme'] . '://' . $parse['host'] . $parse['path'] . '?' . $parse['query'];
        }
        
        $formBuilder->setMaxFieldID();
        
        if (! empty($formBuilder->redirect_to)) {
            echo json_encode(array(
                'redirect_to' => $formBuilder->redirect_to
            ));
            die();
        }
        
        echo 1;
        die();
    }
}
