@extends('layouts.layout')
@section('content')
<h1>
    <div style="text-align:left">
        Team
        : Create new 
        <div style="float:right">
            {!! link_to('teams.index', 'Back', 
            ['class' => 'btn btn-primary']) !!}    
        </div>
    </div>
</h1>
<hr/>
@include('errors.form_errors')
{!! Form::open(['route' => 'teams.store']) !!}
@include('teams.form', ['submitButton' => 'Create'])
{!! Form::close() !!}
<hr/>
@stop
