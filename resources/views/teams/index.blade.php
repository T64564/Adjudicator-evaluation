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
        Teams 
        <div style="float:right">
            {{ link_to('teams/create',
            'Add New', ['class' => 'btn btn-primary']) }}    
            {{ link_to('teams/import_csv', 
            'Import from csv', ['class' => 'btn btn-primary']) }}    
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
    @foreach ($teams as $team)
        <tr>
            <td>
                {{ $team->id }}
            </td>
            <td>
                {{ $team->name }}
            </td>
            <td>
                @if ($team->active === 1)
                    Yes
                @else
                    No
                @endif
            </td>
            <td>
                {{ link_to('teams/' . $team->id . '/edit', 
                'Edit', ['class' => 'btn btn-primary']) }}
            </td>
            <td>
                {{ Form::open(['method' => 'DELETE', 
                    'url' => ['teams', $team->id],
                'onsubmit' => 'return confirmDelete()']) }}
                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
</table>
@endsection
