@extends('admin.adminlayout')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
  body {
    background-color: #e8f4e5;
    overflow-x: hidden;
  }
  .card-custom {
    max-width: 100%;
    margin: 3rem auto;
    padding: 3rem 2rem;
    border-radius: 32px;
    background-color: #ffffff;
    border: 2px solid #14532d;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    position: relative;
  }
  .back-btn-wrapper {
    position: absolute;
    top: 20px;
    left: 20px;
  }
  .btn-back {
    background-color: #14532d;
    color: #fff;
    border: none;
    padding: 6px 14px;
    font-family: 'Georgia', serif;
    font-size: 14px;
    border-radius: 6px;
  }
  .btn-back:hover {
    background-color: #0f3f24;
  }
  .post-title {
    font-family: 'Georgia', serif;
    font-weight: bold;
    font-size: 28px;
    color: #204020;
    margin-bottom: 25px;
    text-align: center;
  }
  .post-label {
    font-weight: bold;
    color: #14532d;
  }
  .post-content {
    font-size: 16px;
    font-family: 'Georgia', serif;
    margin-bottom: 20px;
  }
  .media-preview img {
    max-width: 180px;
    max-height: 150px;
    margin: 10px 10px 0 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    cursor: pointer;
    transition: 0.3s ease;
  }
  .media-preview img:hover {
    opacity: 0.8;
  }

  .btn-green {
    background-color: #14532d !important;
    color: #fff !important;
    border: none;
    padding: 10px 18px;
    font-family: 'Georgia', serif;
    font-size: 15px;
    border-radius: 8px;
  }

  .btn-green:hover {
    background-color: #0f3f24 !important;
    color: #fff !important;
  }

  .btn-outline-green {
    background-color: transparent !important;
    color: #14532d !important;
    border: 1px solid #14532d !important;
    font-family: 'Georgia', serif;
    font-size: 15px;
    border-radius: 8px;
  }

  .btn-outline-green:hover {
    background-color: #14532d !important;
    color: #fff !important;
  }

  .image-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    padding-top: 80px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.8);
  }
  .image-modal-content {
    margin: auto;
    display: block;
    max-width: 90%;
    max-height: 80vh;
    border-radius: 10px;
  }
  .image-modal-close {
    position: absolute;
    top: 30px;
    right: 50px;
    color: white;
    font-size: 35px;
    font-weight: bold;
    cursor: pointer;
  }
  .image-modal-close:hover {
    color: #ccc;
  }
</style>

<div class="container-fluid px-3">
  <div class="card card-custom">

    <div class="back-btn-wrapper">
      <a href="{{ route('admin.posts.manage') }}" class="btn btn-back">
        <i class="bi bi-arrow-left"></i> Back
      </a>
    </div>

    <h2 class="post-title">Post Details</h2>

    <div class="post-content">
      <p><span class="post-label">Posted By:</span> {{ $post->signup->username ?? 'Unknown' }}</p>
      <p><span class="post-label">Content:</span><br>{{ $post->content }}</p>
      <p><span class="post-label">Posted On:</span> {{ $post->created_at->format('F j, Y g:i A') }}</p>
    </div>

    @if($post->media_paths)
    <div class="post-content">
      <p class="post-label">Attached Media:</p>
      <div class="media-preview d-flex flex-wrap">
        @foreach(json_decode($post->media_paths, true) as $media)
          <img src="{{ asset('storage/' . str_replace('\\', '/', $media)) }}" alt="Post Image" class="clickable-img">
        @endforeach
      </div>
    </div>
    @endif

    <div class="mt-4 d-flex gap-3">
      <button class="btn btn-green" data-bs-toggle="modal" data-bs-target="#approveModal">
        <i class="bi bi-check-circle me-1"></i> Approve
      </button>
      <button class="btn btn-green" data-bs-toggle="modal" data-bs-target="#rejectModal">
        <i class="bi bi-x-circle me-1"></i> Reject
      </button>
    </div>
  </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal">
  <span class="image-modal-close">&times;</span>
  <img class="image-modal-content" id="modalImage">
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <h5 class="mb-3">Approve this post?</h5>
      <form method="POST" action="{{ route('admin.posts.approve', $post->posts_id) }}">
        @csrf
        <div class="d-flex justify-content-center gap-3">
          <button type="button" class="btn btn-outline-green" data-bs-dismiss="modal">
            <i class="bi bi-arrow-left me-1"></i> Cancel
          </button>
          <button type="submit" class="btn btn-green">
            <i class="bi bi-check-circle-fill me-1"></i> Yes, Approve
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <h5 class="mb-3">Reject this post?</h5>
      <form method="POST" action="{{ route('admin.posts.reject', $post->posts_id) }}">
        @csrf
        <div class="d-flex justify-content-center gap-3">
          <button type="button" class="btn btn-outline-green" data-bs-dismiss="modal">
            <i class="bi bi-arrow-left me-1"></i> Cancel
          </button>
          <button type="submit" class="btn btn-green">
            <i class="bi bi-x-circle-fill me-1"></i> Yes, Reject
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Success Modals -->
<div class="modal fade" id="approveSuccessModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <i class="bi bi-check-circle-fill text-success mb-3" style="font-size: 2rem;"></i>
      <p class="mb-0">Post approved successfully!</p>
    </div>
  </div>
</div>

<div class="modal fade" id="rejectSuccessModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <i class="bi bi-x-circle-fill text-danger mb-3" style="font-size: 2rem;"></i>
      <p class="mb-0">Post rejected successfully.</p>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('.clickable-img').on('click', function () {
      $('#modalImage').attr('src', $(this).attr('src'));
      $('#imageModal').fadeIn();
    });
    $('.image-modal-close, #imageModal').on('click', function (e) {
      if (e.target.id === 'imageModal' || e.target.className === 'image-modal-close') {
        $('#imageModal').fadeOut();
      }
    });

    @if(session('success') == 'Post approved.')
      const approvedModal = new bootstrap.Modal(document.getElementById('approveSuccessModal'));
      approvedModal.show();
      setTimeout(() => {
        window.location.href = "{{ route('admin.posts.manage') }}";
      }, 2000);
    @endif

    @if(session('success') == 'Post rejected.')
      const rejectedModal = new bootstrap.Modal(document.getElementById('rejectSuccessModal'));
      rejectedModal.show();
      setTimeout(() => {
        window.location.href = "{{ route('admin.posts.manage') }}";
      }, 2000);
    @endif
  });
</script>
@endsection
