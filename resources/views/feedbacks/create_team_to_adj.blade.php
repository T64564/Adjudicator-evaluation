@extends('layouts.layout')
@section('content')
<h1>
    <div style="text-align:left">
        Feedback
        : Create New [ Team -> Adjudicator ]
        <div style="float:right">
            {!! link_to('feedbacks/' . $round->id . '/enter_results', 'Back', 
            ['class' => 'btn btn-primary']) !!}    
        </div>
    </div>
</h1>
<hr/>
@include('errors.form_errors')
{!! Form::open(['route' => 'feedbacks.store']) !!}

<div class="form-group">
    {{ Form::hidden('id', null, ['class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::hidden('round_id', $round->id, ['class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('type', 'Type:') }}
    {{ Form::select('type', $types,
    null,
    ['id' => 'type', 'class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('evaluator_id', 'Evaluator:') }}
    {{ Form::select('evaluator_id', $team_names, 
    null,
    ['id' => 'evaluater_id', 'class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('evaluatee_id', 'Evaluatee:') }}
    {{ Form::select('evaluatee_id', $adj_names, 
    null,
    ['class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('score', 'Score:') }}
    {{ Form::text('score', 
    null,
    ['class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::submit('Create', 
    ['class' => 'btn btn-primary form-control']) }}
</div>
{!! Form::close() !!}
<hr/>
@stop
