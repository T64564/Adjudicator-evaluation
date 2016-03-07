@extends('layouts.layout')
@section('content')
<h1>
    <div style="text-align:left">
        Edit: 
        {{ $team->name }}
        <div style="float:right">
            {{ link_to('teams', 'Back', ['class' => 'btn btn-primary']) }}    
        </div>
    </div>
</h1>
<hr/>
@include('errors.form_errors')
{{ Form::model($team, 
['method' => 'PATCH', 'route' => ['teams.update', $team->id]]) }}
@include('teams.form', ['submitButton' => 'Edit'])
{{ Form::close() }}
<hr/>
@stop
