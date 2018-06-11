<?php
namespace UserMeta;

/**
 * Class for building field editor inside form editor or editor for shared fields.
 *
 * @author Khaled Hossain
 * @since 1.1.7
 */
class FieldBuilder
{

    /**
     *
     * @var type (int) : Field ID
     */
    private $id;

    /**
     *
     * @var type (string) : Field Type
     */
    private $type;

    /**
     *
     * @var type (array) : Field Type Data
     */
    private $typeData;

    /**
     *
     * @var type (array) : Field Data
     */
    private $data;

    /**
     *
     * @var type (array) : Inputs
     */
    private $inputs = array();

    /**
     *
     * @var (string) Editor name
     */
    private $editor;

    /**
     *
     * @var (array) Dropdown
     */
    public static $formFieldsDropdown = array();

    /**
     *
     * @param type $data            
     */
    function __construct($data = array())
    {
        global $userMeta;
        
        $this->data = $data;
        
        $this->id = ! empty($data['id']) ? $data['id'] : 0;
        $this->type = ! empty($data['field_type']) ? $data['field_type'] : '';
        $this->typeData = $userMeta->umFields($this->type);
        
        $this->populateInputs();
    }

    /**
     * Set editor name
     *
     * @param type $editor            
     */
    function setEditor($editor)
    {
        $this->editor = $editor;
    }

