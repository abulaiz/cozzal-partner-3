<div class="main-menu menu-fixed menu-dark menu-accordion    menu-shadow " data-scroll-to-active="true">
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

      <li class=" nav-item @yield('dashboard')"><a href="{{URL::to('dashboard')}}" ><i class="icon-home"></i><span class="menu-title">Dashboard</span></a>
      </li>

      <li class=" nav-item @yield('payment_report')"><a href="{{URL::to('payment_report')}}" ><i class="icon-docs"></i><span class="menu-title">Payment Report</span></a>
      </li>

    </ul>
  </div>
</div>
