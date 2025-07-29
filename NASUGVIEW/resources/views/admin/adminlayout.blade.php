<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - NasugView</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
      overflow-y: auto;
      border-top-right-radius: 12px;
    }

    #sidebar.hide {
      width: 75px;
    }

    #sidebar .logo {
      display: flex;
      align-items: center;
      padding: 20px;
      gap: 10px;
    }

    #sidebar .logo img {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      object-fit: cover;
    }

    #sidebar .logo span.text {
      font-size: 1.2rem;
      font-weight: 600;
      color: white;
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
      border-radius: 8px;
      margin: 4px 8px;
      cursor: pointer;
    }

    #sidebar .side-menu li a i {
      margin-right: 10px;
      font-size: 20px;
    }

    #sidebar.hide .side-menu li a span.text {
      display: none;
    }

    #sidebar .side-menu li a.active,
    #sidebar .side-menu li a:hover {
      background-color: #4b9c4e;
    }

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

    #content {
      background-color: #d6e5cf;
      margin-left: 260px;
      padding: 80px 20px 20px;
      transition: margin-left 0.3s ease;
    }

    #sidebar.hide ~ #content {
      margin-left: 75px;
    }

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
  <div class="logo">
    <img src="{{ asset('img/nasugview-logo.png') }}" alt="Logo">
    <span class="text">NasugView</span>
  </div>
  <ul class="side-menu">
    <!-- Import Account -->
    <li>
      <a href="{{ route('admin.account') }}" class="{{ request()->routeIs('admin.account') ? 'active' : '' }}">
        <i class='bx bx-import'></i><span class="text">Import Account</span>
      </a>
    </li>
    <!-- Account List -->
    <li>
      <a href="{{ route('admin.businessaccounts') }}" class="{{ request()->routeIs('admin.businessaccounts') ? 'active' : '' }}">
        <i class='bx bx-list-ul'></i><span class="text">Account List</span>
      </a>
    </li>
    <!-- System Config -->
    <li>
      <a href="{{ route('admin.systemconfig') }}" class="{{ request()->routeIs('admin.systemconfig') ? 'active' : '' }}">
        <i class='bx bxs-cog'></i><span class="text">System Configuration</span>
      </a>
    </li>
    <!-- Manage Post -->
    <li>
      <a href="{{ route('admin.posts.manage') }}" class="{{ request()->routeIs('admin.posts.manage') ? 'active' : '' }}">
        <i class='bx bx-edit'></i><span class="text">Manage Post</span>
      </a>
    </li>
    <!-- Logout -->
    <li>
      <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class='bx bxs-log-out'></i><span class="text">Logout</span>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
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
    <span>{{ session('username') ?? 'Admin' }}</span>
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
