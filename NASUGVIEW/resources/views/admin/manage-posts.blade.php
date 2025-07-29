@extends('admin.adminlayout') 

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
  body {
    overflow-x: hidden;
    min-height: 100vh;
    margin: 0;
    padding: 0;
  }

  .card-custom {
    max-width: 100%;
    margin: 3rem auto;
    padding: 3rem 2rem;
    border-radius: 32px;
    background-color: #ffffff;
    border: 2px solid #14532d;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
  }

  .header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 20px;
  }

  .header-section h2 {
    font-family: 'Georgia', serif;
    font-weight: 700;
    color: #204020;
    font-size: 28px;
    margin: 0;
  }

  .search-box {
    width: 100%;
    max-width: 300px;
    font-family: 'Georgia', serif;
    font-size: 16px;
    padding: 0.5rem 1rem;
    border: 2px solid #14532d;
    border-radius: 8px;
    margin-top: 10px;
  }

  @media (min-width: 768px) {
    .search-box {
      margin-top: 0;
    }
  }

  .table th {
    background-color: #14532d !important;
    color: #ffffff;
    font-weight: 600;
    font-family: 'Georgia', serif;
    text-align: center;
    padding: 14px;
    font-size: 15px;
  }

  .table td {
    font-family: 'Georgia', serif;
    font-size: 14px;
    padding: 12px;
    vertical-align: middle;
  }

  .table-hover tbody tr:hover {
    background-color: #f1f5f1;
  }

  .btn-green {
    background-color: #14532d;
    color: #fff;
    border: none;
  }

  .btn-green:hover {
    background-color: #0f3f24;
  }

  .profile-img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
    margin-right: 10px;
  }

  .table-container {
    display: block;
    overflow-x: auto;
  }

  .no-posts-row {
    text-align: center;
    font-family: 'Georgia', serif;
    font-size: 16px;
    font-weight: bold;
    color: #4b604b;
    background-color: #f9f9f9;
    padding: 20px;
  }
</style>

<div class="container-fluid px-3">
  <div class="card card-custom">

    <div class="header-section">
      <h2>Pending Posts for Approval</h2>
      <input type="text" id="searchInput" class="search-box" placeholder="Search table...">
    </div>

    <div class="table-container">
      <table class="table table-bordered table-hover" id="postsTable">
        <thead>
          <tr>
            <th>Posted By</th>
            <th>Posted On</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @if($pendingPosts->count())
            @foreach($pendingPosts as $post)
              <tr>
                <td class="d-flex align-items-center">
                  <img src="{{ $post->signup->profile_pic ?? asset('img/profile.png') }}"
                       onerror="this.src='{{ asset('img/profile.png') }}'"
                       class="profile-img">
                  <span>{{ $post->signup->username ?? 'Unknown' }}</span>
                </td>
                <td>{{ $post->created_at->format('F j, Y g:i A') }}</td>
                <td class="text-center">
                  <a href="{{ route('admin.posts.view', ['id' => $post->posts_id]) }}" class="btn btn-sm btn-green">
                    <i class="bi bi-eye"></i> View
                  </a>
                </td>
              </tr>
            @endforeach
          @else
            <tr class="no-posts-row">
              <td colspan="3">No pending posts found.</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>

  </div>
</div>

<script>
  $(document).ready(function () {
    $("#searchInput").on("keyup", function () {
      var value = $(this).val().toLowerCase();
      $("#postsTable tbody tr").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
</script>
@endsection
