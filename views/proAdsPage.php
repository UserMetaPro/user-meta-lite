<?php namespace UserMeta; ?>
<?php global $userMeta; ?>

<div class="wrap">
	<h1>
		<?= __('More features on Pro version', $userMeta->name)?>
		<a class='button-primary' href='<?=$userMeta->website?>'>Get User Meta
			Pro</a> <a class='button-primary' href='https://demo.user-meta.com'>Live
			Demo</a>
	</h1>

	<br />

	<div class="container-fluid">
		<div class="row">

			<div class="col-sm-10">
				<?=panel('Build your forms with pro fields', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-16.png" />')?>
			</div>

			<div class="col-sm-10">
				<?=panel('Extra fields on backend profile', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-07.png" />')?>
			</div>

			<div class="col-sm-10">
				<?=panel('Bulk users export with extra fields', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-09.png" />')?>
			</div>

			<div class="col-sm-10">
				<?=panel('Bulk users import with extra fields', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-10.png" />')?>
			</div>

			<div class="col-sm-10">
				<?=panel('Customize all WordPress generated email. Include extra field to email subject or body', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-11.png" /><img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-12.png" />')?>
			</div>

			<div class="col-sm-10">
				<?=panel('Admin approval / Email verification', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-14.png" />')?>
			</div>

			<div class="col-sm-10">
				<?=panel('Role based redirection', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-15.png" />')?>
			</div>

<?php
$more = "
				<li>Login, registration and profile widget.</li>
				<li>Customize email notification with including extra field's data.</li>
				<li>Advanced fields for creating profile/registration form.</li>
				<li>Fight against spam by Captcha.</li>
				<li>Split your form into multiple page by using Page Heading.</li>
				<li>Group fields using Section Heading.</li>
				<li>Allow user to upload their file by File Upload.</li>
				<li>Country Dropdown for country selection.</li>
				<li>Use Custom Field to build custom input field.</li>
				<li>Get free <a href='{$userMeta->website}/add-ons/'>add-ons</a>.</li>
    
    <center>
        <a class='button-primary' href='{$userMeta->website}'>Get User Meta Pro</a>
        <a class='button-primary' href='https://demo.user-meta.com'>Live Demo</a>
    </center>
    ";
?>

			<div class="col-sm-10">
				<?=panel('And More...', $more)?>
			</div>

		</div>
	</div>
</div>