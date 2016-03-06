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
    @foreach ($values as $value)
        <tr>
            <td>
                {{ link_to('rounds/' . $value->id . '/edit', $value->name) }}
            </td>
            <td>
                {{ $value->silent}}
            </td>
            <td>
                {{-- {!! delete_form([$controller, $value->id]) !!} --}}
            </td>
        </tr>
    @endforeach
</table>
@endsection
