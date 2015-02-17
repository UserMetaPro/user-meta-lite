=== User Meta ===
Contributors: khaledsaikat
Tags: user, profile, registration, login, frontend, users, usermeta, import, csv, upload, AJAX, admin, plugin, page, image, images, photo, picture, file, email, shortcode, captcha, avatar, redirect, register, password, custom, csv, import, user import, widget
Requires at least: 3.3.0
Tested up to: 4.1
Stable tag: 1.1.6
Copyright: Khaled Hossain, user-meta.com
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress user management plugin. Custom user profile, custom Login, registration with extra fields and many more.

== Description ==

= Features =
* Allows user to update their profile on front-end.
* Add extra fields to custom user profile.
* Front-end user registration by shortcode.
* Add extra fields to custom user registration.
* Add profile link to users listing page.
* Fields and forms editor to customize front-end user profile and registration.
* Create unlimited forms for custom user registration or profile.
* Show role based user profile. Users from different role can see different profile form.
* Users can register their account with different role by using different registration form.
* Show user avatar to profile and registration page (both ajax and non ajax).
* Modify default email sender information (Let your user get email from your preferred name and email instead of wordpress@example.com)


= Extra features for User Meta Pro =
* Front-end custom user login by username or email.
* Front-end lost password and reset password.
* User Profile widget, registration widget and login widget.
* Auto user login after registration.
* Email verification on registration.
* Admin approval on user registration.
* Admin can activate or deactivate any user form user listing page.
* Add extra fields to default profile or hide existing fields.
* Role based redirection after user login, logout and registration.
* Customize emails sent from your WordPress site. Add default field or extra fields content to each email.
* Bulk users import/export from/to csv file with extra fields.
* Customize, filter, or change user order while exporting users.
* User import/export with hashed or plain text password.

