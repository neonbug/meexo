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
			var languages = this.props.languages;
			var search_value = this.state.search_value;
			
			return <div>
					<div className="ui icon input">
						<input type="text" placeholder="{{ trans('translation::admin.list.search-placeholder') }}" 
							value={search_value} onChange={this.handleSearchChange} />
						<i className="search icon"></i>
					</div>
					<div className="ui divider"></div>
					{Object.keys(items).map(function(type) {
						return <SourceType key={type} type={type} data={items[type]} search_value={search_value}
							languages={languages} />;
					})}
				</div>;
		}
	});
	
	var SourceType = React.createClass({
		render: function() {
			var type = this.props.type;
			var data = this.props.data;
			var search_value = this.props.search_value;
			var languages = this.props.languages;
			
			var items = Object.keys(data.items).filter(function(item) {
				return search_value.length == 0 || Object.keys(data.items[item].items).some(function(subitem) {
					var found = subitem.indexOf(search_value) != -1 || 
						data.items[item].items[subitem].id.toLowerCase().indexOf(search_value.toLowerCase()) != -1;
					
					if (!found)
					{
						found = Object.keys(data.items[item].items[subitem].translations).some(function(id_language) {
							return (data.items[item].items[subitem].translations[id_language].toLowerCase()
								.indexOf(search_value.toLowerCase()) != -1);
						});
					}
					
					return found;
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
								data={data.items[package]} search_value={search_value} languages={languages} />;
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
			var languages = this.props.languages;
			
			var items = Object.keys(data.items).filter(function(item) {
				var found = search_value.length == 0 || 
					(item.toLowerCase().indexOf(search_value.toLowerCase()) != -1 || 
						data.items[item].id.toLowerCase().indexOf(search_value.toLowerCase()) != -1);
				
				if (!found)
				{
					found = Object.keys(data.items[item].translations).some(function(id_language) {
						return (data.items[item].translations[id_language].toLowerCase()
							.indexOf(search_value.toLowerCase()) != -1);
					});
				}
				
				return found;
			});
			
			var head_items = [ {!! json_encode(trans('translation::admin.list.field-title.edit')) !!}, 
				{!! json_encode(trans('translation::admin.list.field-title.code')) !!} ];
			languages.forEach(function(language) {
				head_items.push(language.locale);
			});
			
			var head = <tr>{head_items.map(function(item) {
				return <th>{item}</th>;
			})}</tr>;
			
			return <div>
					<div className="title">
						<i className="dropdown icon"></i>
						{data.title}
					</div>
					<div className="content">
						<table className="ui celled striped table unstackable">
							<thead>{head}</thead>
							<tbody>
								{items.map(function(item) {
									return <SourceItem key={type + '-' + package + '-' + item} 
										item={item} data={data.items[item]} languages={languages} />;
								})}
							</tbody>
						</table>
					</div>
				</div>;
		}
	});
	
	var SourceItem = React.createClass({
		edit_link: {!! json_encode(route('translation::admin::edit', '==id==')) !!}, 
		render: function() {
			var item = this.props.item;
			var data = this.props.data;
			var languages = this.props.languages;
			
			var title = item;
			if (item.indexOf('.') != -1)
			{
				var style = { color: '#bebebe' };
				var title_first_part = item.substring(0, item.lastIndexOf('.')+1);
				var title_last_part = item.substring(item.lastIndexOf('.')+1);
				title = <span><span style={style}>{title_first_part}</span>{title_last_part}</span>;
			}
			
			var edit_link = this.edit_link.replace('==id==', data.id);
			
			return <tr className="{ (false ? 'error' : (false ? 'warning' : '')) }">
						<td className="collapsing">
							<a href={edit_link} className="ui label blue only-icon">
								<i className="write icon"></i>
							</a>
						</td>
						<td>{title}</td>
						{languages.map(function(language) {
							var cls = 'large icon ' + 
								(data.translations[language.id_language] != undefined && 
									data.translations[language.id_language].length > 0 ? 
									'green checkmark' : 'red close');
							
							return <td className="center aligned collapsing">
								<i className={cls}></i>
							</td>;
						})}
					</tr>;
		}
	});
	
	var items = {!! json_encode($items) !!};
	var languages = {!! json_encode($languages) !!};
	
	React.render(<SourceTypes items={items} languages={languages} />, document.getElementById('source-items'));
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
