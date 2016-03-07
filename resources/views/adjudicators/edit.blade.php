@extends('layouts.layout')
@section('content')
<h1>
    <div style="text-align:left">
        Edit: 
        {{ $adj->name }}
        <div style="float:right">
            {{ link_to('adjudicators', 'Back', ['class' => 'btn btn-primary']) }}    
        </div>
    </div>
</h1>
<hr/>
@include('errors.form_errors')
{{ Form::model($adj, 
['method' => 'PATCH', 'route' => ['adjudicators.update', $adj->id]]) }}
@include('adjudicators.form', ['submitButton' => 'Edit'])
{{ Form::close() }}
<hr/>
@stop