    /**
     * Set $this->inputs
     */
    private function populateInputs()
    {
        global $userMeta;
        
        $inputs = array(
            'field_title' => array(
                'label' => __('Field Label', $userMeta->name),
                'placeholder' => __('Field Label', $userMeta->name)
            ),
            'title_position' => array(
                'type' => 'select',
                'label' => __('Label Position', $userMeta->name),
                'options' => array(
                    'top' => __('Top', $userMeta->name),
                    'left' => __('Left', $userMeta->name),
                    'right' => __('Right', $userMeta->name),
                    'inline' => __('Inline', $userMeta->name),
                    // 'placeholder' => __( 'Placeholder', $userMeta->name ), // Commented since 1.1.8rc2
                    'hidden' => __('Hidden', $userMeta->name)
                )
            ),
            'placeholder' => array(
                'label' => 'Placeholder',
                'placeholder' => __('Placeholder', $userMeta->name)
            ),
            'description' => array(
                'type' => 'textarea',
                'label' => __('Description', $userMeta->name),
                'placeholder' => __("Field's Description", $userMeta->name)
            ),
            'meta_key' => array(
                'label' => 'Meta Key <span class="um_required">*</span>',
                'placeholder' => __('Unique identification key for field', $userMeta->name),
                'info' => __('Unique identification key for field (required).', $userMeta->name)
            ),
            'default_value' => array(
                'label' => __('Default Value', $userMeta->name),
                'placeholder' => __('Default Value', $userMeta->name),
                'info' => __('Use this value when user doesn\'t have any stored value', $userMeta->name)
            ),
            'options' => array(
                'type' => 'textarea',
                'label' => __('Field Options', $userMeta->name) . ' <span class="um_required">*</span>',
                'placeholder' => 'Available options. (e.g: Yes,No OR yes=Agree,no=Disagree'
            ),
            
            'field_class' => array(
                'label' => __('Input Class', $userMeta->name),
                'placeholder' => __('Specify custom class name for input', $userMeta->name)
            ),
            'css_class' => array(
                'label' => __('Field Container Class', $userMeta->name),
                'placeholder' => __('Custom class name for field container', $userMeta->name)
            ),
            'css_style' => array(
                'type' => 'textarea',
                'label' => __('Field Container Style', $userMeta->name),
                'placeholder' => __('Custom css style for field container', $userMeta->name)
            ),
            'field_size' => array(
                'label' => __('Field Size', $userMeta->name),
                'placeholder' => 'e.g. 200px;'
            ),
            'field_height' => array(
                'label' => __('Field Height', $userMeta->name),
                'placeholder' => 'e.g. 200px;'
            ),
            'max_char' => array(
                'label' => __('Max Char', $userMeta->name),
                'placeholder' => __('Maximum allowed character', $userMeta->name)
            ),
            
            'allowed_extension' => array(
                'label' => __('Allowed Extension', $userMeta->name),
                'placeholder' => 'Default: jpg,png,gif'
            ),
            'role_selection_type' => array(
                'type' => 'select',
                'label' => __('Role Selection Type', $userMeta->name),
                'options' => array(
                    'select' => 'Dropdown',
                    'radio' => 'Select One (radio)',
                    'hidden' => 'Hidden'
                )
            ),
            'datetime_selection' => array(
                'type' => 'select',
                'label' => __('Type Selection', $userMeta->name),
                'info' => 'Date, Time or Date & Time',
                'options' => array(
                    'date' => __('Date', $userMeta->name),
                    'time' => __('Time', $userMeta->name),
                    'datetime' => __('Date and Time', $userMeta->name)
                )
            ),
            'date_format' => array(
                'label' => __('Date Format', $userMeta->name),
                'placeholder' => 'Default: yy-mm-dd'
            ),
            'year_range' => array(
                'label' => __('Year Range', $userMeta->name),
                'placeholder' => 'Default: 1950:c'
            ),
            'country_selection_type' => array(
                'type' => 'select',
                'label' => __('Save meta value by', $userMeta->name),
                'options' => array(
                    'by_country_code' => __('Country Code', $userMeta->name),
                    'by_country_name' => __('Country Name', $userMeta->name)
                )
            ),
            
            'max_number' => array(
                'type' => 'number',
                'label' => __('Maximum Number', $userMeta->name)
            ),
            'min_number' => array(
                'type' => 'number',
                'label' => __('Minimum Number', $userMeta->name)
            ),
            'step' => array(
                'type' => 'number',
                'label' => 'Step',
                'info' => __('Intervals for number input', $userMeta->name)
            ),
            
            'max_file_size' => array(
                'type' => 'number',
                'min' => 0,
                'max' => File::getServerMaxSizeLimit(),
                'label' => __('Maximum File Size (KB)', $userMeta->name),
                'placeholder' => 'Default: 1024KB',
                'info' => 'According to your server settings, allowed maximum is ' . File::getServerMaxSizeLimit() . 'KB. ' . 'To increase the limit, increase value of post_max_size and upload_max_filesize on your server.'
            ),
            'image_width' => array(
                'type' => 'number',
                'min' => 0,
                'label' => 'Image Width (px)',
                'placeholder' => 'For Image Only. e.g. 640'
            ),
            'image_height' => array(
                'type' => 'number',
                'min' => 0,
                'label' => 'Image Height (px)',
                'placeholder' => 'For Image Only. e.g. 480'
            ),
            'image_size' => array(
                'type' => 'number',
                'min' => 0,
                'label' => 'Image Size (px) width/height',
                'placeholder' => 'Default: 96'
            ),
            'input_type' => array(
                'type' => 'select',
                'label' => 'HTML5 Input Type',
                'by_key' => true,
                'options' => array(
                    '' => '',
                    'email' => [
                        'Email',
                        'data-child' => 'retype_email,retype_label'
                    ],
                    'password' => [
                        'Password',
                        'data-child' => 'retype_password,retype_label'
                    ],
                    'tel' => 'Tel',
                    'month' => 'Month',
                    'week' => 'Week'
                )
            ),
            'regex' => array(
                'label' => 'Pattern',
                'placeholder' => 'e.g. (alpha-numeric): [a-zA-Z0-9]+'
            ),
            'error_text' => array(
                'label' => __('Error Text', $userMeta->name),
                'placeholder' => 'e.g. Invalid field'
            ),
            'retype_label' => array(
                'label' => __('Retype Label', $userMeta->name),
                'placeholder' => __('Label for retype field', $userMeta->name)
            ),
            'captcha_theme' => [
                'type' => 'select',
                'label' => __('reCaptcha Theme', $userMeta->name),
                'options' => [
                    '' => __('Light', $userMeta->name),
                    'dark' => __('Dark', $userMeta->name)
                ]
            ],
            'captcha_type' => [
                'type' => 'select',
                'label' => __('reCaptcha Type', $userMeta->name),
                'options' => [
                    '' => __('Image', $userMeta->name),
                    'audio' => __('Audio', $userMeta->name)
                ]
            ],
            'captcha_lang' => [
                'label' => __('reCaptcha Language', $userMeta->name),
                'placeholder' => __('(e.g. en) Leave blank for auto detection', $userMeta->name),
                'info' => __('(e.g. en) Leave blank for auto detection', $userMeta->name)
            ],
            'resize_image' => array(
                'type' => 'checkbox',
                'label' => __('Resize Image', $userMeta->name),
                'child' => 'crop_image'
            ),
            'retype_email' => array(
                'type' => 'checkbox',
                'label' => __('Retype Email', $userMeta->name),
                'child' => 'retype_label'
            ),
            'retype_password' => array(
                'type' => 'checkbox',
                'label' => __('Retype Password', $userMeta->name),
                'child' => 'retype_label'
            )
        );
        
        $checkboxes = array(
            'required' => __('Required', $userMeta->name),
            'admin_only' => __('Admin Only', $userMeta->name),
            'non_admin_only' => __('Non-Admin Only', $userMeta->name),
            'read_only' => __('Read-Only for all user', $userMeta->name),
            'read_only_non_admin' => __('Read-Only for non admin', $userMeta->name),
            'unique' => __('Unique', $userMeta->name),
            'registration_only' => __('Only on Registration Page', $userMeta->name),
            
            'disable_ajax' => __('Disable AJAX upload', $userMeta->name),
            'hide_default_avatar' => __('Hide default avatar', $userMeta->name),
            // 'resize_image' => __( 'Resize Image', $userMeta->name ),
            'crop_image' => __('Crop Image', $userMeta->name),
            
            'line_break' => __('Line Break', $userMeta->name),
            'integer_only' => __('Allow integer only', $userMeta->name),
            'as_range' => 'Use as range',
            
            'force_username' => __('Force to change username', $userMeta->name),
            // 'retype_email' => __( 'Retype Email', $userMeta->name ),
            // 'retype_password' => __( 'Retype Password', $userMeta->name ),
            'password_strength' => __('Show password strength meter', $userMeta->name),
            'required_current_password' => __('Current password is required', $userMeta->name),
            'show_divider' => __('Show Divider', $userMeta->name),
            'rich_text' => __('Use Rich Text', $userMeta->name),
            'make_field_shared' => __('Make this field as shared', $userMeta->name),
            'advanced_mode' => __('Advanced mode', $userMeta->name)
        );
        
        foreach ($checkboxes as $key => $val) {
            $inputs[$key] = array(
                'type' => 'checkbox',
                'label' => $val
            );
        }
        
        $this->inputs = $inputs;
    }

