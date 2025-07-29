@extends('consumer.consumerlayout')

@section('content')

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
  body {
    margin: 0;
    padding: 0;
  }

  .card-body {
    padding: 0;
  }

  .square-image {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 8px;
  cursor: pointer;
}

  .cover-photo {
    height: 340px;
    background: linear-gradient(to bottom, #ccc, #e6e6e6);
    position: relative;
    width: 100%;
    border-bottom: 1px solid #ccc;
  }

  .cover-photo .btn {
    position: absolute;
    bottom: 20px;
    right: 30px;
    font-size: 14px;
    border-radius: 6px;
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

  .edit-btn {
    margin-top: -2rem;
  }

  .status-wrapper {
    margin-top: 20px;
  }

  .status-box {
    background: #fff;
    border-radius: 10px;
    padding: 12px 16px;
    max-width: 600px;
    width: 100%;
    margin: 0 auto;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  }

  .status-box .top-row {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .status-box a img {
    width: 40px;
    height: 40px;php artisan view:clear
php artisan cache:clear
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

  .post-delete-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    background: none;
    border: none;
    color:rgb(105, 103, 103);
  }

  .post-delete-btn:hover {
    color:rgb(14, 13, 13);
  }

  .img-fluid.rounded {
    object-fit: cover;
    width: 100%;
    height: auto;
    max-height: 350px;
  }

  #mediaPreview .preview-item {
  position: relative;
  width: 100px;
  height: 100px;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid #ccc;
}

#mediaPreview .preview-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

#mediaPreview .remove-preview {
  position: absolute;
  top: 5px;
  right: 5px;
  background: rgba(0, 0, 0, 0.7); /* darker transparent bg for contrast */
  color: #fff;
  border-radius: 50%;
  font-size: 14px;
  width: 20px;
  height: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  z-index: 10;
  transition: background 0.2s;
}

#mediaPreview .remove-preview:hover {
  background: #dc3545;
}


@media (max-width: 768px) {
  .profile-info-wrapper {
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 2rem 1rem 1rem;
  }

  .profile-img {
    position: static;
    margin-top: -84px;
  }

  .profile-meta {
    margin-left: 0;
    margin-top: 10px;
  }

  .edit-btn {
    margin-top: 1rem;
    width: 100%;
    text-align: center;
  }

  .edit-btn button {
    width: 100%;
  }
}

</style>

<!-- Cover Photo -->
<div class="cover-photo">
  <button class="btn btn-light shadow-sm">
    <i class="bi bi-camera me-1"></i> Add Cover Photo
  </button>
</div>

<!-- Profile Info -->
<div class="profile-info-wrapper">
  <div class="profile-left">
    <img src="{{ session('profile_pic') ?? asset('img/profile.png') }}" class="profile-img" alt="Profile Picture">
    <div class="profile-meta">
      <h2>{{ session('username') ?? 'Your Name' }}</h2>
    </div>
  </div>
  <div class="edit-btn">
    <button class="btn btn-light border px-4">
      <i class="bi bi-pencil me-1"></i> Edit profile
    </button>
  </div>
</div>

<!-- Status Box -->
<div class="container-fluid px-2 status-wrapper">
  <div class="status-box">
    <div class="top-row">
      <a href="{{ url('/consumer/profile') }}">
        <img src="{{ session('profile_pic') ?? asset('img/profile.png') }}" alt="Profile Picture" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
      </a>
      <input type="text" placeholder="What's on your mind, {{ session('username') ?? 'Guest' }}?" data-bs-toggle="modal" data-bs-target="#createPostModal" readonly />
    </div>
  </div>
</div>

<!-- Create Post Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4">
      <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Create post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="d-flex align-items-center mb-3">
            <img src="{{ session('profile_pic') ?? asset('img/profile.png') }}" class="rounded-circle" style="width: 40px; height: 40px;" alt="Profile">
            <div class="ms-2 fw-bold">{{ session('username') ?? 'Guest' }}</div>
          </div>

          <input type="hidden" name="signup_id" value="{{ session('signup_id') }}">
          <textarea name="content" class="form-control border-0" rows="4" placeholder="Write something..." style="resize: none; font-size: 1.2rem;"></textarea>
          <input type="file" name="media[]" id="mediaInput" accept="image/*" multiple hidden>

         <!-- Media Preview Area -->
        <div id="mediaPreview" class="mt-3 d-flex flex-wrap gap-2"></div>

        <!-- Add to your post section -->
        <div class="mt-3 border rounded-3 px-3 py-2">
        <div class="fw-semibold mb-2 text-secondary">Add to your post</div>
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <button type="button" class="btn btn-light icon-btn" onclick="document.getElementById('mediaInput').click();">
            <i class="fas fa-photo-video text-success"></i>
            </button>
            <button type="button" class="btn btn-light icon-btn" data-bs-toggle="modal" data-bs-target="#locationModal">
            <i class="fas fa-map-marker-alt text-danger"></i>
            </button>
        </div>
        </div>

        </div>
        <div class="modal-footer">
          <button class="btn btn-success w-100 rounded-pill" type="submit">Post</button>
        </div>
      </form>
    </div>
  </div>
</div>

