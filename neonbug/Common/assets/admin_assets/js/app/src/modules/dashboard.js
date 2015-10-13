module.exports = {};

var app_data = {};

function initCharts()
{
	$(document).ready(function() {
		$.get(app_data.config.analytics_data_route, function(analytics_data) {
			data = analytics_data.data;
			max_val = analytics_data.highest_value;
			
			var parseDate = d3.time.format('%Y%m%d').parse;
			data.forEach(function(d) {
				d.date = parseDate(d.date);
			});
			
			$('.sessions-value').html(formatAnalyticsValue((analytics_data.total_sessions).toString()));
			$('.views-value').html(formatAnalyticsValue((analytics_data.total_views).toString()));
			
			d3.select(window).on('resize', resize);
			loadCharts();
		}, 'json');
	});
}

function loadChart(data, max_val, selector, graph_width, graph_height)
{
	var margin = { top: 20, right: 80, bottom: 30, left: 50 },
		width = graph_width - margin.left - margin.right,
		height = graph_height - margin.top - margin.bottom;
	
	var x = d3.time.scale().range([0, width]);
	var y = d3.scale.linear().range([height, 0]);

	var color = d3.scale.category10();
	var x_axis = d3.svg.axis().scale(x).orient('bottom'); //.tickFormat(d3.time.format('%m/%d/%y'));
	var y_axis = d3.svg.axis().scale(y).orient('left').ticks(6);

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

	color.domain([ 'sessions', 'views' ]);

	var analytics = color.domain().map(function(name) {
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
		d3.min(analytics, function(c) { return 0; }),
		d3.max(analytics, function(c) { return max_val; /*d3.max(c.values, function(v) { return v.val; });*/ })
	]);

	svg.append('g')
		.attr('class', 'x axis')
		.attr('transform', 'translate(0,' + height + ')')
		.call(x_axis);

	svg.append('g')
		.attr('class', 'y axis')
		.call(y_axis)
		.append('text')
			.style('text-anchor', 'end');
	
	var gridline_data = [];
	svg.selectAll('.y.axis .tick').each(function(data) {
		var tick = d3.select(this);
		var transform = d3.transform(tick.attr('transform')).translate;
		
		if (data > 0)
		{
			gridline_data.push({ values: [[x_extent[0], transform[1]], [x_extent[1], transform[1]]] });
		}
	});
	
	gridline_data.forEach(function(data) {
		svg.append('line')
			.attr('class', 'gridline')
			.attr('x1', x(data.values[0][0]))
			.attr('x2', x(data.values[1][0]))
			.attr('y1',   data.values[0][1])
			.attr('y2',   data.values[1][1]);
	});

	var analytics_line = svg.selectAll('.analytics_line')
		.data(analytics)
		.enter().append('g')
			.attr('class', 'analytics_line');

	analytics_line.append('path')
		.attr('class', 'line')
		.attr('d', function(d) { return line(d.values); })
		.style('stroke', function(d) { return '#f2711c'; });
	
	analytics_line.append('path')
		.attr('class', 'area')
		.attr('d', function(d) { return area(d.values); })
		.style('fill', function(d) { return '#f2711c'; });
	
	/*analytics.forEach(function(category) {
		category.values.forEach(function(item) {
			analytics_line.append('circle')
				.attr('class', 'dot')
				.attr('r', 4)
				.attr('cx', x(item.date))
				.attr('cy', y(item.val))
				.style('fill', '#f2711c');
		});
	});*/
}

function formatAnalyticsValue(value)
{
	var formatted_val = '';
	var c = 1;
	for (var i=value.length-1; i>=0; i--)
	{
		formatted_val = (c++ % 3 == 0 && i > 0 ? ' ' : '') + value.substring(i, i+1) + formatted_val;
	}
	
	return formatted_val;
}

var aspect = 4;
var chart = null;

var data = null;
var max_val = 0;

var resize_timeout = -1;
function resize()
{
	if (chart != null)
	{
		var width = $('.analytics-graph').width();
		chart.attr('width', width);
		chart.attr('height', Math.round(width / aspect));
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
	
	loadChart(data, max_val, '.analytics-graph', $('.analytics-graph').width(), $('.analytics-graph').width()/aspect);
	chart = $('.analytics-graph > svg');
}

module.exports.init = function(trans, config) {
	app_data.trans = trans;
	app_data.config = config;
	
	$(document).ready(function() {
		initCharts();
	});
};
