<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label class="label-control col-lg-3 col-md-3 col-ms-3 col-xs-3 required text-right">Name</label>
    <div class="col-lg-6 col-md-6 col-ms-6 col-xs-6">
        <input type="text" name="name" class="form-control" placeholder="Enter location name." 
        value="{{isset($location)? $location->name : old('name') }}">
        @if($errors->has('name'))
            <p class="help-block">{{ $errors->first('name') }}</p>
        @endif
    </div>
</div>
<div class="form-group {{ $errors->has('locationType') ? 'has-error' : ''}}">
    <label class="label-control col-lg-3 col-md-3 col-ms-3 col-xs-3 required text-right">Location Type</label>
    <div class="col-lg-6 col-md-6 col-ms-6 col-xs-6">
        <select name="locationType" class="form-control chosen">
            <option value="">-- select location type --</option>
            @if(count($types) > 0)
                @foreach($types as $type)
                    @if($type->id == isset($location->location_type)? $location->location_type : old('locationType'))
                        <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                    @else
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endif
                @endforeach
            @endif
        </select>
        @if($errors->has('locationType'))
            <p class="help-block">{{ $errors->first('locationType') }}</p>
        @endif
    </div>
</div>
<div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
    <label class="label-control col-lg-3 col-md-3 col-ms-3 col-xs-3 required text-right">Address</label>
    <div class="col-lg-6 col-md-6 col-ms-6 col-xs-6">
        <textarea style="resize: none;" name="address" class="form-control" placeholder="Enter location address.">{{ isset($location->address)? $location->address : old('address') }}</textarea>
        @if($errors->has('address'))
            <p class="help-block">{{ $errors->first('address') }}</p>
        @endif
    </div>
</div>
<div class="form-group {{ $errors->has('remark') ? 'has-error' : ''}}">
    <label class="label-control col-lg-3 col-md-3 col-ms-3 col-xs-3 text-right">Remark</label>
    <div class="col-lg-6 col-md-6 col-ms-6 col-xs-6">
        <textarea style="resize: none;" name="remark" class="form-control" rows="5" placeholder="Enter remark.">{{ isset($location->remark)? $location->remark : old('remark') }}</textarea>
        @if($errors->has('remark'))
            <p class="help-block">{{ $errors->first('remark') }}</p>
        @endif
    </div>
</div>
<div class="form-group">
    <label class="control-label col-lg-3 col-md-3 col-ms-3 col-xs-3">Images</label>
    <div class="col-lg-3 col-md-3 col-ms-3 col-xs-3">
        <span class="btn btn-success btn-file btn-block">
            Icon Image <span class="fa fa-image"></span><input type="file" name="img_icon" id="img_icon">
        </span>
        <p style="color:#a94442;padding-top: 10px;display: block;" id="note">Note: Only accepted jpg, jpeg, png, svg</p>
        <br>
        <img id="icon_preview" width="50">
    </div>
    <div class="col-lg-3 col-md-3 col-ms-3 col-xs-3">
        <span class="btn btn-success btn-file btn-block">
            Location Image <span class="fa fa-image"></span><input type="file" name="img_location" id="img_location">
        </span>
        <br>
        <img id="img_preview" width="50">
    </div>
</div>
<div class="form-group {{ $errors->has('radius') ? 'has-error' : ''}}">
    <label class="label-control col-lg-3 col-md-3 col-ms-3 col-xs-3 text-right">Radius</label>
    <div class="col-lg-6 col-md-6 col-ms-6 col-xs-6">
        <select name="radius" class="form-control">
            <option value="">-- select radius --</option>
            @if(isset($location->radius)? $location->radius : old('radius') == '1')
                <option value="0.5">0.5km</option>
                <option value="1" selected>1km</option>
                <option value="1.5">1.5km</option>
                <option value="2">2km</option>
            @elseif(isset($location->radius)? $location->radius : old('radius') == '0.5')
                <option value="0.5" selected>0.5km</option>
                <option value="1">1km</option>
                <option value="1.5">1.5km</option>
                <option value="2">2km</option>
            @elseif(isset($location->radius)? $location->radius : old('radius') == '1.5')
                <option value="0.5">0.5km</option>
                <option value="1">1km</option>
                <option value="1.5" selected>1.5km</option>
                <option value="2">2km</option>
            @elseif(isset($location->radius)? $location->radius : old('radius') == '2')
                <option value="0.5">0.5km</option>
                <option value="1">1km</option>
                <option value="1.5">1.5km</option>
                <option value="2" selected>2km</option>
            @else
                <option value="0.5">0.5km</option>
                <option value="1">1km</option>
                <option value="1.5">1.5km</option>
                <option value="2">2km</option>
            @endif
        </select>
        @if($errors->has('radius'))
            <p class="help-block">{{ $errors->first('radius') }}</p>
        @endif
    </div>
