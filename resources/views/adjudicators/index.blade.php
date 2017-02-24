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
        Adjudicators
        <div style="float:right">
            {{ link_to('adjudicators/create',
            'Add New', ['class' => 'btn btn-primary']) }}  
            {{ link_to('adjudicators/import_csv',
            'Import from Csv', ['class' => 'btn btn-primary']) }}  
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
    @foreach ($adjs as $adj)
        <tr>
            <td>
                {{ $adj->id }}
            </td>
            <td>
                {{ $adj->name }}
            </td>
            <td>
                {{ $adj->test_score}}
            </td>
            <td>
                @if ($adj->active === 1)
                    Yes
                @else
                    No
                @endif
            </td>
            <td>
                {{ link_to('adjudicators/' . $adj->id . '/edit',
                'Edit', ['class' => 'btn btn-primary']) }}
            </td>
            <td>
                {{ Form::open(['method' => 'DELETE',
                    'url' => ['adjudicators', $adj->id],
                'onsubmit' => 'return confirmDelete()']) }}
                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
</table>
@endsection
