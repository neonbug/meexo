<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf_token" content="{{ $encrypted_csrf_token }}" />
	
	<title>
		Admin
		@if (isSet($title) && is_array($title))
			@foreach ($title as $title_item)
				| {{ $title_item }}
			@endforeach
		@endif
	</title>
	
	<script src="{{ cached_asset('vendor/common/admin_assets/jquery-2.1.4.min.js') }}"></script>
	
	<script src="{{ cached_asset('vendor/common/admin_assets/semanticui/semantic.min.js') }}"></script>
	<link rel="stylesheet" type="text/css" 
		href="{{ cached_asset('vendor/common/admin_assets/semanticui/semantic.min.css') }}" />
	
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/speakingurl/4.0.0/speakingurl.min.js"></script>
	
	<script src="{{ cached_asset('vendor/common/admin_assets/ckeditor/ckeditor.js') }}"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/ckeditor/adapters/jquery.js') }}"></script>
	
	<script type="text/javascript">
	var admin = {
		content_changed: false, 
		hasContentChanged: function() { return admin.content_changed; }, 
		setContentChanged: function(value) { admin.content_changed = value; }, 
		
		init: function() {
			admin.initCheckboxes();
			admin.initTabs();
			admin.initSlugs();
			admin.initCloseWarning();
			admin.initRichEditors();
		}, 
		initCheckboxes: function() {
			$('.ui.checkbox').checkbox({
				onChange: function() {
					$('[name="' + this.dataset.name + '"]').val(this.checked ? 'true' : 'false');
				}
			});
		}, 
		initTabs: function() {
			$('.menu .item').tab();
		}, 
		initSlugs: function() {
			$('[data-type="slug"]').each(function(idx, item) {
				var generate_from = $('[data-name="' + item.dataset.slugGenerateFrom + '"]', $(item).closest('.tab'));
				generate_from = (generate_from.length == 0 ? null : $(generate_from.get(0)));
				if (generate_from.length == 0) return;
				
				$(item).change(function() {
					this.dataset.slugIsEmpty = (this.value.length == 0 ? 'true' : 'false');
					if (generate_from == null || !this.dataset.slugIsEmpty) return;
					admin.updateSlug($(item), $(generate_from));
				});
				
				if (generate_from != null)
				{
					generate_from.keyup(function() {
						admin.updateSlug($(item), generate_from);
					});
					admin.updateSlug($(item), generate_from);
				}
			});
		}, 
		updateSlug: function(slug_field, generate_from_field) {
			if (slug_field.get(0).dataset.slugIsEmpty == 'false') return;
			slug_field.val(getSlug(generate_from_field.val()));
		}, 
		initCloseWarning: function() {
			$(window).on('beforeunload', function(e) {
				if (!admin.hasContentChanged()) return;
				
				//TODO somehow check if the inputs are *really* different than initial (e.g. a diff against initial state)
				
				var message = 'Are you sure you want to close this page? Any unsaved changes will be gone.';
				
				e.returnValue = message;
				return message;
			});
			
			$('.add input, .add textarea').change(function() { admin.setContentChanged(true); });
			//TODO maybe save all input values here, for diffing above?
		}, 
		initRichEditors: function() {
			$('textarea[data-type="rich_text"]').ckeditor({
				entities: false, 
				baseHref: '{{ url() }}'
			});
		}
	};
	
	$(document).ready(function() {
		admin.init();
	});
	
	$.ajaxPrefilter(function(options, originalOptions, xhr) {
		var token = $('meta[name="csrf_token"]').attr('content');

		if (token) {
			return xhr.setRequestHeader('X-XSRF-TOKEN', token);
		}
	});
	</script>
	
	<style type="text/css">
	body
	{
		height: 100%;
	}
	.main
	{
		display: flex;
		flex-direction: row;
		min-height: 100%;
	}
		.main-menu
		{
			flex: 0 0 auto;
			width: 260px;
			
			background-color: #1b1c1d;
			min-height: 100%;
		}
			/* Smartphones (portrait and landscape) ----------- */
			@media only screen and (min-device-width : 320px) and (max-device-width : 480px) {
				.main-menu
				{
					display: none;
				}
			}
			.main-menu > .header
			{
				color: #ffffff;
				text-align: center;
				
				height: 50px;
				line-height: 50px;
				margin: 0px;
			}
			.main-menu .ui.menu
			{
				margin-top: 0px;
			}
				.main-menu .ui.menu .item
				{
					text-transform: capitalize;
					padding: 1.5em 1.5em;
					font-weight: bold;
				}
				.main-menu .ui.menu .item.level-two
				{
					color: #999999;
					text-transform: capitalize;
					padding: 1.0em 4.5em;
				}
				.main-menu .ui.menu .item.header
				{
					text-transform: uppercase;
				}
					.main-menu .ui.vertical.menu .item > i.icon
					{
						float: left;
						margin-right: 1.3em;
					}
		.main-content
		{
			flex: 1 1 auto;
		}
			.admin-top-menu-divider
			{
				display: inline;
				border-left: 1px solid #ffffff;
				margin-left: 12px;
				margin-right: 12px;
			}
			.admin-top-menu strong
			{
				color: #ffffff;
			}
			.admin-top-menu a
			{
				border-bottom: 1px dotted #ffffff;
			}
			.admin-top-menu a:hover
			{
				border-bottom: 0px;
			}
			
			.main-content .main-inner-content
			{
				padding: 24px;
				padding-top: 10px;
			}
	.inverted a
	{
		color: #ffffff;
	}
	.ui.label.only-icon > .icon
	{
		margin: 0px;
	}
	.add .tab.segment .ui.table
	{
	}
		.add .tab.segment .ui.table tr td
		{
			border-top: 0px;
		}
		.add .tab.segment .ui.table td:first-child
		{
			min-width: 180px;
			font-weight: bold;
			text-transform: capitalize;
		}
	</style>
	
	@yield('head', '')
</head>
<body>
	
	<div class="main">
		<div class="main-menu">
			<h3 class="ui header">Admin menu</h3>
			
			@include('common::admin.menu')
		</div>
		
		<div class="main-content">
			<div class="admin-top-menu ui basic segment inverted orange">
				<div class="ui grid">
					<div class="left floated left aligned ten wide column">
						Home
						@if (isSet($title) && is_array($title))
							@foreach ($title as $title_item)
								<i class="right angle icon divider"></i> {{ $title_item }}
							@endforeach
						@endif
					</div>
					<div class="right floated right aligned six wide column">
						Logged in as <strong>Administrator</strong>
						<div class="admin-top-menu-divider"></div>
						<a href="{{ route('admin-logout') }}">Logout</a>
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