    /**
     * Run inside createElement() before executing field
     */
    private function _pre_field_title()
    {
        if (empty($this->data['is_new']))
            return;
        
        if (isset($this->typeData['field_group']) && 'wp_default' == $this->typeData['field_group']) {
            if (isset($this->typeData['title']))
                $this->data['field_title'] = $this->typeData['title'];
        }
    }

    /**
     * Backword comatibility for placeholder as title
     */
    private function _pre_title_position()
    {
        global $userMeta;
        if (! empty($this->data['title_position']) && 'placeholder' == $this->data['title_position']) {
            $this->inputs['title_position']['options']['placeholder'] = __('Placeholder', $userMeta->name);
        }
    }

    /**
     * Creating divider element
     * Run automaticallly inside createElement() method
     *
     * @return html
     */
    private function _element_divider()
    {
        return '<div class="pf_divider"></div>';
    }

    /**
     * Creating content element
     * Run automaticallly inside createElement() method
     *
     * @return html
     */
    private function _element_content($args)
    {
        global $userMeta;
        extract($this->data);
        
        $args['label'] = __('Content', $userMeta->name);
        $args['value'] = isset($default_value) ? $default_value : null;
        
        return $userMeta->createInput('default_value', 'textarea', $args);
    }

    /**
     * Creating value element
     * Run automaticallly inside createElement() method
     *
     * @return html
     */
    private function _element_value($args)
    {
        global $userMeta;
        extract($this->data);
        
        $args['label'] = __('Value', $userMeta->name);
        $args['value'] = isset($default_value) ? $default_value : null;
        
        return $userMeta->createInput('default_value', 'text', $args);
    }

