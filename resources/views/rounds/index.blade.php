@extends('layouts.layout')
@section('content')
<h1>
    <div style="text-align:left">
        Rounds
        <div style="float:right">
            {!! link_to('rounds/create', 'Add new', 
            ['class' => 'btn btn-primary']) !!}    
            {!! link_to('rounds/import', 'Import .csv', 
            ['class' => 'btn btn-primary']) !!}    
        </div>
    </div>
</h1>
<hr />

<table class="table table-striped table-hover">
    <thead>
        <tr>
            @foreach ($heads as $head) 
                <th>
                    {{ $head }}
                </th>
            @endforeach
        </tr>
    </thead>
    @foreach ($rounds as $round)
        <tr>
            <td>
                {{ $round->name }}
            </td>
            <td>
                {{ $round->silent}}
            </td>
            <td>
                {{ link_to('rounds/' . $round->id . '/edit', 
                'Edit', ['class' => 'btn btn-primary']) }}
            </td>
            <td>
                {{ Form::open(['method' => 'DELETE', 
                'url' => ['adjudicators', $round->id]]) }}
                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
</table>
@endsection
