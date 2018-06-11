<?php
/**
 * Expected: $settings, $forms, $fields, $default
 */
global $userMeta;

$isPro = $userMeta->isPro();
$pageTitle = $isPro ? __('User Meta Pro Settings', $userMeta->name) : __('User Meta Settings', $userMeta->name);
?>

<div class="wrap">
	<h1><?= $pageTitle ?></h1>
    <?php do_action( 'um_admin_notice' ); ?>
    <div id="dashboard-widgets-wrap">
		<div id="um_settings_admin" class="metabox-holder">
			<div id="um_admin_content">
                <?php
                if ($userMeta->isPro)
                    $userMeta->renderPro("activationForm", null, "settings");
                
                $title = array(
                    'general' => __('General', $userMeta->name),
                    'login' => __('Login', $userMeta->name),
                    'registration' => __('Registration', $userMeta->name),
                    'redirection' => __('Redirection', $userMeta->name),
                    'profile' => $isPro ? __('Backend Profile', $userMeta->name) : '<span class="pf_blure">' . __('Backend Profile', $userMeta->name) . '</span>'
                );
                ?>

                <form id="um_settings_form" action="" method="post"
					onsubmit="umUpdateSettings(this); return false;">
					<div id="um_settings_tab">
						<ul>
							<li><a href="#um_settings_general"><?php echo $title['general']; ?></a></li>
							<li><a href="#um_settings_login"><?php echo $title['login']; ?></a></li>
							<li><a href="#um_settings_registration"><?php echo $title['registration']; ?></a></li>
							<?php if($isPro): ?>
							<li><a href="#um_settings_redirection"><?php echo $title['redirection']; ?></a></li>
							<li><a href="#um_settings_backend_profile"><?php echo $title['profile']; ?></a></li>
							<?php endif; ?>
							<li><a href="#um_settings_text"><?php _e( 'Text', $userMeta->name ); ?></a></li>
                        	<?php do_action( 'user_meta_settings_tab' ); ?>
                		</ul>


						<div id="um_settings_general">
                        	<?=$userMeta->renderPro("generalSettings", ['general' => isset($settings['general']) ? $settings['general'] : $default['general']], "settings")?>
                        	<?=$userMeta->renderPro("generalProSettings", ['general' => isset($settings['general']) ? $settings['general'] : $default['general']], "settings")?>
                        </div>

						<div id="um_settings_login">
                        	<?=$userMeta->renderPro("loginSettings", array('login' => isset($settings['login']) ? $settings['login'] : $default['login']), "settings")?>
                        </div>

						<div id="um_settings_registration">
                        	<?=$userMeta->renderPro("registrationSettings", array('registration' => isset($settings['registration']) ? $settings['registration'] : $default['registration']), "settings")?>
                        </div>

						<?php if ($isPro): ?>
						<div id="um_settings_redirection">
                        	<?=$userMeta->renderPro("redirectionSettings", array('redirection' => isset($settings['redirection']) ? $settings['redirection'] : $default['redirection']), "settings")?>
                        </div>

						<div id="um_settings_backend_profile">
        					<?=$userMeta->renderPro("backendProfile", array('backend_profile' => isset($settings['backend_profile']) ? $settings['backend_profile'] : $default['backend_profile'],'forms' => $forms,'fields' => $fields), "settings")?>
        				</div>
						<?php endif; ?>
                        
                        <div id="um_settings_text">
                        	<?=$userMeta->renderPro("textSettings", array('text' => isset($settings['text']) ? $settings['text'] : array()), "settings")?>
                        </div>
                        
                        <?php do_action('user_meta_settings_tab_details'); ?>
					</div>

                    <?php
                    echo $userMeta->nonceField();
                    echo $userMeta->createInput("save_field", "submit", array(
                        "value" => __("Save Changes", $userMeta->name),
                        "id" => "update_settings",
                        "class" => "button-primary",
                        "enclose" => "p"
                    ));
                    ?>
                </form>
			</div>

			<div id="um_admin_sidebar">
                <?php
                $panelArgs = [
                    'panel_class' => 'panel-default'
                ];
                echo $userMeta->metaBox(__('Get started', $userMeta->name), $userMeta->boxHowToUse());
                echo $userMeta->metaBox('Shortcodes', $userMeta->boxShortcodesDocs());
                ?>
            </div>
		</div>
	</div>
</div>