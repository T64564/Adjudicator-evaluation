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

