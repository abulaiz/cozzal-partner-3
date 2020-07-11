<button class="btn btn-outline-info dropdown-toggle btn-sm" type="button" data-toggle="dropdown"
aria-haspopup="true" aria-expanded="false">Action</button>
<div class="dropdown-menu arrow">
	@if($type == '2')
	<a onclick="window._pay(this)" class="dropdown-item" href="javascript:void(0)">
		<i class="fa fa-money mr-1"></i> Pay Now
	</a>
	@endif
	@if($type == '3')
	<a onclick="window._approve(this)" class="dropdown-item" href="javascript:void(0)">
		<i class="fa fa-money mr-1"></i> Approve
	</a>
	@endif
	<a class="dropdown-item" href="javascript:void(0)">
		<i class="fa fa-pencil mr-1"></i> Edit
	</a>
	<div class="dropdown-divider"></div>
	<a onclick="window._delete(this)" data-type="{{ $type }}" class="dropdown-item" href="javascript:void(0)">
		<i class="fa fa-close mr-1"></i> Cancel
	</a>	
</div> 