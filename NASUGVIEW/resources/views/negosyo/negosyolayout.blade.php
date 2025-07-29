<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Negosyo Center</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Boxicons & Bootstrap -->
  <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #d6e5cf;
      overflow-x: hidden;
    }

    /* SIDEBAR */
    #sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 260px;
      height: 100vh;
      background-color: #084d1a;
      color: white;
      z-index: 2000;
      transition: width 0.3s ease;
      overflow: hidden;
      border-top-right-radius: 12px;
    }

    #sidebar.hide {
      width: 75px;
    }

    #sidebar .logo {
      display: flex;
      align-items: center;
      padding: 20px 20px;
      gap: 10px;
      transition: all 0.3s ease;
    }

    #sidebar .logo img {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      object-fit: cover;
      transition: all 0.3s ease;
    }

    #sidebar .logo span.text {
      font-size: 1.2rem;
      font-weight: 600;
      color: white;
      white-space: nowrap;
      transition: all 0.3s ease;
    }

    #sidebar.hide .logo {
      justify-content: center;
      padding: 20px 0;
    }

    #sidebar.hide .logo span.text {
      display: none;
    }

    #sidebar .side-menu {
      list-style: none;
      padding: 0;
      margin: 10px 0;
    }

    #sidebar .side-menu li a {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      transition: 0.3s;
      white-space: nowrap;
      border-radius: 8px;
      margin: 4px 8px;
    }

    #sidebar .side-menu li a i {
      margin-right: 10px;
      font-size: 20px;
      min-width: 20px;
      text-align: center;
    }

    #sidebar.hide .side-menu li a span.text {
      display: none;
    }

    #sidebar .side-menu li a.active,
    #sidebar .side-menu li a:hover {
      background-color: #4b9c4e;
    }

    /* HEADER */
    .main-header {
      position: fixed;
      top: 0;
      left: 260px;
      right: 0;
      height: 70px;
      background-color: #f5f5f5;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
      z-index: 1999;
      transition: left 0.3s ease;
    }

    #sidebar.hide ~ .main-header {
      left: 75px;
    }

    /* CONTENT */
    #content {
      background-color: #d6e5cf;
      margin-left: 260px;
      padding: 80px 20px 20px;
      transition: margin-left 0.3s ease;
    }

    #sidebar.hide ~ #content {
      margin-left: 75px;
    }

    /* PROFILE DROPDOWN */
    .profile-dropdown {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .profile-dropdown img {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      object-fit: cover;
    }

    .profile-dropdown span {
      font-weight: 500;
      color: #333;
    }

    /* MOBILE */
    @media screen and (max-width: 768px) {
      #sidebar {
        transform: translateX(-100%);
        position: fixed;
      }

      #sidebar.show {
        transform: translateX(0);
      }

      .main-header {
        left: 0 !important;
      }

      #content {
        margin-left: 0 !important;
      }
    }
  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <section id="sidebar">
    <div class="logo" id="logoContainer">
      <img src="{{ asset('img/nasugview-logo1.png') }}" alt="Logo">
      <span class="text">NasugView</span>
    </div>
    <ul class="side-menu">
      <li>
        <a href="{{ route('negosyo.dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
          <i class='bx bxs-dashboard'></i>
          <span class="text">Dashboard</span>
        </a>
      </li>
      <li>
        <a href="{{ route('negosyo.events.index') }}" class="{{ request()->is('events*') ? 'active' : '' }}">
          <i class='bx bxs-calendar-event'></i>
          <span class="text">Events</span>
        </a>
      </li>
      <li>
        <a href="{{ route('negosyo.certificate') }}" class="{{ request()->is('certificate') ? 'active' : '' }}">
          <i class='bx bxs-award'></i>
          <span class="text">Certificate</span>
        </a>
      </li>
      <li>
        <a href="{{ route('negosyo.business') }}" class="{{ request()->is('business') ? 'active' : '' }}">
          <i class='bx bxs-briefcase'></i>
          <span class="text">Business</span>
        </a>
      </li>
      <li>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="d-flex align-items-center">
          <i class='bx bxs-log-out'></i>
          <span class="text">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
          @csrf
        </form>
      </li>
    </ul>
  </section>

  <!-- HEADER -->
  <div class="main-header">
    <button class="btn btn-success px-3 py-2 fs-7" onclick="toggleSidebar()">
      <i class='bx bx-menu'></i>
    </button>
    <div class="profile-dropdown">
      <i class='bx bxs-bell'></i>
      <span>{{ session('username') ?? 'nasugadmin' }}</span>
      <img src="{{ asset('img/icon.png') }}" alt="Profile">
    </div>
  </div>

  <!-- CONTENT -->
  <section id="content">
    @yield('content')
  </section>

  <!-- SCRIPT -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle('show');
      } else {
        sidebar.classList.toggle('hide');
      }
    }
  </script>

</body>
</html>