@if($user->posts->count())
<div class="container mt-4">
  @foreach($user->posts as $post)
    @php
      $mediaList = json_decode(str_replace('\\', '/', $post->media_paths ?? '[]'));
      $mediaUrls = array_map(fn($m) => asset('storage/' . str_replace('\\', '/', $m)), $mediaList);
    @endphp

    <div class="card mb-4 p-3 shadow-sm border-0 position-relative" style="max-width: 600px; margin: 0 auto;">

      <!-- User Info -->
      <div class="d-flex align-items-center mb-2">
        <img src="{{ $post->signup->profile_pic ?? asset('img/profile.png') }}" class="rounded-circle" style="width: 40px; height: 40px;">
        <div class="ms-2">
          <strong>{{ $post->signup->username }}</strong><br>
          <small class="text-muted">Posted on {{ $post->created_at->format('F j \a\t g:i A') }}</small>
        </div>
      </div>

      <!-- Post Text -->
      <p class="mb-3" style="font-size: 15px;">{{ $post->content }}</p>

      <!-- Media Grid -->
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

      <!-- Like & Comment Buttons -->
      <div class="d-flex border-top pt-2 mt-2 justify-content-around text-muted">
        <button class="btn btn-light w-100 border-0 d-flex align-items-center justify-content-center gap-1" style="font-size: 14px;">
          <i class="fa-regular fa-thumbs-up"></i> Like
        </button>
        <button class="btn btn-light w-100 border-0 d-flex align-items-center justify-content-center gap-1" style="font-size: 14px;">
          <i class="fa-regular fa-comment"></i> Comment
        </button>
      </div>
    </div>
  @endforeach
</div>
@else
  <div class="text-center text-muted mt-4">No posts yet.</div>
@endif


<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true" style="z-index: 2000;">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content bg-black text-white border-0 d-flex align-items-center justify-content-center position-relative">

      <!-- Close Button -->
      <button type="button" class="btn btn-close btn-close-white position-absolute top-0 start-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>

      <!-- Prev/Next Navigation -->
      <button id="prevBtn" onclick="prevImage()" class="btn position-absolute start-0 top-50 translate-middle-y bg-secondary-subtle rounded-circle" style="width: 45px; height: 45px;">
        <i class="fas fa-chevron-left text-dark"></i>
      </button>
      <button id="nextBtn" onclick="nextImage()" class="btn position-absolute end-0 top-50 translate-middle-y bg-secondary-subtle rounded-circle" style="width: 45px; height: 45px;">
        <i class="fas fa-chevron-right text-dark"></i>
      </button>

      <!-- Main Image Display -->
      <img id="modalImage" src="" class="img-fluid rounded"
     style="max-height: 90vh; max-width: 95vw; object-fit: contain; border: none;" />


      <!-- Zoom Controls -->
      <div class="position-absolute top-0 end-0 m-3 d-flex align-items-center gap-2">
        <button class="btn btn-light btn-sm" onclick="zoomIn()" title="Zoom In"><i class="fas fa-search-plus"></i></button>
        <button class="btn btn-light btn-sm" onclick="zoomOut()" title="Zoom Out"><i class="fas fa-search-minus"></i></button>
        <button class="btn btn-light btn-sm" onclick="resetZoom()" title="Reset Zoom"><i class="fas fa-search"></i></button>
      </div>

    </div>
  </div>
</div>


<script>
let currentZoom = 1, currentIndex = 0, currentImages = [];

function openFromAttribute(el) {
  const images = JSON.parse(el.getAttribute('data-images') || '[]');
  const index = parseInt(el.getAttribute('data-index')) || 0;

  if (images.length) {
    currentImages = images;
    currentIndex = index;
    showImage(images[index], images, index);
  } else {
    document.getElementById('modalImage').style.display = 'none';
  }
}

function showImage(src, images = [], index = 0) {
  const modalImage = document.getElementById('modalImage');

  modalImage.src = '';
  modalImage.style.display = 'none';

  const testImg = new Image();
  testImg.onload = () => {
    modalImage.src = src;
    modalImage.style.display = 'block';
  };
  testImg.onerror = () => {
    modalImage.style.display = 'none';
  };
  testImg.src = src;

  currentImages = images;
  currentIndex = index;

  document.getElementById('prevBtn').style.display = images.length > 1 ? 'block' : 'none';
  document.getElementById('nextBtn').style.display = images.length > 1 ? 'block' : 'none';
}

function prevImage() {
  if (currentIndex > 0) {
    showImage(currentImages[--currentIndex], currentImages, currentIndex);
  }
}

function nextImage() {
  if (currentIndex < currentImages.length - 1) {
    showImage(currentImages[++currentIndex], currentImages, currentIndex);
  }
}

function zoomIn() {
  currentZoom += 0.1;
  document.getElementById('modalImage').style.transform = `scale(${currentZoom})`;
}

function zoomOut() {
  currentZoom = Math.max(0.5, currentZoom - 0.1);
  document.getElementById('modalImage').style.transform = `scale(${currentZoom})`;
}

function resetZoom() {
  currentZoom = 1;
  document.getElementById('modalImage').style.transform = 'scale(1)';
}





function updatePreview() {
  preview.innerHTML = '';
  fileList.forEach((file, index) => {
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const div = document.createElement('div');
        div.classList.add('preview-item');
        div.innerHTML = `
          <img src="${e.target.result}" alt="preview">
          <span class="remove-preview" onclick="removePreview(${index})">&times;</span>
        `;
        preview.appendChild(div);
      };
      reader.readAsDataURL(file);
    }
  });

  const dt = new DataTransfer();
  fileList.forEach(f => dt.items.add(f));
  input.files = dt.files;
}

function removePreview(index) {
  fileList.splice(index, 1);
  updatePreview();
}


</script>

@endsection
