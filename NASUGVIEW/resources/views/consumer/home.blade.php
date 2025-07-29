@extends('consumer.consumerlayout')

@section('content')

<!-- Bootstrap & Font Awesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>

<style>
  html, body {
    height: 100%;
    margin: 0;
    background-color: #f0f2f5;
    overflow: hidden;
  }

  .main-container {
    display: flex;
    height: 100vh;
    overflow: hidden;
  }

  .left-sidebar {
    width: 500px;
    font-family: 'Segoe UI', sans-serif;
    flex-shrink: 0;
    padding: 20px 10px 0 20px;
  }

  .left-sidebar .left-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    padding: 25px; /* Increased padding for a larger area */
    font-size: 20px; /* Increased font size for better visibility */
    font-weight: 600;
    color: #14532d;
    border-left: 4px solid #198754;
    height: calc(100vh - 100px);
    display: flex;
    flex-direction: column; /* Stack content vertically */
    gap: 30px; /* Increased gap between content */
    text-align: center;
  }

  .main-feed {
    flex: 1;
    overflow-y: auto;
    height: 100vh;
    padding: 20px 0;
  }

  .main-feed-content {
    width: 100%;
    max-width: 800px;
    margin-left: calc((100vw - 640px - 1040px) / 2); /* centers feed next to sidebar */
    padding: 0 16px;
  }

  .main-feed::-webkit-scrollbar {
    width: 8px;
  }

  .main-feed::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 4px;
  }

  .status-wrapper {
    margin-bottom: 20px;
  }

  .status-box {
    background: #fff;
    border-radius: 10px;
    padding: 12px 16px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', sans-serif;
  }

  .status-box .top-row {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .status-box a img {
    width: 60px; /* Increased profile picture size */
    height: 60px; /* Increased profile picture size */
    border-radius: 50%;
    object-fit: cover;
  }

  .status-box input[type="text"] {
    flex-grow: 1;
    border: none;
    outline: none;
    background-color: #f0f2f5;
    border-radius: 25px;
    padding: 10px 16px;
    font-size: 15px;
    color: #050505;
    cursor: pointer;
  }

  /* Style for the shop names */
  .left-sidebar .left-card .shop-name {
    font-size: 22px; /* Increased shop name size */
    font-weight: 700;
    color: #198754;
  }

  @media (max-width: 768px) {
    .main-container {
      flex-direction: column;
    }

    .left-sidebar {
      width: 100%;
      padding: 10px;
      height: auto;
    }

    .left-sidebar .left-card {
      height: auto;
    }

    .main-feed-content {
      margin: 0 auto;
      padding: 0 15px;
    }
  }

  .reaction-wrapper {
    position: relative;
  }

  .reaction-icons {
    display: flex;
    position: absolute;
    top: -50px;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    border-radius: 30px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    padding: 6px 10px;
    gap: 10px;
    z-index: 10;
    transition: opacity 0.2s ease;
    opacity: 0;
    pointer-events: none;
  }

  .reaction-wrapper:hover .reaction-icons {
    opacity: 1;
    pointer-events: auto;
  }

  .reaction-icons i {
    font-size: 22px;
    cursor: pointer;
    transition: transform 0.2s ease;
  }

  .reaction-icons i:hover {
    transform: scale(1.3);
  }
</style>

<div class="main-container">
  <!-- Left Sidebar -->
  <div class="left-sidebar">
    <div class="left-card">
      <i></i> <span style="font-size: 24px; font-weight: 700;">Check this out!</span> <!-- Increased font size for the title -->
      
      <!-- Xylex's Barber Shop -->
      <div class="d-flex flex-column gap-5"> <!-- Increased gap for spacing -->
        <div class="d-flex align-items-center gap-3">
          <!-- Profile Image as a link for Xylex's Barber Shop -->
          <a href="{{ url('/consumer/shop/profile') }}">
            <img src="https://th.bing.com/th/id/OIP.Aou5XEHRLEAUYv5Cknv0_AHaHa?w=187&h=188&c=7&r=0&o=7&pid=1.7&rm=3" alt="Profile Picture" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
          </a>
          
          <!-- Shop Name -->
          <div class="shop-name">
            <strong>Xylex's Barber Shop</strong>
          </div>
        </div>
        
        <!-- Kainan sa Dalampasigan -->
        <div class="d-flex align-items-center gap-3">
          <a href="{{ url('/consumer/shop/profile') }}">
            <img src="https://th.bing.com/th/id/OIP.uompDkzYBlSMhTLs_rrl9gHaFB?w=257&h=180&c=7&r=0&o=7&pid=1.7&rm=3" alt="Profile Picture" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
          </a>
          <div class="shop-name">
            <strong>Kainan sa Dalampasigan</strong>
          </div>
        </div>

        <!-- Lomi House -->
        <div class="d-flex align-items-center gap-3">
          <a href="{{ url('/consumer/shop/profile') }}">
            <img src="https://th.bing.com/th?q=Lomi+House+Design+Icon&w=120&h=120&c=1&rs=1&qlt=70&r=0&o=7&cb=1&pid=InlineBlock&rm=3&mkt=en-PH&cc=PH&setlang=en&adlt=moderate&t=1&mw=247" alt="Profile Picture" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
          </a>
          <div class="shop-name">
            <strong>Lomi House</strong>
          </div>
        </div>

        <!-- Hair Salon -->
        <div class="d-flex align-items-center gap-3">
          <a href="{{ url('/consumer/shop/profile') }}">
            <img src="https://th.bing.com/th/id/OIP.VdEPsRwwknTz-DGNJhBiggHaEO?w=279&h=180&c=7&r=0&o=7&pid=1.7&rm=3" alt="Profile Picture" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
          </a>
          <div class="shop-name">
            <strong>Hair Salon</strong>
          </div>
        </div>

        <!-- Kahit Saan -->
        <div class="d-flex align-items-center gap-3">
          <a href="{{ url('/consumer/shop/profile') }}">
            <img src="https://kahit-saan.weebly.com/uploads/4/0/7/1/40717055/1412908487.png" alt="Profile Picture" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
          </a>
          <div class="shop-name">
            <strong>Kahit Saan</strong>
          </div>
        </div>

        <!-- David's Salon -->
        <div class="d-flex align-items-center gap-3">
          <a href="{{ url('/consumer/shop/profile') }}">
            <img src="https://th.bing.com/th/id/OIP.JzhG50W2zkDl_09HKIBhPQHaCe?w=329&h=116&c=7&r=0&o=7&pid=1.7&rm=3" alt="Profile Picture" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
          </a>
          <div class="shop-name">
            <strong>David's Salon</strong>
          </div>
        </div>

        <!-- Ikaw Bahala -->
        <div class="d-flex align-items-center gap-3">
          <a href="{{ url('/consumer/shop/profile') }}">
            <img src="https://th.bing.com/th/id/OIP.H3W4yMaKlxlJ7YK3ay6HDgHaHa?w=173&h=180&c=7&r=0&o=7&pid=1.7&rm=3" alt="Profile Picture" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
          </a>
          <div class="shop-name">
            <strong>Ikaw Bahala</strong>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Main Feed -->
  <div class="main-feed">
    <div class="main-feed-content">
      <!-- Status Box -->
      <div class="status-wrapper">
        <div class="status-box">
          <div class="top-row">
            <a href="{{ url('/consumer/profile') }}">
              <img src="{{ session('profile_pic') ?? asset('img/profile.png') }}" alt="Profile Picture">
            </a>
            <input type="text" placeholder="What's on your mind, {{ session('username') ?? 'Guest' }}?" data-bs-toggle="modal" data-bs-target="#createPostModal" readonly />
          </div>
        </div>
      </div>

      <!-- Posts -->
      @include('consumer.post-content')
    </div>
  </div>
</div>

@include('consumer.modals-and-scripts')

@endsection
