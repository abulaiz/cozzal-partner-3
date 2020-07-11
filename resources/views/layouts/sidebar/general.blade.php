<div class="main-menu menu-fixed menu-dark menu-accordion    menu-shadow " data-scroll-to-active="true">
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

      <li class=" nav-item @yield('dashboard')"><a href="{{URL::to('dashboard')}}" ><i class="icon-home"></i><span class="menu-title">Dashboard</span></a>
      </li>

      <li class=" nav-item"><a><i class="icon-note"></i><span class="menu-title">Booking Data</span></a>
        <ul class="menu-content">
           <li class="menu-item @yield('create_booking')"><a href="{{ route('booking.create') }}" >Create Booking</a></li>
           <li class="menu-item @yield('booking_list')"><a href="{{ route('booking') }}">Booking List</a></li>
           </a></li>
        </ul>
      </li>

      <li class=" nav-item"><a><i class="icon-book-open"></i><span class="menu-title">Reservations</span></a>
        <ul class="menu-content">
          <li class="menu-item @yield('confirmed_reservation')"><a href="{{route('reservation.confirmed')}}" >Confirmed Reservation</a></li>
           <li class="menu-item @yield('canceled_reservation')"><a href="{{route('reservation.canceled')}}" >Canceled Reservation</a></li>
        </ul>
      </li>

      <li class=" nav-item"><a><i class="icon-share-alt"></i><span class="menu-title">Expenditures</span></a>
        <ul class="menu-content">
           <li class="menu-item @yield('create_expenditure')"><a href="{{ route('expenditure.create') }}" >Create Expenditure</a></li>
           <li class="menu-item @yield('expenditure_list')"><a href="{{ route('expenditure') }}">Expenditure List</a></li>
           <li class="menu-item @yield('approve_expenditure')"><a href="{{ route('expenditure.approval') }}" >Approval Exp
            <!--<span class="dbadge badge badge-danger float-right mr-2" id="apprv_badge"></span>-->
           </a></li>
        </ul>
      </li>

      @hasrole('manager')
      <li class=" nav-item @yield('payment')"><a href="{{ route('payment') }}" ><i class="icon-docs"></i><span class="menu-title">Owner Payment</span></a>
      </li>

      <li class=" nav-item @yield('cashes')"><a href="{{ route('cashs') }}" ><i class="icon-briefcase"></i><span class="menu-title">Cash Management</span></a>
      </li>
      @endhasrole

      <li class=" nav-item @yield('units')"><a href="{{ route('units') }}" ><i class="icon-diamond"></i><span class="menu-title">Unit Data</span></a>
      </li>

      <li class=" nav-item"><a><i class="icon-layers"></i><span class="menu-title">Master Data</span></a>
        <ul class="menu-content">
          <li class="menu-item @yield('tenants')"><a href="#" >Tenants</a></li>
          <li class="menu-item @yield('owners')"><a href="#" >Owners</a></li>
          <li class="menu-item @yield('apartments')"><a href="{{ route('apartments') }}" >Apartments</a></li>
          <li class="menu-item @yield('booking_vias')"><a href="{{ route('booking_vias') }}" >Booking Vias</a></li>
          <li class="menu-item @yield('banks')"><a href="{{ route('banks') }}" >Banks</a></li>
        </ul>
      </li>

    </ul>
  </div>
</div>
