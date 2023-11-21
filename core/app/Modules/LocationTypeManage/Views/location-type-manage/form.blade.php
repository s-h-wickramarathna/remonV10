<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Name', ['class' => 'col-lg-3 col-md-3 col-ms-3 col-xs-3 control-label required']) !!}
    <div class="col-lg-6 col-md-6 col-ms-6 col-xs-12">
        {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
        @if($errors->has('name'))
            <p class="help-block">{{ $errors->first('name') }}</p>
        @endif
    </div>
</div><div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
    {!! Form::label('description', 'Description', ['class' => 'col-lg-3 col-md-3 col-ms-3 col-xs-3 control-label']) !!}
    <div class="col-lg-6 col-md-6 col-ms-6 col-xs-12">
        {!! Form::textarea('description', old('description'), ['class' => 'form-control']) !!}
        @if($errors->has('description'))
            <p class="help-block">{{ $errors->first('description') }}</p>
        @endif
    </div>
</div>

<div class="form-group">
    <div class="col-lg-offset-3 col-md-offset-3 col-ms-offset-3 col-lg-3 col-md-3 col-ms-3 col-xs-3">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
