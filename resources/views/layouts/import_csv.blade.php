@extends('layouts.layout')
@section('content')
@include('errors.form_errors')
<h1>
    <div style="text-align:left">
        Import
        {{ ucfirst($modelName) }}
        <div style="float:right">
            {{ link_to($modelName . '.import_csv', 'Download Sample CSV', ['class' => 'btn btn-primary']) }}
            {{ link_to($modelName, 'Back', ['class' => 'btn btn-primary']) }}
        </div>
    </div>
</h1>
<hr/>
@if(Session::has('success'))
<h3 class="success">{{ Session::get('success') }}</h3>
@endif
<div class="form-group">
    {!! Form::label('csv', 'Csv file:') !!}
    {!! Form::open(['route' => $modelName . '.import_csv', 'files' => true]) !!}
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
