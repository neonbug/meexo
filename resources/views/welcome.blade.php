@extends('layouts.master')

@section('content')
	<div class="container">
		<div class="content">
			<div class="title">Laravel 5</div>
			<div class="quote">{{ Inspiring::quote() }}</div>
			
			<div>
				{!! $contact_form !!}
			</div>
		</div>
	</div>
@stop