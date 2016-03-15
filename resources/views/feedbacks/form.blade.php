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
    {{ Form::label('evaluatee_id', 'Evaluatee:') }}
    {{ Form::select('evaluatee_id', $adj_names, 
    null,
    ['class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('evaluator_id', 'Evaluator:') }}
    {{ Form::select('evaluator_id', $team_names, 
    null,
    ['id' => 'evaluater_id', 'class' => 'form-control']) }}
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

<script type="text/javascript">
var team_names = JSON.parse('<?php echo json_encode($team_names) ?>');
var adj_names = JSON.parse('<?php echo json_encode($adj_names) ?>');

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
}

function updateEvaluator(value) {
    $('[name = evaluator_id]').children('option').remove();

    if (value == 0) {
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
