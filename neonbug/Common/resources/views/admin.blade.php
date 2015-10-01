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
	
	<script src="{{ cached_asset('vendor/common/admin_assets/js/jquery-2.1.4.min.js') }}"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/js/semanticui/semantic.min.js') }}"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/js/ckeditor/ckeditor.js') }}"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/js/flow.min.js') }}"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/js/html.sortable.min.js') }}"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/js/jquery.noty.packaged.min.js') }}"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/js/noty/custom_relax.js') }}"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/js/app/main.js') }}"></script>
	
	<script type="text/javascript">
	var trans = {
		messages: {
			close_page: {!! json_encode(trans('common::admin.main.messages.close-page')) !!}, 
			logged_in: {!! json_encode(trans('common::admin.main.messages.logged-in')) !!}
		}
	};
	
	var config = {
		check_token_route: {!! json_encode(url() . '/check-token') !!}, 
		login_route: {!! json_encode(route('admin-login')) !!}, 
		token_route: {!! json_encode(route('admin-token')) !!}, 
	};
	
	main.init(trans, config);
	</script>
	
	<link rel="stylesheet" type="text/css" 
		href="{{ cached_asset('vendor/common/admin_assets/js/semanticui/semantic.min.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ cached_asset('vendor/common/admin_assets/css/pikaday.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ cached_asset('vendor/common/admin_assets/css/animate.css') }}" />
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
	
	<div class="login-modal-template" style="display: none;">
		<div class="ui tiny modal orange login-modal">
			<div class="header">
				{{ trans('common::admin.login-popup.title') }}
			</div>
			<div class="content">
				<div class="ui icon message">
					<div class="content">
						{{ trans('common::admin.login-popup.description') }}
					</div>
				</div>
				
				<div class="ui error icon message hidden">
					<i class="frown icon"></i>
					<div class="content">
						@foreach ($errors->all() as $error)
							<p>{{ $error }}</p>
						@endforeach
					</div>
				</div>
				
				<div class="field">
					<div class="ui left icon input" data-name="username">
						<i class="user icon"></i>
						<input type="text" name="username" placeholder="{{ trans('common::admin.login-popup.username') }}" 
							value="{{ old('username') }}" autofocus />
					</div>
				</div>
				
				<div class="field">
					<div class="ui left icon input" data-name="password">
						<i class="lock icon"></i>
						<input type="password" name="password" placeholder="{{ trans('common::admin.login-popup.password') }}">
					</div>
				</div>
			</div>
			<div class="actions">
				<div class="ui right labeled icon button orange login-button">
					{{ trans('common::admin.login-popup.login-button') }}
					<i class="power icon"></i>
				</div>
			</div>
		</div>
	</div>

</body>
</html>
