@extends('layouts.layout')
@section('content')
@include('errors.form_errors')
<h1>
    Adjudicator Ranking
</h1>
<hr />

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>
                {{ 'Name' }}
            </th>
            @foreach ($rounds as $round) 
                <th>
                    {{ $round->name }}
                </th>
            @endforeach
        </tr>
    </thead>
    @foreach ($values as $value)
        <tr>
            <td>
                {{ $value->name }}
            </td>
            <td>
                {{ $value->active}}
            </td>
            <td>
                {{-- {!! delete_form([$controller, $value->id]) !!} --}}
            </td>
        </tr>
    @endforeach
</table>
@endsection
