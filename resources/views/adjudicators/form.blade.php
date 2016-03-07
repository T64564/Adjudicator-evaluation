<div class="form-group">
    {!! Form::hidden('id', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('test_score', 'Test score:') !!}
    {!! Form::text('test_score', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('active', 'Active:') !!}
    {!! Form::checkbox('active', null, null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::submit($submitButton, 
    ['class' => 'btn btn-primary form-control']) !!}
</div>
