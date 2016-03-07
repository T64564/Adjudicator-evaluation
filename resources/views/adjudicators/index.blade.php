@extends('layouts.layout')
@section('content')
@include('errors.form_errors')
<h1>
    <div style="text-align:left">
        Adjudicators
        <div style="float:right">
            {{ link_to('adjudicators/create',
            'Add new', ['class' => 'btn btn-primary']) }}    
            {{ link_to('adjudicators/import_csv',
            'Import .csv', ['class' => 'btn btn-primary']) }}    
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
    @foreach ($adjs as $adj)
        <tr>
            <td>
                {{ $adj->name }}
            </td>
            <td>
                {{ $adj->test_score}}
            </td>
            <td>
                {{ $adj->active}}
            </td>
            <td>
                {{ link_to('adjudicators/' . $adj->id . '/edit', 
                'Edit', ['class' => 'btn btn-primary']) }}
            </td>
            <td>
                {{ Form::open(['method' => 'DELETE', 
                'url' => ['adjudicators', $adj->id]]) }}
                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
</table>
@endsection