    /**
     * Creating field_type element
     * Run automaticallly inside createElement() method
     *
     * @return html
     */
    private function _element_field_type()
    {
        global $userMeta;
        
        extract($this->data);
        
        $field_type_data = $userMeta->getFields('key', $field_type);
        $field_type_title = $field_type_data['title'];
        $field_group = $field_type_data['field_group'];
        $field_types_options = $userMeta->getFields('field_group', $field_group, 'title', ! $userMeta->isPro);
        
        return $userMeta->createInput('field_type', 'select', array(
            'label' => __('Field Type', $userMeta->name),
            'value' => isset($field_type) ? $field_type : null,
            'class' => 'form-control',
            'label_class' => 'col-sm-3 control-label',
            'field_enclose' => 'div class="col-sm-6"',
            'enclose' => 'div class="form-group um_fb_field"',
            'by_key' => true
        ), $field_types_options);
    }

    /**
     * Creating checkbox_group element
     * Run automaticallly inside createElement() method
     *
     * @return html
     */
    private function _element_checkbox_group($args, $input)
    {
        global $userMeta;
        
        extract($this->data);
        
        array_shift($input);
        
        $html = '<div class="form-group"><label class="control-label col-sm-3">' . array_shift($input) . '</label>';
        
        $inputs = $this->inputs;
        
        $html .= '<div class="col-sm-8">';
        foreach ($input as $checkbox) {
            $data = $inputs[$checkbox];
            
            $inputArg = array(
                'value' => '1',
                'checked' => ! empty($$checkbox) ? true : false,
                'label' => $data['label'],
                'class' => 'form-control',
                'enclose' => 'p class="um_fb_field"'
            );
            
            if (! empty($data['child'])) {
                $inputArg['class'] .= ' um_parent';
                $inputArg['data-child'] = $data['child'];
            }
            
            $html .= $userMeta->createInput($checkbox, 'checkbox', $inputArg);
        }
        $html .= '</div></div>';
        
        return $html;
    }

    /**
     * Creating allowed_roles element
     * Run automaticallly inside createElement() method
     *
     * @return html
     */
    private function _element_allowed_roles()
    {
        global $userMeta;
        
        $roles = $userMeta->getRoleList(true);
        
        extract($this->data);
        
        return $userMeta->createInput('allowed_roles', 'multiselect', array(
            'label' => __('Allowed Roles', $userMeta->name),
            'value' => isset($allowed_roles) ? $allowed_roles : null,
            'class' => 'form-control um_multiselect',
            'label_class' => 'col-sm-3 control-label',
            'field_enclose' => 'div class="col-sm-6"',
            'enclose' => 'div class="form-group um_fb_field"',
            'by_key' => true
        ), $roles);
    }

    /**
     * Creating default_role element
     * Run automaticallly inside createElement() method
     *
     * @return html
     */
    private function _element_default_role()
    {
        global $userMeta;
        
        $roles = $userMeta->getRoleList(true);
        $emptyFirstRoles = $roles;
        array_unshift($emptyFirstRoles, null);
        
        extract($this->data);
        
        return $userMeta->createInput('default_value', 'select', array(
            'label' => __('Default Role', $userMeta->name),
            'value' => isset($default_value) ? $default_value : null,
            'after' => __('Should be one of the Allowed Roles', $userMeta->name),
            'class' => 'form-control',
            'label_class' => 'col-sm-3 control-label',
            'field_enclose' => 'div class="col-sm-6"',
            'enclose' => 'div class="form-group um_fb_field"',
            'by_key' => true
        ), $emptyFirstRoles);
    }

    private function _element_options()
    {
        global $userMeta;
        
        return $userMeta->renderPro('optionsSelection', array(
            'data' => $this->data
        ), 'fields', true);
    }

