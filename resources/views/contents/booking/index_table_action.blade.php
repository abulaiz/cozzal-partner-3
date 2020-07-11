<button type="button" class="btn btn-sm btn-outline-info dropdown-toggle" data-toggle="dropdown"
aria-haspopup="true" aria-expanded="true">
  Action
</button>
<div class="dropdown-menu dropup">
  <div class="dropdown-submenu">
    <button class="dropdown-item" type="button">Detail</button>
    <div class="dropdown-menu open-left" role="menu">
      <a class="dropdown-item" target="blank" href="{{ route('booking.invoice', $row->id) }}">
        <i class="fa fa-file-text-o mr-1"></i>Invoice
      </a>
      <a class="dropdown-item payments" 
      onclick="window.payment(this)"
      data-toggle="modal" 
      data-backdrop="static" 
      data-keyboard="false" 
      data-target="#payment">
        <i class="fa fa-money mr-1"></i>Payment
      </a>      
    </div>
  </div>
  <div class="dropdown-submenu">
    <button class="dropdown-item" type="button">Action</button>
    <div class="dropdown-menu open-left" role="menu">
      <a onclick="window.confirm(this)" class="dropdown-item confirm">
        <i class="fa fa-check mr-1"></i>Confrim</a>
      <a class="dropdown-item">
        <i class="fa fa-pencil mr-1"></i>Edit</a>
      <div class="dropdown-divider"></div>
      <button onclick="window.cancel(this)" type="submit" class="dropdown-item">
        <i class="fa fa-times mr-1"></i>Cancel</button>
    </div>
  </div>
</div>
