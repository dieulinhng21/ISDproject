<header class="main-header">
    <!-- Logo -->
    <a href="../admin/project" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>AZ</b>Land</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Admin </b> AZLand</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar navbar-static-top">
        <div class="pull-right">
          <a href="{{ route('logout') }}" class="btn btn-default" onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">Đăng xuất</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
          </form>
        </div>
      </div>
    </nav>
  </header>