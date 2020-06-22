<div class="row skin skin-flat">
  <div class="col-md-6 col-sm-12">
  	@if($item->check_in == null)
    <input type="checkbox" class="ckck" value="e{{ $item->id }}">
    @else
    <input type="checkbox" class="ckck" value="r{{ $item->id }}">
    @endif
  </div>
</div>  