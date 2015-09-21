<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf_token" content="{{ $encrypted_csrf_token }}" />
	
	<title>
		{{ trans('common::admin.header.title') }}
		@if (isSet($title) && is_array($title))
			@foreach ($title as $title_item)
				| {{ $title_item }}
			@endforeach
		@endif
	</title>
	
	<script type="text/javascript">
	var require = {
		paths: {
			jquery:      'jquery-2.1.4.min', 
			moment:      'moment-with-locales.min', 
			speakingurl: 'speakingurl.min', 
			pikaday:     'pikaday/pikaday', 
			sortable:    'html.sortable.min'
		}, 
		config: {
			moment: { noGlobal: true }
		}
	};
	</script>
	
	<script src="{{ cached_asset('vendor/common/admin_assets/js/jquery-2.1.4.min.js') }}"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/js/semanticui/semantic.min.js') }}"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/js/ckeditor/ckeditor.js') }}"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/js/flow.min.js') }}"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/js/html.sortable.min.js') }}"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/js/require.js') }}" 
		data-main="{{ url() . '/vendor/common/admin_assets/js/main' }}"></script>

	<link rel="stylesheet" type="text/css" 
		href="{{ cached_asset('vendor/common/admin_assets/js/semanticui/semantic.min.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ cached_asset('vendor/common/admin_assets/js/pikaday/pikaday.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ cached_asset('vendor/common/admin_assets/css/main.css') }}" />
	
	@yield('head', '')
</head>
<body>
	
	<div class="main">
		<div class="main-menu">
			<h3 class="ui header">{{ trans('common::admin.menu.title') }}</h3>
			
			@include('common::admin.menu')
		</div>
		
		<div class="main-content">
			<div class="admin-top-menu ui basic segment inverted orange">
				<div class="ui grid">
					<div class="left floated left aligned ten wide column">
						{{ trans('common::admin.breadcrumbs.first-item') }}
						@if (isSet($title) && is_array($title))
							@foreach ($title as $title_item)
								<i class="right angle icon divider"></i> {{ $title_item }}
							@endforeach
						@endif
					</div>
					<div class="right floated right aligned six wide column">
						{!! trans('common::admin.header.logged-in-as', [ 'name' => $user->name ]) !!}
						<div class="admin-top-menu-divider"></div>
						<a href="{{ route('admin-logout') }}">{{ trans('common::admin.header.logout') }}</a>
						<i class="sign out icon"></i>
					</div>
				</div>
			</div>
			
			<div class="main-inner-content ui basic segment">
				@yield('content')
			</div>
		</div>
	</div>
	
</body>
</html>
