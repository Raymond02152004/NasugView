@extends('admin.adminlayout')

@section('content')
<!-- FontAwesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
  html, body {
    margin: 0;
    padding: 0;
    overflow-x: hidden;
    overflow-y: hidden;
  }

  .import-container {
    padding: 2rem 1rem;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: calc(100vh - 90px);
    background-color: #d6e5cf;
  }

  .card {
    width: 100%;
    max-width: 1500px;
    border-radius: 30px;
    background-color: #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  }

  .card-body {
    padding: 5rem 6rem;
    border: 3px solid #2e7d32;
    border-radius: 26px;
  }

  h1.fs-1 {
    font-family: 'Georgia', serif;
    font-weight: 600;
    color: #204020;
  }

  .moto-line {
    font-family: 'Georgia', serif;
    font-weight: 600;
    color: #204020;
  }

  .white-wrapper {
    background-color: #ffffff;
    border-radius: 40px;
    padding: 3rem;
    border: 2px dashed #054d1a;
    width: 100%;
  }

  .btn-custom-green {
    background-color: #054d1a;
    color: white;
    padding: 0.75rem 2rem;
    border: none;
    font-weight: 500;
    border-radius: 10px;
    transition: 0.3s ease;
    text-decoration: none;
    display: inline-block;
  }

  .btn-custom-green:hover {
    background-color: #043f16;
  }

  @media (max-width: 1200px) {
    .card-body {
      padding: 3rem 2rem;
    }

    .white-wrapper {
      padding: 2rem;
    }
  }

  @media (max-width: 768px) {
    .card-body {
      padding: 2rem 1.5rem;
    }

    .white-wrapper {
      padding: 1.5rem;
    }

    .btn-custom-green {
      width: 100%;
      font-size: 14px;
    }
  }

  @media (max-width: 480px) {
    .card-body {
      padding: 1.25rem;
    }

    .white-wrapper {
      padding: 1rem;
    }

    h1.fs-1 {
      font-size: 1.75rem !important;
    }
  }
</style>

<div class="import-container">
  <div class="card w-100">
    <div class="card-body">
      <h1 class="fs-1 text-center text-md-start">Account Settings</h1>
      <div class="moto-line mb-3 text-center text-md-start">
        Manage imported user accounts for your organization.
      </div>

      <div class="white-wrapper text-center">
        <img src="{{ asset('img/import.png') }}" class="img-fluid mb-4" style="max-width: 200px;">
        <h4>No accounts added</h4>
        <p>Click below to go to the import page.</p>
        <a href="{{ route('admin.accountlist') }}" class="btn btn-custom-green">
          <i class="fas fa-file-import me-2"></i> Import Accounts
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
