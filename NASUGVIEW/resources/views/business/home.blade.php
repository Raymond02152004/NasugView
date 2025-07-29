@extends('business.businessowner')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>

<style>
    html, body { height: 100%; margin: 0; background-color: #f0f2f5; overflow: hidden; }
    .main-container { display: flex; height: 100vh; overflow: hidden; }
    .main-feed { flex: 1; overflow-y: auto; height: 100vh; padding: 20px 0; margin-left: 0; }
    .main-feed-content { max-width: 600px; margin: 0 auto; padding: 0 16px; }
    .status-wrapper { margin-bottom: 20px; }
    .status-box { background: #fff; border-radius: 10px; padding: 12px 16px; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1); }
    .status-box .top-row { display: flex; align-items: center; gap: 10px; }
    .status-box a img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
    .status-box input[type="text"] { flex-grow: 1; border: none; outline: none; background-color: #f0f2f5; border-radius: 25px; padding: 10px 16px; font-size: 15px; color: #050505; cursor: pointer; }
</style>

<div class="main-container">

    <div class="main-feed">
        <div class="main-feed-content">
            <div class="status-wrapper">
                <div class="status-box">
                    <div class="top-row">
                        <a href="{{ url('/business/profile') }}">
                            <img src="{{ session('profile_pic') ?? asset('img/profile.png') }}" alt="Profile Picture">
                        </a>
                        <input type="text" placeholder="What's on your mind, {{ session('username') ?? 'Guest' }}?" data-bs-toggle="modal" data-bs-target="#createPostModal" readonly />
                    </div>
                </div>
            </div>

            @include('business.businesspost-content')
        </div>
    </div>
</div>

@include('business.businessmodals-and-scripts')

@endsection
