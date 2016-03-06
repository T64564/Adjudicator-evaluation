@extends('layouts.layout')
@section('content')
<h1>
    <div style="text-align:left">
        Feedback
        : Edit
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
@include('feedbacks.form', ['submitButton' => 'Create']) 
{{ Form::close() }}
<hr/>
@stop
