@extends('layouts.layout')
@section('content')
<h1>
    <div style="text-align:left">
        Feedback Create
        <div style="float:right">
            {!! link_to('feedbacks/' . $round->id . '/enter_results', 'Back', 
            ['class' => 'btn btn-primary']) !!}    
        </div>
    </div>
</h1>
<hr/>
@include('errors.form_errors')
{!! Form::open(['route' => 'feedbacks.store']) !!}
@if ($from_team === true)
    @include('feedbacks.form', ['type' => 'team_to_adj', 'submitButton' => 'Create']) 
@elseif ($from_team === false)
    @include('feedbacks.form', ['type' => 'adj_to_adj', 'submitButton' => 'Create']) 
@endif
{{ Form::close() }}
<hr/>
@stop
