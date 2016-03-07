@extends('layouts.layout')
@section('content')
@include('errors.form_errors')
<h1>
    Adjudicator Ranking
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
            <th>
                {{ 'Name' }}
            </th>
            <th>
                {{ 'Test score' }}
            </th>
            @foreach ($rounds as $round) 
                <th>
                    {{ $round->name . ' avg'}}
                </th>
            @endforeach
            <th>
                {{ 'Avg of each avg of round' }}
            </th>
            <th>
                {{ 'Avg of each feedback' }}
            </th>
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
