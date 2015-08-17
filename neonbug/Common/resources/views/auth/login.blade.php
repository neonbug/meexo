<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin login</title>
	
	<script src="{{ asset('vendor/common/admin_assets/jquery-2.1.4.min.js') }}"></script>
	
	<script src="{{ asset('vendor/common/admin_assets/semanticui/semantic.min.js') }}"></script>
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/common/admin_assets/semanticui/semantic.min.css') }}" />
	
	<style type="text/css">
	html { height: 100%; }
	body
	{
		height: 100%;
		padding: 24px;
	}
		body > div
		{
			height: 100%;
		}
			@media (min-height: 800px) {
				body > div > div
				{
					margin-bottom: 160px;
				}
			}
	.column
	{
		max-width: 380px;
	}
	.ui.form
	{
		box-shadow: 0 7px 12px 0 rgba(0, 0, 0, 0.25);
	}
	</style>
	
	<script type="text/javascript">
	$(document).ready(function() {
		$('form').submit(function(e) {
			$('button[type="submit"]').addClass('loading');
		});
	});
	</script>
</head>
<body>

<div class="ui middle aligned center aligned grid">
	<div class="column">
		<div>
			<form class="ui large form" method="POST">
				<div class="ui orange padded segment">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />

					<div class="field {{ $errors->has('username') ? 'error' : '' }}">
						<div class="ui left icon input">
							<i class="user icon"></i>
							<input type="text" name="username" placeholder="Username" value="{{ old('username') }}"
								autofocus />
						</div>
					</div>

					<div class="field {{ $errors->has('password') ? 'error' : '' }}">
						<div class="ui left icon input">
							<i class="lock icon"></i>
							<input type="password" name="password" placeholder="Password">
						</div>
					</div>

					<div>
						<button type="submit" class="ui button orange">
							<i class="power icon"></i>
							Login
						</button>
					</div>
				</div>
			</form>
			
			@if (count($errors) > 0)
				<div class="ui error icon message">
					<!-- <div class="header">Errors</div> -->
					<i class="frown icon"></i>
					<div class="content">
						@foreach ($errors->all() as $error)
							<p>{{ $error }}</p>
						@endforeach
					</div>
				</div>
			@endif
		</div>
	</div>
</div>

</body>
</html>