</div>
@if($status == true)
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label class="label-control col-lg-3 col-md-3 col-ms-3 col-xs-3 text-right">Status</label>
    <div class="col-lg-6 col-md-6 col-ms-6 col-xs-6">
        <select name="status" class="form-control">
            @if(isset($location))
                @if(($location->status !== ''? $location->status : old('status')) == '1')
                    <option value="1" selected>Active</option>
                    <option value="0">Deactive</option>
                @elseif(($location->status !== ''? $location->status : old('status')) == '0')
                    <option value="1">Active</option>
                    <option value="0" selected>Deactive</option>
                @else
                    <option value="1" selected>Active</option>
                    <option value="0">Deactive</option>
                @endif
            @else
                <option value="1" selected>Active</option>
                <option value="0">Deactive</option>
            @endif
        </select>
        @if($errors->has('status'))
            <p class="help-block">{{ $errors->first('status') }}</p>
        @endif
    </div>
</div>
@endif
<div class="form-group">
    <label class="control-label col-lg-3 col-md-3 col-ms-3 col-xs-3">pick location</label>
    <div class="col-lg-6 col-md-6 col-ms-6 col-xs-6">
        <button type="button" name="pick_location" id="pick_location" class="btn btn-block btn-success" data-toggle="modal" data-target="#pickLocationModal">Pick location from map <span class="fa fa-map-marker"></span></button>
    </div>
</div>
<div class="form-group" style="display: block;" id="txt_lat_lng">
    <label class="control-label col-lg-3 col-md-3 col-ms-3 col-xs-3 required {{ $errors->has('latitude') || $errors->has('longitude') ? 'red-txt':'' }}">Location</label>
    <div class="col-lg-3">
        <input type="text" name="latitude" id="latitude" class="form-control {{ $errors->has('latitude')? 'red-borded':'' }}" placeholder="latitude." value="{{ isset($location->latitude)? $location->latitude : old('latitude') }}" readonly>
        @if($errors->has('latitude'))
            <p class="help-block" style="color: #a94442;">{{ $errors->first('latitude') }}</p>
        @endif
    </div>
    <div class="col-lg-3">
        <input type="text" name="longitude" id="longitude" class="form-control {{ $errors->has('longitude')? 'red-borded':'' }}" placeholder="longitude." value="{{ isset($location->longitude)? $location->longitude : old('longitude') }}" readonly>
        @if($errors->has('longitude'))
            <p class="help-block" style="color: #a94442;">{{ $errors->first('longitude') }}</p>
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-lg-offset-3 col-md-offset-3 col-ms-offset-3 col-xs-offset-3 col-lg-3 col-md-3 col-ms-3 col-xs-3">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary btn-sm']) !!}
    </div>
</div>

<!-- Modal -->
<div id="pickLocationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pick location from map <span class="fa fa-map-marker"></span></h4>
      </div>
      <div class="modal-body">
        <div style="width: 100%;height: 400px;border:1px solid #999;" id="googleMap"></div>
      </div>
      <div class="modal-footer">
        <div style="color: #a94442;float: left;">Note: drag and drop the marker.</div>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="btn_pick_location" onclick="pinMarker()">Pick Location</button>
      </div>
    </div>

  </div>
</div>
