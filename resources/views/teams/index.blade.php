@extends('layouts.layout')
@section('content')
@include('errors.form_errors')
<h1>
    <div style="text-align:left">
        Teams 
        <div style="float:right">
            {!! link_to('teams/create',
            'Add new', ['class' => 'btn btn-primary']) !!}    
            {!! link_to('teams/import_csv', 
            'Import.csv', ['class' => 'btn btn-primary']) !!}    
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
    @foreach ($teams as $team)
        <tr>
            <td>
                {{ $team->name }}
            </td>
            <td>
                {{ $team->active}}
            </td>
            <td>
                {{ link_to('teams/' . $team->id . '/edit', 
                'Edit', ['class' => 'btn btn-primary']) }}
            </td>
            <td>
                {{ Form::open(['method' => 'DELETE', 
                'url' => ['adjudicators', $team->id]]) }}
                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
</table>
@endsection
