@extends('layouts.layout')
@section('content')
<h1>
    <div style="text-align:left">
        Round
        : Create new 
        <div style="float:right">
            {!! link_to('rounds', 'Back', 
            ['class' => 'btn btn-primary']) !!}    
        </div>
    </div>
</h1>
<hr/>
@include('errors.form_errors')
{!! Form::open(['route' => 'rounds.store']) !!}
@include('rounds.form', ['submitButton' => 'Create'])
{!! Form::close() !!}
<hr/>
@stop
