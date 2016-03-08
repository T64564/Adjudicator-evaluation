@extends('layouts.layout')
@section('content')
@include('errors.form_errors')
<h1>
    <div style="text-align:left">
        Adjudicator Ranking
        <div style="float:right">
            {{ link_to('results/ranking/export_csv',
            'Download csv', ['class' => 'btn btn-primary']) }}    
        </div>
    </div>
</h1>
<hr />
<script type="text/javascript">
window.onload = function() {
    $("#table").tablesorter(); 
}
</script>
<table id='table' class="table table-striped table-hover">
    <thead>
        <tr>
            @foreach ($heads as $head)
                <th>
                    {{ $head }}
                </th>
            @endforeach
        </tr>
    </thead>
    @foreach ($adjudicators as $adjudicator)
        <tr>
            <td>
                {{ $adjudicator->name }}
            </td>
            <td>
                {{ $rankings->test_scores[$adjudicator->id] }}
            </td>
            @foreach ($rounds as $round) 
                <td>
                    {{ $rankings->averages[$adjudicator->id][$round->id] }}
                </td>
            @endforeach
            <td>
                {{ $rankings->averages[$adjudicator->id]['round'] }}
            </td>
            <td>
                {{ $rankings->averages[$adjudicator->id]['feedback'] }}
            </td>
        </tr>
    @endforeach
</table>
@endsection
