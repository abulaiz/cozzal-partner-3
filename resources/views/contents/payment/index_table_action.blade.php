<button class="btn btn-outline-info dropdown-toggle btn-sm" type="button" data-toggle="dropdown"
aria-haspopup="true" aria-expanded="false">Action</button>
<div class="dropdown-menu arrow">
	<a class="dropdown-item" onclick="window.invoice(this)">
		<i class="fa fa-file-o mr-1"></i> Invoice
	</a>

	@if(!$item->is_accepted)
	<div class="dropdown-divider"></div>
	<a class="dropdown-item" onclick="window.__remove(this)">
		@if($item->is_rejected)
		<i class="fa fa-trash-o mr-1"></i> Remove
		@else
		<i class="fa fa-times mr-1"></i> Cancel
		@endif
	</a>
	@endif		
</div> 