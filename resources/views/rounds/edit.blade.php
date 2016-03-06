@extends('layouts.layout')
@section('content')
<h1>
    <div style="text-align:left">
        Edit: 
        {{ $round->name }}
        <div style="float:right">
            {{ link_to('rounds', 'Back', ['class' => 'btn btn-primary']) }}    
        </div>
    </div>
</h1>
<hr/>
@include('errors.form_errors')
{{ Form::model($round, 
['method' => 'PATCH', 'route' => ['rounds.update', $round->id]]) }}
@include('rounds.form', ['submitButton' => 'Edit'])
{{ Form::close() }}
<hr/>
@stop
