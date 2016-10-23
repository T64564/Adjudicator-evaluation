@extends('layouts.layout')
@section('content')
@include('errors.form_errors')

<script type="text/javascript">
window.onload = function() {
    $("#table").tablesorter(); 
}
</script>

<h1>
    <div style="text-align:left">
        Feedback {{ $round->name }}
        <div style="float:right">
            {!! link_to('feedbacks/' . $round->id . '/check',
            'Check', ['class' => 'btn btn-primary']) !!}    
            {!! link_to('feedbacks/' . $round->id . '/create_team_to_adj',
            'Add new [Team -> Adjudicator]', ['class' => 'btn btn-primary']) !!}    
            {!! link_to('feedbacks/' . $round->id . '/create_adj_to_adj',
            'Add new [Adjudicator -> Adjudicator]', ['class' => 'btn btn-primary']) !!}    
        </div>
    </div>
</h1>
<hr />

<table id="table" class="table table-striped table-hover">
    <thead>
        <tr>
            @foreach ($heads as $head) 
                <th>
                    {{ $head }}
                </th>
            @endforeach
        </tr>
    </thead>
    @foreach ($feedbacks as $feedback)
        <tr>
            <td>
                {{ $feedback->id }}
            </td>
            <td>
                {{ $feedback->type_name }}
            </td>
            <td>
                {{ $feedback->evaluator_name }}
            </td>
            <td>
                {{ $feedback->evaluatee_name }}
            </td>
            <td>
                {{ $feedback->score }}
            </td>
            <td>
                {{ link_to('feedbacks/' . "$round->id/$feedback->id" . '/edit',
                'Edit', ['class' => 'btn btn-primary']) }}
            </td>
            <td>
                {{ Form::open(['method' => 'DELETE', 'url' => ['feedbacks', $round->id, $feedback->id]]) }}
                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
</table>
@endsection
