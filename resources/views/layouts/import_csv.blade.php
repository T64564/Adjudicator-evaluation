@extends('layouts.layout')
@section('content')
@include('errors.form_errors')
<h1>
    Import
    {{ ucfirst($parentRoot) }}
</h1>
<hr/>
@if(Session::has('success'))
    <h3 class="success">{{ Session::get('success') }}</h3>
@endif
<div class="form-group">
    {!! Form::label('csv', 'Csv file:') !!}
    {!! Form::open(['route' => $parentRoot . '.import_csv', 'files' => true]) !!}
    {!! Form::file('csv', ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('deplicatin_label', 'If name deplication found:') !!}
    {!! Form::select('name_dep', 
    ['igrore' => 'ignore', 'update' => 'update'], null, 
    ['class' => 'form-control']) !!}
</div>
<hr/>
<div class="form-group">
    {!! Form::submit('Import', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@endsection
