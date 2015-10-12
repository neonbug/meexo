@extends('common::admin')

@section('head')
<style type="text/css">
.analytics-sessions-graph, .analytics-views-graph
{
	font: 10px sans-serif;
}

.axis path, .axis line
{
	fill: none;
	stroke: #aaaaaa;
	shape-rendering: crispEdges;
}
.axis text
{
	fill: #aaaaaa;
}

.line
{
	fill: none;
	stroke-width: 2.5px;
}
.gridline
{
	stroke-width: 1px;
}

.area
{
	opacity: 0.5;
}
</style>
<script type="text/javascript">
function loadChart(data, data_key, max_val, selector, graph_width, graph_height)
{
	var margin = { top: 20, right: 80, bottom: 30, left: 50 },
		width = graph_width - margin.left - margin.right,
		height = graph_height - margin.top - margin.bottom;
	
	var x = d3.time.scale().range([0, width]);
	var y = d3.scale.linear().range([height, 0]);

	var color = d3.scale.category10();
	var xAxis = d3.svg.axis().scale(x).orient('bottom'); //.tickFormat(d3.time.format('%m/%d/%y'));
	var yAxis = d3.svg.axis().scale(y).orient('left');

	var line = d3.svg.line()
		.interpolate('cardinal')
		.tension(0.8)
		.x(function(d) { return x(d.date); })
		.y(function(d) { return y(d.val); });
	
	var line_gridline = d3.svg.line()
		.x(function(d) { return x(d[0]); })
		.y(function(d) { return y(d[1]); });
	
	var area = d3.svg.area()
		.interpolate('cardinal')
		.tension(0.8)
		.x(function(d) { return x(d.date); })
		.y0(height)
		.y1(function(d) { return y(d.val); });
	
	d3.select(selector + ' > svg').remove();
	var svg = d3.select(selector).append('svg')
		.attr('width', width + margin.left + margin.right)
		.attr('height', height + margin.top + margin.bottom)
		.attr('viewBox', '0 0 ' + graph_width + ' ' + graph_height)
		.attr('perserveAspectRatio', 'xMinYMid')
		.append('g')
		.attr('transform', 'translate(' + margin.left + ',' + margin.top + ')');

	color.domain([ data_key ]);

	var cities = color.domain().map(function(name) {
		return {
			name: name,
			values: data.map(function(d) {
				return {date: d.date, val: +d[name]};
			})
		};
	});
	
	var x_extent = d3.extent(data, function(d) { return d.date; });
	x.domain(x_extent);

	y.domain([
		d3.min(cities, function(c) { return 0; }),
		d3.max(cities, function(c) { return max_val; /*d3.max(c.values, function(v) { return v.val; });*/ })
	]);

	svg.append('g')
		.attr('class', 'x axis')
		.attr('transform', 'translate(0,' + height + ')')
		.call(xAxis);

	svg.append('g')
		.attr('class', 'y axis')
		.call(yAxis)
		.append('text')
		.attr('transform', 'rotate(-90)')
		.attr('y', 6)
		.attr('dy', '.71em')
		.style('text-anchor', 'end');
	
	var gridline_data = [];
	for (var y_val=max_val/6; y_val<max_val; y_val += max_val/6)
	{
		gridline_data.push({ values: [[x_extent[0], y_val], [x_extent[1], y_val]] });
	}
	
	gridline_data.forEach(function(data) {
		svg.append('path')
			//.data([ { date: new Date(2015, 8, 13), value: 0 }, { date: new Date(), value: 1600 } ])
			.data([ data ])
			.attr('class', 'gridline')
			.style('stroke', function(d) { return '#eeeeee'; })
			.attr('d', function(d) { console.log(d.values); return line_gridline(d.values); });
	});

	var city = svg.selectAll('.city')
		.data(cities)
		.enter().append('g')
		.attr('class', 'city');

	city.append('path')
		.attr('class', 'line')
		.attr('d', function(d) { return line(d.values); })
		.style('stroke', function(d) { return '#f2711c'; });
	
	city.append('path')
		.attr('class', 'area')
		.attr('d', function(d) { return area(d.values); })
		.style('fill', function(d) { return '#f2711c'; });
	
	/*cities.forEach(function(category) {
		category.values.forEach(function(item) {
			city.append('circle')
				.attr('class', 'dot')
				.attr('r', 4)
				.attr('cx', x(item.date))
				.attr('cy', y(item.val))
				.style('fill', '#f2711c');
		});
	});*/
}

$(document).ready(function() {
	$.get({!! json_encode(route('admin-dashboard-analytics-data')) !!}, function(analytics_data) {
		data = analytics_data.data;
		max_val = analytics_data.highest_value;
		
		var parseDate = d3.time.format('%Y%m%d').parse;
		data.forEach(function(d) {
			d.date = parseDate(d.date);
		});
		
		$('.sessions-value').html(analytics_data.total_sessions);
		$('.views-value').html(analytics_data.total_views);
		
		d3.select(window).on('resize', resize);
		loadCharts();
	}, 'json');
});

var aspect = 2;
var chart_1 = null;
var chart_2 = null;

var data = null;
var max_val = 0;

var resize_timeout = -1;
function resize()
{
	if (chart_1 != null)
	{
		var width = $('.analytics-sessions-graph').width();
		chart_1.attr('width', width);
		chart_1.attr('height', Math.round(width / aspect));
	}
	
	if (chart_2 != null)
	{
		var width = $('.analytics-views-graph').width();
		chart_2.attr('width', width);
		chart_2.attr('height', Math.round(width / aspect));
	}
	
	if (resize_timeout != -1) clearTimeout(resize_timeout);
	resize_timeout = setTimeout(function() {
		resize_timeout = -1;
		loadCharts();
	}, 1000);
}
function loadCharts()
{
	if (data == null) return;
	
	loadChart(data, 'sessions', max_val, '.analytics-sessions-graph', 
		$('.analytics-sessions-graph').width(), $('.analytics-sessions-graph').width()/aspect);
	chart_1 = $('.analytics-sessions-graph > svg');
	
	loadChart(data, 'views', max_val, '.analytics-views-graph', 
		$('.analytics-views-graph').width(), $('.analytics-views-graph').width()/aspect);
	chart_2 = $('.analytics-views-graph > svg');
}
</script>
@stop

@section('content')
	<h2 class="ui header">
		<i class="area chart icon"></i>
		<div class="content">
			{{ trans('common::admin.dashboard.title') }}
		</div>
	</h2>
	<div class="ui divider"></div>
	
	<div class="ui grid">
		<div class="stackable two column row center aligned">
			<div class="column">
				<div class="ui statistic">
					<div class="value sessions-value">
						<div class="ui active inline small loader"></div>
					</div>
					<div class="label">
						<i class="users icon"></i> Sessions
					</div>
				</div>
			</div>
			<div class="column">
				<div class="ui statistic">
					<div class="value views-value">
						<div class="ui active inline small loader"></div>
					</div>
					<div class="label">
						<i class="unhide icon"></i> Views
					</div>
				</div>
			</div>
		</div>
		<div class="stackable two column row">
			<div class="column">
				<div class="analytics-sessions-graph"></div>
			</div>
			<div class="column">
				<div class="analytics-views-graph"></div>
			</div>
		</div>
	</div>
@stop
