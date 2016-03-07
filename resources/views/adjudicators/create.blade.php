@extends('layouts.layout')
@section('content')
<h1>
    <div style="text-align:left">
        Adjudicator
        : Create new 
        <div style="float:right">
            {!! link_to('adjudicators', 'Back', 
            ['class' => 'btn btn-primary']) !!}    
        </div>
    </div>
</h1>
<hr/>
@include('errors.form_errors')
{!! Form::open(['route' => 'adjudicators.store']) !!}
@include('adjudicators.form', ['submitButton' => 'Create'])
{!! Form::close() !!}
<hr/>
@stop
