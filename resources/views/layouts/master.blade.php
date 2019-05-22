<?php
$titles = [];
if (isSet(View::getSections()['title']))
{
	if (is_array(View::getSections()['title']))
	{
		$titles = array_merge($titles, View::getSections()['title']);
	}
	else
	{
		$titles[] = View::getSections()['title'];
	}
}

if (sizeof($titles) == 0)
{
	$page_title = trans('site::frontend.main.title') . ' - ' . trans('site::frontend.header.title');
}
else
{
	$page_title = implode(' - ', array_reverse($titles)) . ' | ' . trans('site::frontend.main.title');
}
?>
<html lang="{{ $language->locale }}">
<head>
	<title>{{ $page_title }}</title>
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0" />
	<meta name="description" content="{{ e($__env->yieldContent('description', trans('site::frontend.meta-description'))) }}" />
	<meta name="csrf_token" content="{{ csrf_token() }}" />
	
	<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

	<style>
		body {
			margin: 0;
			padding: 0;
			width: 100%;
			height: 100%;
			color: #B0BEC5;
			display: table;
			font-weight: 100;
			font-family: 'Lato';
		}

		.container {
			text-align: center;
			display: table-cell;
			vertical-align: middle;
		}

		.content {
			text-align: center;
			display: inline-block;
		}

		.title {
			font-size: 96px;
			margin-bottom: 40px;
		}

		.quote {
			font-size: 24px;
		}
	</style>
	
	<script src="https://code.jquery.com/jquery-1.12.1.min.js"></script>
	
	<script src="https://www.google.com/recaptcha/api.js"></script>
	<script src="{{ cached_asset('vendor/contact_form/assets/js/partial.js') }}"></script>
	<script type="text/javascript">
	$(function() {
		{{-- ContactForm.init({
			base_url: {!! json_encode(route('contact_form::submit', [ ':id:' ])) !!}, 
			csrf_token: $('meta[name="csrf_token"]').attr('content'), 
			success_event_handler: function(id_contact_form) {
				alert($('.contact-form[data-id-contact-form="' + id_contact_form + '"] .success-message').html());
			}, 
			error_event_handler: function(id_contact_form) {
				alert('Error');
			}, 
			before_ajax_event_handler: function(id_contact_form) { }
		}); --}}
	});
	</script>
</head>
<body>
	@yield('content')

	@if (Config::get('neonbug.common.analytics.default_profile_id') != '')
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-{{ Config::get('neonbug.common.analytics.default_profile_id') }}-1', {
			'storage': 'none',
			'clientId': '<?php echo sha1(
				(array_key_exists('HTTP_USER_AGENT',      $_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : '') . 
				(array_key_exists('HTTP_ACCEPT',          $_SERVER) ? $_SERVER['HTTP_ACCEPT'] : '') . 
				(array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '') . 
				(array_key_exists('REMOTE_ADDR',          $_SERVER) ? $_SERVER['REMOTE_ADDR'] : '')
			); ?>'
		});
		ga('send', 'pageview', {'anonymizeIp': true});
	</script>
	@endif
	
	@yield('after-content', '')
</body>
</html>
