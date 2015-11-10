@extends('common::admin')

@section('head')
	<script src="{{ cached_asset('vendor/common/admin_assets/js/app/dashboard.js') }}"></script>
	<script type="text/javascript">
	var trans = {};
	
	var config = {
		analytics_data_route: {!! json_encode(route('admin-dashboard-analytics-data')) !!}
	};
	
	dashboard.init(trans, config);
	</script>
@stop

@section('content')
	<h3 class="ui header">
		<i class="area chart icon"></i>
		<div class="content">
			{{ trans('common::admin.dashboard.title') }}
		</div>
	</h3>
	<div class="ui divider"></div>
	
	@if ($analytics_supported)
		<div class="analytics-graph-container">
			<div class="ui stackable grid">
				<div class="center aligned eight wide column">
					<div class="ui statistic">
						<div class="value sessions-value">
							<div class="ui active inline small loader"></div>
						</div>
						<div class="label">
							<i class="users icon"></i> {{ trans('common::admin.dashboard.analytics.sessions') }}
						</div>
					</div>
				</div>
				<div class="center aligned eight wide column">
					<div class="ui statistic">
						<div class="value views-value">
							<div class="ui active inline small loader"></div>
						</div>
						<div class="label">
							<i class="unhide icon"></i> {{ trans('common::admin.dashboard.analytics.views') }}
						</div>
					</div>
				</div>
				<div class="sixteen wide column">
					<div class="analytics-graph"></div>
				</div>
			</div>
		</div>
	@endif
@stop
