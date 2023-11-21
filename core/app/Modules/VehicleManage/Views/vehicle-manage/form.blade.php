<div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
    {!! Form::label('type', 'Type', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('type', $typeList, null, ['class' => 'form-control']) !!}
        {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('plate_no') ? 'has-error' : ''}}">
    {!! Form::label('plate_no', 'Plate No', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('plate_no', null, ['class' => 'form-control']) !!}
        {!! $errors->first('plate_no', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('chassis_no') ? 'has-error' : ''}}">
    {!! Form::label('chassis_no', 'Chassis No', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('chassis_no', null, ['class' => 'form-control']) !!}
        {!! $errors->first('chassis_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