    /**
     * Creating conditional_logic element
     * Run automaticallly inside createElement() method
     *
     * @return html
     */
    private function _element_conditional_logic()
    {
        global $userMeta;
        extract($this->data);
        
        return $html = $userMeta->renderPro('conditionalPanel', array(
            'id' => $this->id,
            'conditional' => ! empty($condition) && is_array($condition) ? $condition : array(),
            'fieldList' => self::$formFieldsDropdown
        ), 'forms', true);
    }

    /**
     * Temp implementation using umInput class
     * Creating divider element
     * Run automaticallly inside createElement() method
     *
     * @return html
     */
    private function _element_input_type($args)
    {
        $args['name'] = 'input_type';
        $args['class'] = $args['class'] . ' um_parent';
        
        $attr = $args;
        $attr['_enclose'] = [
            'div',
            'class' => 'col-sm-6'
        ];
        unset($attr['type']);
        unset($attr['label']);
        unset($attr['label_class']);
        unset($attr['value']);
        unset($attr['field_enclose']);
        unset($attr['enclose']);
        unset($attr['by_key']);
        
        $options = $this->inputs['input_type']['options'];
        
        $input = new Html\Html('div', [
            'class' => 'form-group um_fb_field'
        ]);
        $input->label($args['label'], [
            'class' => $args['label_class']
        ]);
        $input->select($args['value'], $attr, $options);
        
        return $input->render();
    }

    /**
     * Creating single field's element
     *
     * @param string $input            
     * @return type html input
     */
    function createElement($input)
    {
        global $userMeta;
        
        if (empty($input))
            return;
        
        $name = is_array($input) ? $input[0] : $input;
        
        if (method_exists($this, '_pre_' . $name)) {
            $methodName = '_pre_' . $name;
            $this->$methodName();
        }
        
        extract($this->data);
        
        $args = isset($this->inputs[$name]) ? $this->inputs[$name] : array();
        
        $args = wp_parse_args($args, array(
            'type' => 'text',
            'value' => isset($$name) ? $$name : null,
            'class' => 'form-control',
            'label_class' => 'col-sm-3 control-label',
            'field_enclose' => 'div class="col-sm-6"',
            'after' => $this->tooltip($input),
            'enclose' => 'div class="form-group um_fb_field"'
        ));
        
        if ('checkbox' == $args['type']) {
            $args['label_class'] = 'col-sm-offset-3 col-sm-6';
            $args['field_enclose'] = '';
            $args['enclose'] = 'p class="form-group um_fb_field"';
        }
        
        if ('select' == $args['type'] && ! isset($args['by_key']))
            $args['by_key'] = true;
        
        $options = array();
        if (isset($args['options'])) {
            $options = $args['options'];
            unset($args['options']);
        }
        
        if (method_exists($this, '_element_' . $name)) {
            $methodName = '_element_' . $name;
            return $this->$methodName($args, $input);
        }
        
        $type = $args['type'];
        unset($args['type']);
        
        return $userMeta->createInput($name, $type, $args, $options);
    }

    /**
     * Show tooltip
     *
     * @param string $input            
     */
    private function tooltip($input)
    {
        if (! (is_string($input) && isset($this->inputs[$input]) && isset($this->inputs[$input]['info'])))
            return;
        
        return Html\Html::i('', [
            'class' => 'fa fa-info-circle',
            'style' => 'margin:10px 0;font-size: 1.3em;',
            'data-toggle' => 'tooltip',
            'data-placement' => 'right',
            'title' => $this->inputs[$input]['info']
        ]);
    }

