@extends('layouts.layout')
@section('content')
@include('errors.form_errors')
<h1>
    Restore
</h1>
<hr/>
@if(Session::has('success'))
    <h3 class="success">{{ Session::get('success') }}</h3>
@endif
<div class="form-group">
    {!! Form::label('sql ', 'Choose SQL File:') !!}
    {!! Form::open(['route' => 'restore', 'files' => true]) !!}
    {!! Form::file('sql', ['class' => 'form-control']) !!}
</div>
<hr/>
<div class="form-group">
    {!! Form::submit('Restore', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@endsection
