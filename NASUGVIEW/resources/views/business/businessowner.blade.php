<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NasugView - Business Owner</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Montserrat', sans-serif;
      background-color: #f0f2f5;
    }

    .fb-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 16px;
      background-color: #ffffff;
      border-bottom: 1px solid #ddd;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .fb-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .fb-left img {
      height: 42px;
      width: 42px;
      border-radius: 50%;
      object-fit: cover;
    }

    .search-bar {
      position: relative;
      display: flex;
      align-items: center;
    }

    .search-bar input {
      padding: 8px 12px 8px 36px;
      border-radius: 20px;
      border: 1px solid #ccc;
      background-color: #f0f2f5;
      font-size: 14px;
      outline: none;
      width: 220px;
    }

    .search-bar i {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #888;
      font-size: 14px;
    }

    .fb-center {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 24px;
      margin-left: -260px;
      flex: 1;
    }

    .tooltip-wrapper {
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .tooltip-wrapper a {
      text-decoration: none;
      color: #65676b;
      font-size: 22px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: #ffffff;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: color 0.3s ease;
    }

    .tooltip-wrapper a:hover {
      color: #084d1a;
    }

    .tooltip-wrapper.active a {
      color: #14532d;
    }

    .tooltip-wrapper.active a::after {
      content: "";
      position: absolute;
      bottom: -4px;
      left: 0;
      width: 100%;
      height: 3px;
      background-color: #14532d;
      border-radius: 2px;
    }

    .tooltip-text {
      position: absolute;
      bottom: -40px;
      background-color: #4b9c4e;
      color: #ffffff;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 14px;
      font-size: 14px;
      white-space: nowrap;
      opacity: 0;
      transform: scale(0.95);
      transition: all 0.2s ease;
      pointer-events: none;
      z-index: 10;
    }

    .tooltip-wrapper:hover .tooltip-text {
      opacity: 1;
      transform: scale(1);
    }

    .fb-right {
      position: fixed;
      top: 12px;
      right: 16px;
      z-index: 2000;
    }

    .profile-wrapper {
      position: relative;
      width: 42px;
      height: 42px;
      cursor: pointer;
    }

    .profile-pic-container img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
    }

    .caret-overlay {
      position: absolute;
      bottom: -2px;
      right: -2px;
      background-color: white;
      border-radius: 50%;
      width: 16px;
      height: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      color: #000;
      border: 1px solid #ccc;
    }

    .dropdown {
      position: absolute;
      top: 44px;
      left: 50%;
      transform: translateX(-100%);
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
      width: 240px;
      display: none;
      flex-direction: column;
      padding: 12px;
    }

    .dropdown.show {
      display: flex;
    }

    .dropdown-item {
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: background-color 0.2s;
    }

    .dropdown-item:hover {
      background-color: #f0f2f5;
    }

    .main-content {
      padding: 0;
    }

    @media (max-width: 768px) {
      .search-bar input {
        display: none;
      }
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <div class="fb-header">
    <div class="fb-left">
      <img src="{{ asset('img/nasugview-logo.png') }}" alt="NasugView Logo">
      <div class="search-bar">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search...">
      </div>
    </div>

    <div class="fb-center">
      <div class="tooltip-wrapper {{ request()->routeIs('business.home') ? 'active' : '' }}">
        <a href="{{ route('business.home') }}"><i class="fas fa-home"></i></a>
        <span class="tooltip-text">Home</span>
      </div>
      <div class="tooltip-wrapper {{ request()->routeIs('business.notification') ? 'active' : '' }}">
        <a href="{{ route('business.notification') }}"><i class="fas fa-bell"></i></a>
        <span class="tooltip-text">Notification</span>
      </div>
      <div class="tooltip-wrapper {{ request()->routeIs('business.marketplace') ? 'active' : '' }}">
        <a href="{{ route('business.marketplace') }}"><i class="fas fa-store"></i></a>
        <span class="tooltip-text">Marketplace</span>
      </div>
    </div>
  </div>

  <!-- PROFILE + DROPDOWN -->
  <div class="fb-right">
    <div class="profile-wrapper" onclick="toggleDropdown()">
      <div class="profile-pic-container">
        <img src="{{ session('profile_pic') ?? asset('img/profile.png') }}" alt="Profile">
        <div class="caret-overlay">
          <i class="fas fa-caret-down"></i>
        </div>
      </div>

      <div class="dropdown" id="profileDropdown">
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
          @csrf
          <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left;">
            <i class="fas fa-sign-out-alt"></i> Logout
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- MAIN CONTENT -->
  <div class="main-content">
    @yield('content')
  </div>

  <script>
    function toggleDropdown() {
      document.getElementById("profileDropdown").classList.toggle("show");
    }

    window.onclick = function(e) {
      if (!e.target.closest(".profile-wrapper")) {
        document.getElementById("profileDropdown").classList.remove("show");
      }
    }
  </script>

</body>
</html>