    /**
     * Run by $this->build()
     *
     * @return array Single field's conaining element array
     */
    function fieldSpecification()
    {
        global $userMeta;
        
        $start1 = array(
            'field_title',
            'title_position',
            'description'
        );
        $start2 = array(
            'field_title',
            'title_position',
            'placeholder',
            'description'
        );
        $start3 = array(
            'field_title',
            'title_position',
            'description',
            'meta_key',
            'default_value'
        );
        $start4 = array(
            'field_title',
            'title_position',
            'placeholder',
            'description',
            'meta_key',
            'default_value'
        );
        $checkbox1 = array(
            array(
                'checkbox_group',
                'Rules',
                'admin_only',
                'read_only',
                'read_only_non_admin'
            )
        );
        $checkbox2 = array(
            array(
                'checkbox_group',
                'Rules',
                'admin_only',
                'read_only',
                'read_only_non_admin',
                'unique'
            )
        );
        $checkbox3 = array(
            array(
                'checkbox_group',
                'Rules',
                'admin_only',
                'non_admin_only',
                'read_only',
                'read_only_non_admin'
            )
        );
        $style1 = array(
            'divider',
            'max_char',
            'field_size',
            'field_class',
            'css_class',
            'css_style'
        );
        $style2 = array(
            'divider',
            'max_char',
            'field_size',
            'field_height',
            'field_class',
            'css_class',
            'css_style'
        );
        $style3 = array(
            'divider',
            'field_size',
            'field_class',
            'css_class',
            'css_style'
        );
        
        $fields = array(
            'user_login' => array(
                'basic' => $start2,
                'advanced' => array_merge(array(
                    array(
                        'checkbox_group',
                        'Rule',
                        'admin_only'
                    )
                ), $style1)
            ),
            'user_email' => array(
                'basic' => array_merge($start2, array(
                    array(
                        'checkbox_group',
                        '',
                        'retype_email'
                    ),
                    'retype_label'
                )),
                'advanced' => array_merge($checkbox1, $style1)
            ),
            'user_pass' => array(
                'basic' => array_merge($start2, array(
                    'regex',
                    'error_text'
                ), array(
                    array(
                        'checkbox_group',
                        '',
                        'retype_password',
                        'password_strength',
                        'required_current_password'
                    ),
                    'retype_label'
                )),
                'advanced' => array_merge(array(
                    array(
                        'checkbox_group',
                        'Rule',
                        'admin_only'
                    )
                ), $style1)
            ),
            'description' => array(
                'basic' => array_merge($start2, array(
                    array(
                        'checkbox_group',
                        '',
                        'required',
                        'rich_text'
                    )
                )),
                'advanced' => array_merge($checkbox2, $style2)
            ),
            'role' => array(
                'basic' => array_merge($start1, array(
                    'allowed_roles',
                    'default_role',
                    'role_selection_type',
                    'required'
                )),
                'advanced' => array_merge($checkbox3, $style3)
            ),
            'user_avatar' => array(
                'basic' => array_merge($start1, array(
                    'allowed_extension',
                    'image_size',
                    'max_file_size'
                ), array(
                    array(
                        'checkbox_group',
                        '',
                        'required',
                        'hide_default_avatar',
                        'resize_image',
                        'crop_image',
                        'disable_ajax'
                    )
                )),
                'advanced' => array_merge($checkbox3, array(
                    'divider',
                    'field_class',
                    'css_class',
                    'css_style'
                ))
            ),
            'rich_text' => [
                'basic' => [
                    'field_title',
                    'title_position',
                    'description',
                    'meta_key',
                    'default_value',
                    'required'
                ],
                'advanced' => array_merge($checkbox2, $style2)
            ],
            'hidden' => array(
                'basic' => array(
                    'meta_key',
                    'default_value'
                ),
                'advanced' => array(
                    array(
                        'checkbox_group',
                        '',
                        'admin_only'
                    )
                )
            ),
            
            // select == multiselect radio == checkbox
            'select' => array(
                'basic' => array(
                    'field_title',
                    'title_position',
                    'description',
                    'meta_key',
                    'options',
                    array(
                        'checkbox_group',
                        '',
                        'advanced_mode',
                        'required'
                    )
                ),
                'advanced' => array_merge($checkbox1, $style3)
            ),
            'radio' => array(
                'basic' => array(
                    'field_title',
                    'title_position',
                    'description',
                    'meta_key',
                    'options',
                    array(
                        'checkbox_group',
                        '',
                        'advanced_mode',
                        'required',
                        'line_break'
                    )
                ),
                'advanced' => array_merge($checkbox1, $style3)
            ),
            'checkbox' => array(
                'basic' => array(
                    'field_title',
                    'title_position',
                    'description',
                    'meta_key',
                    'options',
                    array(
                        'checkbox_group',
                        '',
                        'advanced_mode',
                        'required',
                        'line_break'
                    )
                ),
                'advanced' => array_merge($checkbox1, $style3)
            ),
            'url' => array(
                'basic' => array(
                    'field_title',
                    'title_position',
                    'placeholder',
                    'description',
                    'meta_key',
                    'default_value',
                    'required'
                ),
                'advanced' => array_merge($checkbox2, $style3)
            ),
            'wp_default' => array(
                'basic' => array(
                    'field_title',
                    'title_position',
                    'placeholder',
                    'description',
                    'required'
                ),
                'advanced' => array_merge($checkbox2, $style1)
            ),
            'group_1' => array(
                'basic' => array(
                    'field_title',
                    'title_position',
                    'placeholder',
                    'description',
                    'meta_key',
                    'default_value',
                    'required'
                ),
                'advanced' => array_merge($checkbox2, $style2)
            ),
            'group_3' => array(
                'basic' => array_merge($start1, array(
                    array(
                        'checkbox_group',
                        '',
                        'show_divider'
                    )
                )),
                'advanced' => array(
                    'css_class',
                    'css_style'
                )
            )
        );
        
        if ($userMeta->isPro) {
            $fieldsPro = array(
                'multiselect' => array(
                    'basic' => array(
                        'field_title',
                        'title_position',
                        'description',
                        'meta_key',
                        'options',
                        array(
                            'checkbox_group',
                            '',
                            'advanced_mode',
                            'required'
                        )
                    ),
                    'advanced' => array_merge($checkbox1, $style3)
                ),
                'datetime' => array(
                    'basic' => array_merge($start4, array(
                        'datetime_selection',
                        'date_format',
                        'year_range',
                        'required'
                    )),
                    'advanced' => array_merge($checkbox2, $style3)
                ),
                'password' => array(
                    'basic' => array_merge($start4, array(
                        array(
                            'checkbox_group',
                            '',
                            'required',
                            'retype_password',
                            'password_strength'
                        )
                    )),
                    'advanced' => array_merge($checkbox1, $style1)
                ),
                'email' => array(
                    'basic' => array_merge($start4, array(
                        array(
                            'checkbox_group',
                            '',
                            'required',
                            'retype_email'
                        )
                    )),
                    'advanced' => array_merge($checkbox2, $style1)
                ),
                'file' => array(
                    'basic' => array_merge($start3, array(
                        'allowed_extension',
                        'image_width',
                        'image_height',
                        'max_file_size'
                    ), array(
                        array(
                            'checkbox_group',
                            '',
                            'required',
                            'resize_image',
                            'crop_image',
                            'disable_ajax'
                        )
                    )),
                    'advanced' => array_merge($checkbox1, array(
                        'divider',
                        'field_class',
                        'css_class',
                        'css_style'
                    ))
                ),
                'number' => array(
                    'basic' => array_merge($start4, array(
                        'min_number',
                        'max_number',
                        'step'
                    ), array(
                        array(
                            'checkbox_group',
                            '',
                            'required',
                            'integer_only',
                            'as_range'
                        )
                    )),
                    'advanced' => array_merge($checkbox2, $style3)
                ),
                'country' => array(
                    'basic' => array_merge($start3, array(
                        'country_selection_type',
                        'required'
                    )),
                    'advanced' => array_merge($checkbox2, $style3)
                ),
                'custom' => array(
                    'basic' => array_merge($start4, array(
                        'input_type',
                        'regex',
                        'error_text',
                        'retype_label',
                        'required',
                        'retype_email',
                        'retype_password'
                    )),
                    'advanced' => array_merge($checkbox2, $style3)
                ),
                
                'html' => array(
                    'basic' => array(
                        'field_title',
                        'title_position',
                        'content',
                        'description'
                    ),
                    'advanced' => array()
                ),
                'captcha' => array(
                    'basic' => array_merge($start1, array(
                        'captcha_theme',
                        'captcha_type',
                        'captcha_lang'
                    ), array(
                        array(
                            'checkbox_group',
                            '',
                            'registration_only'
                        )
                    )),
                    'advanced' => array()
                )
            );
            
            $fields = array_merge($fields, $fieldsPro);
        }
        
        $groups = array(
            'text' => 'group_1',
            'textarea' => 'group_1',
            // 'rich_text' => 'group_1',
            'image_url' => 'group_1',
            'phone' => 'group_1',
            'page_heading' => 'group_3',
            'section_heading' => 'group_3'
        );
        
        foreach ($groups as $key => $val)
            $fields[$key] = $fields[$val];
        
        $fieldType = isset($fields[$this->type]) ? $this->type : 'wp_default';
        $field = $fields[$fieldType];
        
        if ('fields_editor' == $this->editor) {
            array_unshift($field['advanced'], 'field_type');
        } elseif ('form_editor' == $this->editor) {
            if (! empty($this->data['is_shared'])) {
                $key = array_search('meta_key', $field['basic']);
                if ($key)
                    unset($field['basic'][$key]);
            } else {
                array_unshift($field['advanced'], 'field_type');
                array_push($field['advanced'], 'make_field_shared');
            }
        }
        
        $this->addConditionalLogic($field['advanced'], $fieldType);
        
        return $field;
    }

