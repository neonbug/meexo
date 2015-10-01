@extends('common::admin')

@section('head')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.1/react-with-addons.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.1/JSXTransformer.js"></script>
	<script src="{{ cached_asset('vendor/common/admin_assets/js/app/list.js') }}"></script>
	
	<script type="text/javascript">
	var trans = {
		errors: {
			slug_empty: {!! json_encode(trans('common::admin.add.errors.slug-empty')) !!}, 
			slug_already_exists: {!! json_encode(trans('common::admin.add.errors.slug-already-exists')) !!}
		}, 
		messages: {
			deleted: {!! json_encode(trans('common::admin.add.messages.deleted')) !!}
		}
	};
	var config = {
		delete_route: {!! json_encode($delete_route === null ? null : route($delete_route)) !!}
	};
	
	list.init(trans, config);
	
	$(document).ready(function() {
		$('.ui.accordion').accordion();
	});
	</script>
	
	<script type="text/jsx;harmony=true">
	var items = {!! json_encode($items) !!};
	
	var SourceTypes = React.createClass({
		getInitialState: function() {
			return { search_value: '' };
		}, 
		componentDidMount: function() {
			$('.ui.accordion').accordion();
		}, 
		handleSearchChange: function(event) {
			this.setState({ search_value: event.target.value });
		}, 
		render: function() {
			var items = this.props.items;
			var search_value = this.state.search_value;
			
			return <div>
					<div className="ui icon input">
						<input type="text" placeholder="Search..." value={search_value} onChange={this.handleSearchChange} />
						<i className="search icon"></i>
					</div>
					<div className="ui divider"></div>
					{Object.keys(items).map(function(type) {
						return <SourceType key={type} type={type} data={items[type]} search_value={search_value} />;
					})}
				</div>;
		}
	});
	
	var SourceType = React.createClass({
		render: function() {
			var type = this.props.type;
			var data = this.props.data;
			var search_value = this.props.search_value;
			
			var items = Object.keys(data.items).filter(function(item) {
				return search_value.length == 0 || Object.keys(data.items[item].items).some(function(subitem) {
					return subitem.indexOf(search_value) != -1 || 
						data.items[item].items[subitem].id.indexOf(search_value) != -1;
				});
			});
			
			return <div>
					<h3 className="ui block header">
						<i className={ (type == 'frontend' ? 'sitemap' : 'settings') + ' icon' }></i>
						{data.title}
					</h3>
					<div className="ui styled fluid accordion">
						{items.map(function(package) {
							return <SourcePackage key={type + '-' + package} type={type} package={package} 
								data={data.items[package]} search_value={search_value} />;
						})}
					</div>
				</div>;
		}
	});
	
	var SourcePackage = React.createClass({
		render: function() {
			var package = this.props.package;
			var type = this.props.type;
			var data = this.props.data;
			var search_value = this.props.search_value;
			
			var items = Object.keys(data.items).filter(function(item) {
				return search_value.length == 0 || 
					(item.indexOf(search_value) != -1 || data.items[item].id.indexOf(search_value) != -1);
			});
			
			return <div>
					<div className="title">
						<i className="dropdown icon"></i>
						{data.title}
					</div>
					<div className="content">
						<table className="ui celled striped table">
							<thead><tr>
								<th>Edit</th>
								<th>Code</th>
								<th>En</th>
								<th>Sl</th>
							</tr></thead>
							<tbody>
								{items.map(function(item) {
									return <SourceItem key={type + '-' + package + '-' + item} 
										item={item} data={data.items[item]} />;
								})}
							</tbody>
						</table>
					</div>
				</div>;
		}
	});
	
	var SourceItem = React.createClass({
		render: function() {
			var item = this.props.item;
			var data = this.props.data;
			
			var title = item;
			if (item.indexOf('.') != -1)
			{
				var style = { color: '#bebebe' };
				var title_first_part = item.substring(0, item.lastIndexOf('.')+1);
				var title_last_part = item.substring(item.lastIndexOf('.')+1);
				title = <span><span style={style}>{title_first_part}</span>{title_last_part}</span>;
			}
			
			return <tr className="{ (false ? 'error' : (false ? 'warning' : '')) }">
						<td className="collapsing">
							<a href="#" 
								className="ui label blue only-icon"><i className="write icon"></i></a>
						</td>
						<td>{title}</td>
						<td className="center aligned collapsing">
							<i className="large green checkmark icon"></i>
						</td>
						<td className="center aligned collapsing">
							<i className="large red close icon"></i>
						</td>
					</tr>;
		}
	});
	
	React.render(<SourceTypes items={items} />, document.getElementById('source-items'));
	</script>
	
	<style type="text/css">
	.ui.block.header > span
	{
		display: table-cell;
		line-height: 26px;
		padding-left: 12px;
	}
	.accordion
	{
		margin-bottom: 32px;
	}
	</style>
@stop

@section('content')
	<div id="source-items"></div>
@stop
