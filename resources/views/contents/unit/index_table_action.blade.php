@if(Auth::user()->hasRole('manager'))
<button class="btn btn-outline-info dropdown-toggle btn-sm" type="button" data-toggle="dropdown"
aria-haspopup="true" aria-expanded="false">Action</button>
<div class="dropdown-menu arrow">
	<a class="dropdown-item" href="{{ route('unit.calendar', $row->id) }}">
		<i class="fa fa-calendar mr-1"></i> Calendar
	</a>	
	<a class="dropdown-item" href="{{ route('unit.manage', $row->id) }}">
		<i class="fa fa-pencil-square-o mr-1"></i> Manage
	</a>
	<div class="dropdown-divider"></div>
	<a class="dropdown-item" onclick="window._delete(this)">
		<i class="fa fa-close mr-1"></i> Delete
	</a>	
</div> 
@else
-
@endif