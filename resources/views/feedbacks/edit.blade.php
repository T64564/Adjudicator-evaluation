@extends('layouts.layout')
@section('content')
<h1>
    <div style="text-align:left">
        Feedback Edit
        <div style="float:right">
            {!! link_to('feedbacks/' . $round->id . '/enter_results', 'Back', 
            ['class' => 'btn btn-primary']) !!}    
        </div>
    </div>
</h1>
<hr/>
@include('errors.form_errors')
{{ Form::model($feedback, 
['method' => 'PATCH', 'route' => ['feedbacks.update', $feedback->id]]) }}
@if ($feedback->type === 0)
    @include('feedbacks.form', ['type' => 'team_to_adj', 'submitButton' => 'Edit']) 
@elseif (1 <= $feedback->type and $feedback->type <= 4)
    @include('feedbacks.form', ['type' => 'adj_to_adj', 'submitButton' => 'Edit']) 
@endif
{{ Form::close() }}
<hr/>
@stop
