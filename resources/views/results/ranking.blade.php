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
        Adjudicator Ranking
        <div style="float:right">
            {{ link_to('results/ranking/export_csv',
            'Download csv', ['class' => 'btn btn-primary']) }}    
        </div>
    </div>
</h1>
<hr />
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
                    <?php 
                    $score = $rankings->averages[$adjudicator->id][$round->id];
                    if ($score !== null) {
                    echo round($score, 5);
                    } else {
                    echo 'N/a';
                    }
                    ?>
                </td>
            @endforeach
            <td>
                {{ round($rankings->averages[$adjudicator->id]['round'], 5) }}
            </td>
            <td>
                {{ round($rankings->averages[$adjudicator->id]['feedback'], 5) }}
            </td>
            <td>
                {{ round($rankings->averages[$adjudicator->id]['4:6'], 5) }}
            </td>
            <td>
                {{ round($rankings->averages[$adjudicator->id]['2:8'], 5) }}
            </td>
            <td>
                {{ round($rankings->averages[$adjudicator->id]['ignore_test'], 5) }}
            </td>
        </tr>
    @endforeach
</table>
@endsection
