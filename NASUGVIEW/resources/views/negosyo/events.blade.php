@extends('negosyo.negosyolayout')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
  body {
    background-color: #d0e7cc;
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
  .card-custom h2 {
    font-family: 'Georgia', serif;
    font-weight: 700;
    color: #204020;
    font-size: 28px;
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
  }
  .table-hover tbody tr:hover {
    background-color: #f1f5f1;
  }
  .btn-custom-green {
    background-color: #14532d;
    color: #ffffff;
    border: none;
  }
  .btn-custom-green:hover {
    background-color: #0f3f1f;
    color: #ffffff;
  }
</style>

<div class="container-fluid px-3">
  <div class="card card-custom">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
      <h2 class="mb-2 mb-md-0">Event Management</h2>
      <a href="{{ route('negosyo.events.create') }}" class="btn btn-custom-green">
        <i class="bi bi-plus-circle me-1"></i> Create New Event
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th style="width: 260px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($forms as $form)
            <tr>
              <td>{{ $form->title }}</td>
              <td>{{ Str::limit($form->description, 50) }}</td>
              <td>
                @if ($form->form_id)
                  <a href="{{ route('negosyo.events.show', $form->form_id) }}" class="btn btn-sm btn-custom-green mb-1">
                    <i class="bi bi-eye-fill me-1"></i> View
                  </a>
                  <a href="{{ route('negosyo.events.edit', $form->form_id) }}" class="btn btn-sm btn-custom-green mb-1">
                    <i class="bi bi-pencil-fill me-1"></i> Edit
                  </a>
                  <form action="{{ route('negosyo.events.destroy', $form->form_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this event?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-custom-green mb-1">
                      <i class="bi bi-trash-fill me-1"></i> Delete
                    </button>
                  </form>
                @else
                  <a href="#" class="btn btn-sm btn-secondary disabled mb-1">
                    <i class="bi bi-eye-slash me-1"></i> View
                  </a>
                  <button class="btn btn-sm btn-secondary disabled mb-1">
                    <i class="bi bi-x-circle me-1"></i> Delete
                  </button>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center text-muted">No events found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@if(session('success'))
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <i class="bi bi-check-circle-fill text-success mb-2" style="font-size: 2rem;"></i>
      <p class="mb-0">{{ session('success') }}</p>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
    setTimeout(() => { successModal.hide(); }, 2000);
  });
</script>
@endif

@endsection
