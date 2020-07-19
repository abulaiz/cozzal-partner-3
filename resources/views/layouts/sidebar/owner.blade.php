<div class="main-menu menu-fixed menu-dark menu-accordion    menu-shadow " data-scroll-to-active="true" id="sidebar">
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

      <li class=" nav-item @yield('dashboard')"><a href="{{URL::to('dashboard')}}" ><i class="icon-home"></i><span class="menu-title">Dashboard</span></a>
      </li>

      <li class=" nav-item @yield('reservation_report')"><a href="{{route('reservation.report')}}" ><i class="icon-book-open"></i><span class="menu-title">Reservation List</span></a>
      </li>

      <li class=" nav-item @yield('expenditure_list')"><a href="{{route('expenditure')}}" ><i class="icon-share-alt"></i><span class="menu-title">Expenditures</span></a>
      </li>

      <li class=" nav-item @yield('payment_report')"><a href="{{route('payment_report')}}" ><i class="icon-note"></i><span class="menu-title">Waiting Payment</span>
      <span class="badge badge badge-primary float-right mr-1 op-0" v-if="payment_report.waiting_payment > 0">@{{ payment_report.waiting_payment }}</span>
      </a>
      </li>

      <li class=" nav-item @yield('paid_payment')"><a href="{{ route('payment.paid') }}" ><i class="icon-credit-card"></i><span class="menu-title">Paid Incomes</span></a>
      </li>

      <li class=" nav-item @yield('units')"><a href="{{ route('units') }}" ><i class="icon-screen-tablet"></i><span class="menu-title">Unit List</span></a>
      </li>

    </ul>
  </div>
</div>
