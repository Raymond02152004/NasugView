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
            <img src="{{ session('profile_pic') ?? asset('img/profile.png') }}" class="rounded-circle" style="width: 40px; height: 40px;">
            <div class="ms-2 fw-bold">{{ session('username') ?? 'Guest' }}</div>
          </div>
          <input type="hidden" name="signup_id" value="{{ session('signup_id') }}">
          <textarea name="content" class="form-control border-0" rows="4" placeholder="Write something..." style="resize: none; font-size: 1.2rem;"></textarea>
          <input type="file" name="media[]" id="mediaInput" accept="image/*" multiple hidden>
          <div id="mediaPreview" class="file-preview"></div>
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

<!-- Fullscreen Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true" style="z-index: 2000;">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content bg-black text-white border-0 d-flex align-items-center justify-content-center position-relative">

      <button type="button" class="btn btn-close btn-close-white position-absolute top-0 start-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>

      <!-- Prev/Next Navigation -->
      <button id="prevBtn" onclick="prevImage()" class="btn position-absolute start-0 top-50 translate-middle-y bg-secondary-subtle rounded-circle" style="width: 45px; height: 45px;">
        <i class="fas fa-chevron-left text-dark"></i>
      </button>
      <button id="nextBtn" onclick="nextImage()" class="btn position-absolute end-0 top-50 translate-middle-y bg-secondary-subtle rounded-circle" style="width: 45px; height: 45px;">
        <i class="fas fa-chevron-right text-dark"></i>
      </button>

      <!-- Main Image Display -->
      <img id="modalImage" src="" class="img-fluid rounded border border-white"
        style="max-height: 90vh; max-width: 95vw; object-fit: contain;" />

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

let fileList = [];
const preview = document.getElementById('mediaPreview');
const input = document.getElementById('mediaInput');
input.addEventListener('change', function () {
  fileList = Array.from(this.files);
  updatePreview();
});
function updatePreview() {
  preview.innerHTML = '';
  fileList.forEach((file, index) => {
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const div = document.createElement('div');
        div.classList.add('preview-item');
        div.innerHTML = `<img src="${e.target.result}" alt="preview"><span class="remove-preview" onclick="removePreview(${index})">&times;</span>`;
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
document.getElementById('locationSearchInput')?.addEventListener('keyup', function () {
  const keyword = this.value.toLowerCase();
  document.querySelectorAll('#locationSuggestions li').forEach(item => {
    item.style.display = item.textContent.toLowerCase().includes(keyword) ? '' : 'none';
  });
});
</script>

<style> 
 .file-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 1rem;
  }

  .file-preview .preview-item {
    position: relative;
    width: 130px;
    height: 130px;
    flex-shrink: 0;
  }

  .file-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #ccc;
  }

  .file-preview .remove-preview {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: #dc3545;
    color: white;
    border-radius: 50%;
    padding: 0 5px;
    font-weight: bold;
    font-size: 14px;
    cursor: pointer;
    line-height: 1;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
  }

</style>