Get [User Meta Pro](http://user-meta.com/ "User Meta Pro").


= Supported field for form builder =
* User Avatar
* TextBox
* Paragraph
* Rich Text
* Hidden Field
* DropDown
* Multi select
* CheckBox
* Select One (radio)
* Date /Time
* Password
* Email
* File Upload
* Image Url
* Phone Number
* Number
* Website
* Country
* Custom Field
* Page Heading
* Section Heading
* HTML
* Captcha



You can create unlimited number of fields. All newly created field's data will save to WordPress default usermeta table. so you can retrieve all user data by calling wordpress default functions(e.g. get_userdata(), get_user_meta() ). User Meta plugin separates fields and forms. So, a single field can be used among several forms.

= Documentation =

**3 steps to get started**

1. Create Field from User Meta >> Fields Editor.
1. Go to User Meta >> Forms Editor, Give a name to your form. Drag and drop fields from right to left and save the form.
1. Write shortcode to your page or post. e.g.: Shortcode: [user-meta-profile form='your_form_name']

[View Documentation](http://user-meta.com/documentation/ "User Meta Pro Documentation")

Get [User Meta Pro](http://user-meta.com/ "User Meta Pro").

== Installation ==

1. Upload and extract `user-meta.zip` to the `/wp-content/plugins/` directory or add the plugin from Plugin >> Add New menu
2. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

= Why error message, "User registration is currently not allowed." is showing in registration page? =

WordPress doesn't allow to register new user by default settings. To allow user to register, go to Settings >> General page in admin section. Checked the checkbox which is saying "Anyone can register" and Save Changes.


== Screenshots ==

01. Front-end user registration with extra fields.
02. Front-end user profile with extra fields.
03. Front-end user login form / widget.
04. Supported fields for creating profile or registration form.
05. Simple registration form.
06. Simple profile form.
07. Remove existing fields or add new fields to WordPress default backend profile.
08. Login configuration.
09. User export screen.
10. User import screen after uploading csv.
11. Email notifications.
12. Customization of email notification.
13. Change default email sender.
14. Auto login, admin approval, email verification on registration.
15. Role based redirection on login, logout and registration.

== Changelog ==

= 1.1.6 =
* Support user registration for free version.
* Pro: Added registration link with login form/widget.
* Remove plugin-framework.css/js. Split user-meta.js into user-meta.js and user-meta-admin.js
* Optimize the plugin for user-meta-advanced add-on.
* Allow action/filter hook enable/disable by ‘user_meta_wp_hook’ filter.
* Added Chinese translation.
* Fix: postbox toggle icon and allows drag texts inside postbox.
* Removed users_can_register option dependencies for user registration form.
* Added role placeholder.
* Added ver paramater to js and css files.
* Pro: Shortcode: [user-meta-field id=Field_ID] for showing single field.
* Pro: Shortcode: [user-meta-field-value id=Field_ID] to show stored field content.
* Pro: Store resetpass hashed key instead of plaintext as of WP-3.7
* Pro: Added Lost Password email notification.
* Pro: Number field allows integer and decimal point.
* Pro: Filter: user_meta_admin_email_recipient to filter admin notification email.
* Pro: Added multiselect field and allow optgroup on select.
* Pro: Import both plain text and hashed password with users import.
* Pro: Allow override of WordPress default user registration and reset password email by add-on.
* Pro: try to send single email when sending multiple email at a time failed.

= 1.1.5 =
* Add user_id parameter to user_meta_pre_user_update filter hook.
* Remove not used uploaded files via schedule events.
* Add Russian translation. (Thanks to Vitaliy Cherednichenko for his translation)
* Add placeholder support to Fields Editor.
* Change file upload directory to /uploads/files/
* Change logout url. Logout url is not using resetpass anymore.
* Add html5 placeholder to field by user-meta hook.
* Bug fix: Required validation for country field.
* Add settings for customize UMP generated text for front-end.
* Pro: Allow to send email notification for all users who have administrative role.
* Pro: User password will not force to include in registration email notification.
* Pro: Add option for use default lostpassword url.
* Pro: Remember last user import settings.
* Pro: Use separate page for reset password and email verification.
* Pro: Login redirect will not show any message while redirecting.

= 1.1.4.1 =
* Add Captcha for login form.
* Fix import UMP and pagination bugs.

= 1.1.4 =
* Use TinyMCE as rich text editor.
* meta_key auto generate from field title.
* Enable translation for dynamic text.
* Disable free to pro one click update as WordPress plugin guidline.
* add allow_custom in datetime field to allow more customization by js.
* Added alternate method when allow_url_fopen=0 for showing uploaded image.
* Field validation in both server and client side.
* Pro: Introduce new field type "Custom Field" to add custom regex and error message.

= 1.1.3 =
* Assign form to login widget.
* Allow role based profile as widget.
* Replace type=both into type=profile-registration
* Replace type=none into type=public
* type=public allow user_id as $_GET for showing public user profile.
* Add type=login to form widget.
* Change date format and filter hook.
* Default role selection.
* Shortcode generator popup.
* Clickable checkbox and radio.
* Added filter: user_meta_pre_configuration_update for fields_editor, forms_editor and settings.
* Action: user_meta_load_admin_pages
* Filter: user_meta_execution_page_config
* Filter: user_meta_default_login_form
* Aded filter support to lost password form and deafult login form.
* Theme for reCaptcha.
* Check user access by â€œadd_usersâ€ capability.
* Clickable users listing for Active | Inactive | Pending | Pending Approval
* Change email verification and reset password process.
* WordPress-3.5 compatibility.
* UMP Export-Import fields, forms, settings.
* Role based profile showing.
* Allow role selection on registration/profile (admin can choose which roles user can select).
* Field title position: Top, Left, Right, Inline, Hidden.
* Added â€œAuto login after user registrationâ€ feature.
* Fixes: Password changing from frontend.
* Image crop for avatar or file upload.
* Single pot file.
* Enable SSL admin.
* Assign custom form with login widget/login form that allow to use custom field, class name, changing button text/class.
* Integrate plugin-framework.pot with user-meta.pot (single pot file instead of two).
* Provide more action/filter hook in every steps.
* Allow to use placeholder under html field.
* MU: New blog registration.
* MU: Add user to blog.
* MU: added option for prevent login for non-member for current blog.
* Registration/Profile widget.
* Registration/Profile Template Tag.
* Extended users export.
* Allow to change buttonâ€™s text and css class of form.
* Custom email notification for profile update(both user and admin).

= 1.1.2 =
* One click upgrade to Pro version.
* Add default email sender support.
* Pro: One click version update.
* Pro: Login widget. Showing role based user data with login widget or shortcode.
* Pro: Extra fields in backend profile.
* Pro: Role based customizable email notification with extra fields.
* Pro: Import users from csv file including user's meta data.
* Pro: Front-end lost password and reset password tools.
* Pro: User email verification on registration.
* Pro: User activation and deactivation.
* pro: Role based user redirection on registration, login and logout.

= 1.1.1 =
* Added Support while fail AJAX call

= 1.1.0 =
* Include first version of User Meta Pro
* Pro: added more fields type
* Pro: Frontend Registration
* Pro: Frontend Login with username or email

= 1.0.5 =
* Changing complete structure
* Make Seperation of fields and form, so one field can be use in many form
* Add verious type of fields
* Added dragable fields to form
* Improve frontend profile

= 1.0.3 =
* Extend Import Functionality
* Draggable Meta Field
* Add Donation Button

= 1.0.2 =
* Optimize code using php class.
* add [user-meta-profile] shortcode support.

= 1.0.1 =
* Some Bug Free.

= 1.0.0 =
* First version.