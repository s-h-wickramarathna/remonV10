@extends('layouts.master') @section('title','Cover Size')
@section('css')
<style type="text/css">
	.panel.panel-bordered {
	    border: 1px solid #ccc;
	}

	.btn-primary {
	    color: white;
	    background-color: #005C99;
	    border-color: #005C99;
	}

	.chosen-container{
		font-family: 'FontAwesome', 'Open Sans',sans-serif;
	}
</style>
@stop
@section('content')
<ol class="breadcrumb">
	<li>
    	<a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
  	</li>
  	<li>
    	<a href="javascript:;">Cover Management</a>
  	</li>
  	<li>
    	<a href="{{url('product/list')}}">Cover List</a>
  	</li>
  	<li class="active">Cover Size</li>
</ol>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-bordered">
      		<div class="panel-heading border">
      			<div style="padding-bottom: 6px;padding-top: 6px; display: inline-block;">
      				<strong>Box Size</strong>
      			</div>
      			<a class="pull-right btn btn-danger" href="{{url('/AlbumCover/list')}}"><i class="fa fa-arrow-left"></i> Back</a>
      		</div>
          	<div class="panel-body">
          		<form product="form" class="form-horizontal" id="size_form" name="product_form" method="post">
          		{!!Form::token()!!}

					  <div class="form-group{{ $errors->has('cover')? ' has-error':'' }}">
                                                <label class="col-sm-3 control-label required">Cover</label>

                                                <div class="col-sm-6">
                                                    <input type="text" autocomplete="off"
                                                           class="form-control" name="cover"
                                                           id="cover" placeholder="Cover"
                                                           value="{{$cover->cover}}">
                                                    <label hidden="true" id="lasizebel_des" name="label_des" class="error"></label>
                                                    @if($errors->has('cover'))
                                                        <div class="help-block">{{ $errors->first('cover') }}</div>
                                                    @endif
                                                </div>
                                            </div>


					<div class="form-group">
						<div class="col-sm-6 col-md-offset-3">
							<div class="pull-left">
								<button type="submit" name="save" class="btn btn-primary"><i class="fa fa-floppy-o"
																							 style="padding-right: 16px;width: 28px;"></i>
									Save
								</button>
							</div>
						</div>
					</div>
            	</form>
          	</div>
        </div>
	</div>
</div>
@stop
@section('js')
<script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/vendor/jquery-mask/jquery.mask.min.js')}}"></script>
<script src="{{asset('assets/scripts/custom/validation.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.form-validation').validate();
		var select_brand,select_category,select_range, chosen_brand, chosen_category, chosen_range;

		//brand new entry
		select_brand = $(".chosen-select-brand");
		select_brand.chosen({ no_results_text: 'Press Enter to add new brand:' });
		chosen_brand = select_brand.data('chosen');
		chosen_brand.dropdown.find('input').on('keyup', function(e)
		{
			if (e.which == 13 && chosen_brand.dropdown.find('li.no-results').length > 0)
			{
				var option = $("<option>").val(this.value).text(this.value);
				select_brand.prepend(option);
				select_brand.find(option).prop('selected', true);
				select_brand.trigger("chosen:updated");
			}
		});

		//category new entry
		select_category = $(".chosen-select-category");
		select_category.chosen({ no_results_text: 'Press Enter to add new category:' });
		chosen_range = select_category.data('chosen');
		chosen_range.dropdown.find('input').on('keyup', function(e)
		{
			if (e.which == 13 && chosen_range.dropdown.find('li.no-results').length > 0)
			{
				var option = $("<option>").val(this.value).text(this.value);
				select_category.prepend(option);
				select_category.find(option).prop('selected', true);
				select_category.trigger("chosen:updated");
			}
		});

		//Range new entry
		select_range = $(".chosen-select-range");
		select_range.chosen({ no_results_text: 'Press Enter to add new range:' });
		chosen_category = select_range.data('chosen');
		chosen_category.dropdown.find('input').on('keyup', function(e)
		{
			if (e.which == 13 && chosen_category.dropdown.find('li.no-results').length > 0)
			{
				var option = $("<option>").val(this.value).text(this.value);
				select_range.prepend(option);
				select_range.find(option).prop('selected', true);
				select_range.trigger("chosen:updated");
			}
		});
	});
</script>
@stop
