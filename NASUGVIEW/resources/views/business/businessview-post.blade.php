@extends('business.businessowner')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
  .business-card {
    max-width: 1140px;
    margin: 2rem auto;
    border-radius: 20px;
    border: 2px solid #14532d;
    background: #fff;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }

  .banner-img {
    width: 100%;
    height: 380px;
    object-fit: cover;
  }

  .business-header {
    padding: 1.5rem 2rem;
    background: #f7faf7;
    border-bottom: 1px solid #ccc;
  }

  .business-header h4 {
    font-weight: bold;
    color: #204020;
  }

  .business-tabs {
    border-bottom: 2px solid #dee2e6;
  }

  .business-tabs .nav-link {
    color: #444;
    font-weight: 500;
    font-family: 'Georgia', serif;
  }

  .business-tabs .nav-link:hover {
    color: #14532d;
  }

  .business-tabs .nav-link.active {
    color: #14532d;
    font-weight: bold;
    border-bottom: 3px solid #14532d;
    background-color: transparent;
  }

  .tab-pane label {
    color: #14532d;
    font-weight: bold;
    font-size: 0.95rem;
  }

  .info-item {
    margin-bottom: 1rem;
  }

  .map-container {
    height: 250px;
    border-radius: 10px;
    border: 1px solid #ccc;
  }

  .action-icon {
    font-size: 1.2rem;
    color: #14532d;
    margin-left: 0.5rem;
  }

  .edit-btn {
    position: absolute;
    top: 20px;
    right: 20px;
  }

  .text-muted {
    font-size: 0.95rem;
    color: #6c757d !important;
  }

  .text-info-line {
    font-size: 1rem;
    color: #000;
    margin-bottom: 0.3rem;
  }

  .btn-primary {
    background-color: #14532d;
    border-color: #14532d;
  }

  .btn-primary:hover {
    background-color: #0d3f22;
    border-color: #0d3f22;
  }
</style>

<div class="business-card position-relative">
  <!-- Edit Dropdown -->
  <div class="dropdown edit-btn">
    <button class="btn btn-sm btn-light border dropdown-toggle" data-bs-toggle="dropdown">
      <i class="bi bi-three-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
      <li>
        <a class="dropdown-item" href="{{ route('business.editpost', ['id' => $post->business_id]) }}">
          <i class="bi bi-pencil-square me-2"></i>Edit Post
        </a>
      </li>
    </ul>
  </div>

  <!-- Banner -->
  @if ($post->image_path)
  <img src="{{ asset('storage/' . $post->image_path) }}" alt="Business Image" class="banner-img">
  @endif

  <!-- Header Info -->
  <div class="business-header">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-1">{{ $post->business_name }}</h4>
        <div class="text-muted">{{ $post->address }}</div>
      </div>
    </div>
  </div>

  <!-- Tabs -->
  <ul class="nav nav-tabs business-tabs px-3 pt-2" id="businessTab" role="tablist">
    <li class="nav-item">
      <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#infoTab" type="button" role="tab">Info</button>
    </li>
    <li class="nav-item">
      <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviewsTab" type="button" role="tab">Reviews</button>
    </li>
    <li class="nav-item">
      <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#servicesTab" type="button" role="tab">Services</button>
    </li>
    <li class="nav-item">
      <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#productsTab" type="button" role="tab">Products</button>
    </li>
    <li class="nav-item">
      <button class="nav-link" id="menu-tab" data-bs-toggle="tab" data-bs-target="#menuTab" type="button" role="tab">Menu</button>
    </li>
  </ul>

  <div class="tab-content p-4" id="businessTabContent">
    <!-- Info Tab -->
    <div class="tab-pane fade show active" id="infoTab" role="tabpanel" aria-labelledby="info-tab">
      <div class="info-item">
        <label>Description</label>
        <p class="text-info-line">{{ $post->description }}</p>
      </div>
      <div class="info-item d-flex align-items-center justify-content-between">
        <div>
          <label>Contact</label>
          <p class="text-info-line">{{ $post->contact_info }}</p>
        </div>
        <a href="tel:{{ $post->contact_info }}" class="action-icon">
          <i class="bi bi-telephone-forward-fill"></i>
        </a>
      </div>
      <div class="info-item">
        <label>Location</label>
        <div id="map" class="map-container mt-2"></div>
      </div>
    </div>

    <!-- Reviews Tab -->
    <div class="tab-pane fade" id="reviewsTab" role="tabpanel" aria-labelledby="reviews-tab">
      <p class="text-info-line">No reviews available yet.</p>
    </div>

    <!-- Services Tab -->
    <div class="tab-pane fade" id="servicesTab" role="tabpanel" aria-labelledby="services-tab">
      <p class="text-info-line">List of services offered will appear here.</p>
      <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#serviceModal">Post a Service</button>
    </div>

    <!-- Products Tab -->
    <div class="tab-pane fade" id="productsTab" role="tabpanel" aria-labelledby="products-tab">
      <p class="text-info-line">List of products available will appear here.</p>
      <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#productModal">Post a Product</button>
    </div>

    <!-- Menu Tab -->
    <div class="tab-pane fade" id="menuTab" role="tabpanel" aria-labelledby="menu-tab">
      <p class="text-info-line">Menu details will appear here.</p>
      <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#menuModal">Post a Menu Item</button>
    </div>
  </div>
</div>

<!-- Service Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="serviceModalLabel">Post a Service</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Form for posting a service -->
        <form action="{{ route('business.postservice', ['id' => $post->business_id]) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="serviceName" class="form-label">Service Name</label>
            <input type="text" class="form-control" id="serviceName" name="service_name" required>
          </div>
          <div class="mb-3">
            <label for="serviceDescription" class="form-label">Service Description</label>
            <textarea class="form-control" id="serviceDescription" name="service_description" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="servicePrice" class="form-label">Price</label>
            <input type="number" class="form-control" id="servicePrice" name="service_price" required>
          </div>
          <div class="mb-3">
            <label for="serviceImage" class="form-label">Upload Image</label>
            <input type="file" class="form-control" id="serviceImage" name="service_image" accept="image/*" required>
          </div>
          <button type="submit" class="btn btn-primary">Post Service</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Post a Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Form for posting a product -->
        <form action="{{ route('business.postproduct', ['id' => $post->business_id]) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="productName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="productName" name="product_name" required>
          </div>
          <div class="mb-3">
            <label for="productDescription" class="form-label">Product Description</label>
            <textarea class="form-control" id="productDescription" name="product_description" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="productPrice" class="form-label">Price</label>
            <input type="number" class="form-control" id="productPrice" name="product_price" required>
          </div>
          <div class="mb-3">
            <label for="productImage" class="form-label">Upload Image</label>
            <input type="file" class="form-control" id="productImage" name="product_image" accept="image/*" required>
          </div>
          <button type="submit" class="btn btn-primary">Post Product</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Menu Modal -->
<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="menuModalLabel">Post a Menu Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Form for posting a menu item -->
        <form action="{{ route('business.postmenu', ['id' => $post->business_id]) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="menuItemName" class="form-label">Menu Item Name</label>
            <input type="text" class="form-control" id="menuItemName" name="menu_item_name" required>
          </div>
          <div class="mb-3">
            <label for="menuItemDescription" class="form-label">Menu Item Description</label>
            <textarea class="form-control" id="menuItemDescription" name="menu_item_description" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="menuItemPrice" class="form-label">Price</label>
            <input type="number" class="form-control" id="menuItemPrice" name="menu_item_price" required>
          </div>
          <div class="mb-3">
            <label for="menuItemImage" class="form-label">Upload Image</label>
            <input type="file" class="form-control" id="menuItemImage" name="menu_item_image" accept="image/*" required>
          </div>
          <button type="submit" class="btn btn-primary">Post Menu Item</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const lat = {{ $post->latitude ?? 14.072177 }};
    const lng = {{ $post->longitude ?? 120.649967 }};
    let map = L.map('map').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
    }).addTo(map);

    L.marker([lat, lng]).addTo(map).bindPopup("Business Location").openPopup();
  });
</script>

@endsection
