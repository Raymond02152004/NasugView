@extends('business.businessowner')

@section('content')
<!-- Bootstrap + Leaflet CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
  .card-custom {
    max-width: 800px;
    margin: 3rem auto;
    padding: 2rem;
    border-radius: 20px;
    border: 2px solid #14532d;
    background-color: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  }

  .form-label {
    font-weight: bold;
    color: #14532d;
  }

  .btn-green {
    background-color: #14532d !important;
    color: #fff !important;
    border-radius: 8px;
    padding: 10px 18px;
    font-family: 'Georgia', serif;
  }

  .btn-green:hover {
    background-color: #0f3f24 !important;
  }

  #map {
    height: 300px;
    border: 2px solid #ccc;
    border-radius: 10px;
    margin-bottom: 1rem;
  }

  .guide-box {
    background-color: #e6f4e7;
    border-left: 5px solid #14532d;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    font-family: 'Georgia', serif;
  }

  .guide-box h5 {
    color: #14532d;
    font-weight: bold;
  }
</style>

<div class="container">
  <div class="card card-custom">
    <h3 class="text-center mb-4"><i class="bi bi-shop me-2"></i>Post Business Details</h3>

    <div class="guide-box">
      <h5><i class="bi bi-info-circle me-1"></i> How to Post Your Business</h5>
      <ul class="mb-0">
        <li>Fill in the business name, description, contact info, and address.</li>
        <li>Upload an image that represents your business (optional).</li>
        <li><strong>Click the map</strong> to select your business location.</li>
        <li>The latitude and longitude will automatically be filled below the map.</li>
        <li>Click <strong>"Post Business"</strong> to submit your listing.</li>
      </ul>
    </div>

    <form method="POST" action="{{ route('business.marketplace.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label for="business_name" class="form-label">Business Name</label>
        <input type="text" class="form-control" id="business_name" name="business_name" required>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Business Description</label>
        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
      </div>

      <div class="mb-3">
        <label for="contact_info" class="form-label">Contact Information</label>
        <input type="text" class="form-control" id="contact_info" name="contact_info" required>
      </div>

      <div class="mb-3">
        <label for="address" class="form-label">Full Business Address</label>
        <input type="text" class="form-control" id="address" name="address" placeholder="e.g. JP Laurel St, Nasugbu, Batangas" required>
      </div>

      <div class="mb-3">
        <label for="image" class="form-label">Business Image</label>
        <input type="file" class="form-control" id="image" name="image">
      </div>

      <div class="mb-3">
        <label class="form-label">Select Business Location (Click the Map)</label>
        <div id="map"></div>
      </div>

      <div class="mb-3">
        <label for="latitude" class="form-label">Latitude</label>
        <input type="text" class="form-control" id="latitude" name="latitude" readonly required>
      </div>

      <div class="mb-3">
        <label for="longitude" class="form-label">Longitude</label>
        <input type="text" class="form-control" id="longitude" name="longitude" readonly required>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-green">
          <i class="bi bi-send-fill me-1"></i>Post Business
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ✅ Success Modal (Bootstrap fade) -->
@if(session('success'))
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <i class="bi bi-check-circle-fill text-success mb-3" style="font-size: 3rem;"></i>
      <p class="fs-5">{{ session('success') }}</p>
      <button type="button" class="btn btn-green mt-2" data-bs-dismiss="modal">OK</button>
    </div>
  </div>
</div>
@endif

<!-- JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  let map = L.map('map').setView([14.072177, 120.649967], 12);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
  }).addTo(map);

  let marker;

  map.on('click', function (e) {
    const lat = e.latlng.lat.toFixed(6);
    const lng = e.latlng.lng.toFixed(6);

    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    if (marker) {
      map.removeLayer(marker);
    }

    marker = L.marker([lat, lng]).addTo(map);
  });

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (pos) {
      const lat = pos.coords.latitude.toFixed(6);
      const lng = pos.coords.longitude.toFixed(6);
      map.setView([lat, lng], 15);
    });
  }

  // Automatically show modal if it exists
  document.addEventListener('DOMContentLoaded', function () {
    const successModal = document.getElementById('successModal');
    if (successModal) {
      new bootstrap.Modal(successModal).show();
    }
  });
</script>
@endsection
