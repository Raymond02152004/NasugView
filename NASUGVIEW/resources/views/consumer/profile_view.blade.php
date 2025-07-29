@extends('consumer.consumerlayout')

@section('content')
<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
  .cover-photo {
    height: 340px;
    background: linear-gradient(to bottom, #ccc, #e6e6e6);
    position: relative;
    width: 100%;
    border-bottom: 1px solid #ccc;
  }

  .profile-info-wrapper {
    background-color: white;
    padding: 2rem 3rem 1rem;
    border-bottom: 1px solid #ddd;
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    flex-wrap: wrap;
  }

  .profile-left {
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
  }

  .profile-img {
    width: 168px;
    height: 168px;
    border-radius: 50%;
    border: 5px solid white;
    object-fit: cover;
    background-color: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    position: absolute;
    top: -84px;
    left: 0;
  }

  .profile-meta {
    margin-left: 190px;
  }

  .profile-meta h2 {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    text-transform: capitalize;
  }

  .post-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
  }

  .post-card .card-body {
    padding: 10px;
  }

  .square-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
  }
</style>

<!-- Cover Photo -->
<div class="cover-photo"></div>

<!-- Profile Info -->
<div class="profile-info-wrapper">
  <div class="profile-left">
    <img src="{{ $user->profile_pic ?? asset('img/profile.png') }}" class="profile-img" alt="Profile Picture">
    <div class="profile-meta">
      <h2>{{ $user->username }}</h2>
    </div>
  </div>
</div>

<!-- Posts -->
<div class="container mt-5 pt-3">
  <div class="row">
    <div class="col-md-8 offset-md-2">
      @if($user->posts->count())
        @foreach($user->posts as $post)
          @php
            $mediaList = json_decode(str_replace('\\', '/', $post->media_paths ?? '[]'));
            $mediaUrls = array_map(fn($m) => asset('storage/' . str_replace('\\', '/', $m)), $mediaList);
          @endphp

          <div class="card mb-4 shadow-sm border-0 post-card">
            <div class="card-body">
              <div class="d-flex align-items-center mb-2">
                <img src="{{ $user->profile_pic ?? asset('img/profile.png') }}" class="rounded-circle" style="width: 40px; height: 40px;">
                <div class="ms-2">
                  <strong>{{ $user->username }}</strong><br>
                  <small class="text-muted">Posted on {{ $post->created_at->format('F j \a\t g:i A') }}</small>
                </div>
              </div>

              <p class="mb-3" style="font-size: 15px;">{{ $post->content }}</p>

              @if(count($mediaList))
              <div class="row g-2 mb-2">
                @foreach($mediaList as $index => $media)
                  @if($index < 4)
                    <div class="col-6">
                      <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-0 position-relative">
                          <a href="#"
                             data-bs-toggle="modal"
                             data-bs-target="#imageModal"
                             data-images="{{ json_encode($mediaUrls) }}"
                             data-index="{{ $index }}"
                             onclick="openFromAttribute(this)">
                            <img src="{{ asset('storage/' . str_replace('\\', '/', $media)) }}" class="square-image" />
                            @if($index === 3 && count($mediaList) > 4)
                              <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 rounded">
                                <span class="text-white fw-bold fs-4">+{{ count($mediaList) - 4 }}</span>
                              </div>
                            @endif
                          </a>
                        </div>
                      </div>
                    </div>
                  @endif
                @endforeach
              </div>
              @endif

              <div class="d-flex border-top pt-2 mt-2 justify-content-around text-muted">
                <button class="btn btn-light w-100 border-0 d-flex align-items-center justify-content-center gap-1" style="font-size: 14px;">
                  <i class="fa-regular fa-thumbs-up"></i> Like
                </button>
                <button class="btn btn-light w-100 border-0 d-flex align-items-center justify-content-center gap-1" style="font-size: 14px;">
                  <i class="fa-regular fa-comment"></i> Comment
                </button>
              </div>
            </div>
          </div>
        @endforeach
      @else
        <div class="text-center text-muted mt-4">No posts yet from this user.</div>
      @endif
    </div>
  </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0">
        <img id="modalImage" src="" class="img-fluid w-100 rounded" style="max-height: 80vh; object-fit: contain;">
      </div>
    </div>
  </div>
</div>

<script>
  function openFromAttribute(el) {
    const images = JSON.parse(el.getAttribute('data-images'));
    const index = parseInt(el.getAttribute('data-index'));
    const modalImage = document.getElementById('modalImage');
    modalImage.src = images[index];
  }
</script>
@endsection
