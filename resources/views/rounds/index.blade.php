@extends('layouts.layout')
@section('content')

<script type="text/javascript">
window.onload = function() {
    $("#table").tablesorter(); 
}
</script>

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
    @foreach ($rounds as $round)
        <tr>
            <td>
                {{ $round->id }}
            </td>
            <td>
                {{ $round->name }}
            </td>
            <td>
                @if ($round->silent === 1)
                    Yes
                @else
                    No
                @endif
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
