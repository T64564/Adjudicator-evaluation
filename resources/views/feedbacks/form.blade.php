<div class="form-group">
    {{ Form::hidden('id', null, ['class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::hidden('round_id', $round->id, ['class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('type', 'Type:') }}
    {{ Form::select('type', $types,
    null,
    ['id' => 'type', 'class' => 'form-control', 
    'onchange' => 'updateEvaluator(this.value)']) }}
</div>
<div class="form-group">
    {{ Form::label('evaluator_id', 'Evaluator:') }}
    {{ Form::select('evaluator_id', $team_names, 
    null,
    ['id' => 'evaluater_id', 'class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('evaluatee_id', 'Evaluatee:') }}
    {{ Form::select('evaluatee_id', $adj_names, 
    null,
    ['class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('score', 'Score:') }}
    {{ Form::text('score', 
    null,
    ['class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::submit($submitButton, 
    ['class' => 'btn btn-primary form-control']) }}
</div>

<?php
foreach ($team_names as $k => $v) {
$team_names[$k] = str_replace("'", "_", $team_names[$k]);
}
foreach ($adj_names as $v) {
$adj_names[$k] = str_replace("'", "_", $adj_names[$k]);
}
?>
<script type="text/javascript">
// TODO: なぜかエラーが起こることがある(原因不明)
var team_names = JSON.parse('<?php echo json_encode($team_names) ?>');
var adj_names = JSON.parse('<?php echo json_encode($adj_names) ?>');
var prev_type;

window.onload = function() {
    var type = $('#type').val();
    if (type != 0) {
        updateEvaluator(type);

        var evaluator_id = <?php 
            echo isset($feedback->evaluator_id) ? $feedback->evaluator_id : -1; 
        ?>

            if (evaluator_id != -1) {
                $('[name = evaluator_id]').val(evaluator_id);
            }
    }
    prev_type = type;
}

function updateEvaluator(type) {
    if (prev_type == 0 && type == 0) {
        return;
    }
    if ((prev_type == 1 || type == 2 || type == 3)
            && (type == 1 || type == 2 || type == 3)) {
        return;
    }

    $('[name = evaluator_id]').children('option').remove();

    if (type == 0) {
        var keys = Object.keys(team_names);
        for (var key in team_names) {
            console.log(team_names[key]);
            $('[name = evaluator_id]').
                append($('<option value=' + key + '>' 
                            + team_names[key] + '</option>'));
        }
    } else {
        for (var key in adj_names) {
            console.log(adj_names[key]);
            $('[name = evaluator_id]').
                append($('<option value=' + key + '>' 
                            + adj_names[key] + '</option>'));
        }
    }
}
</script>
