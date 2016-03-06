@extends('layouts.layout')
@section('content')
@include('errors.form_errors')
<h1>
    Feedback management
</h1>
<hr />

<table class="table table-striped table-hover">
    @foreach ($rounds as $round)
        <tr>
            <td>
                {{ $round->name }}
            </td>
            <td>
                {!! link_to('feedbacks/' . $round->id . '/enter_results', 
                'Enter', ['class' => 'btn btn-primary']) !!}    
            </td>
        </tr>
    @endforeach
</table>
@endsection
