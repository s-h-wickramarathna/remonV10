@extends('content')
@section('dTable')
<div >
    <div class=" form-group">
        <div class="thumbnail"></div>
    </div>
    <div ui-grid="gridOptions" ui-grid-selection ui-grid-exporter ui-grid-pagination class="grid form-group"></div>
</div>
@enddTable
