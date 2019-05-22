@extends('layouts.master')

@section('description', '')
@section('title', [ '404' ])

@section('content')
	<div class="content white contentpage">
		<div class="main-width">
			<h3>
				{{ trans('site::frontend.error.404.title') }}
			</h3>
			
			<div style="text-align: center;">
				{!! trans('site::frontend.error.404.contents') !!}
			</div>
		</div>
	</div>
@stop

@section('after-content')
@stop