    /**
     * Add conditional_logic to end of provided array
     *
     * @param array $array            
     * @param unknown $fieldType            
     */
    private function addConditionalLogic(array &$array, $fieldType)
    {
        if ('form_editor' == $this->editor && ! in_array($fieldType, [
            'hidden',
            'page_heading',
            'captcha'
        ])) {
            array_push($array, 'conditional_logic');
        }
    }

    function build()
    {
        global $userMeta;
        $html = null;
        $field = $this->fieldSpecification();
        
        $tabsText = [
            'basic' => __('Basic', $userMeta->name),
            'advanced' => __('Advanced', $userMeta->name)
        ];
        $tabs = [];
        foreach ($field as $key => $group) {
            $inputs = null;
            $inputs .= '<br /><div class="form-horizontal" role="form">';
            foreach ($group as $input) {
                $inputs .= $this->createElement($input);
            }
            $inputs .= '</div>';
            $tabs[$tabsText[$key]] = $inputs;
        }
        
        return $userMeta->buildTabs('fields_tab_' . $this->id, $tabs);
    }

    /**
     * Building field panel
     *
     * @return html
     */
    function buildPanel()
    {
        $class = '';
        $panelClass = 'panel-info';
        
        if ('fields_editor' == $this->editor) {
            $class = ' in';
        } elseif ('form_editor' == $this->editor) {
            if (empty($this->data['is_shared'])) {
                $panelClass = 'panel-success';
                $class = ' in';
            }
        }
        
        return '<div id="um_admin_field_' . $this->id . '" class="panel ' . $panelClass . ' um_field_single">
            <div class="panel-heading">
                <h3 class="panel-title">
                    ' . $this->title() . '
                    <span class="um_trash" title="Remove this field"><i style="margin-left:10px" class="fa fa-times"></i></span> 
                    <span title="Click to toggle"><i class="fa fa-caret-down"></i></span>
                </h3>
            </div>
            <div class="panel-collapse collapse' . $class . '">
                <div class="panel-body">
                ' . $this->build() . '
                </div>
            </div>
        </div>';
    }

    /**
     * Field panel title for field's editor
     *
     * @return html
     */
    function title()
    {
        $label = isset($this->data['field_title']) ? $this->data['field_title'] : '';
        $typeLabel = isset($this->typeData['title']) ? $this->typeData['title'] : '';
        return '<span class="um_field_panel_title">ID:<span class="um_field_id">' . $this->id . '</span>' . ' (<span>' . $typeLabel . '</span>) ' . '<span class="um_field_label">' . $label . '</span></span>' . '<input type="hidden" class="um_field_type" value="' . $this->type . '"/>';
    }
}