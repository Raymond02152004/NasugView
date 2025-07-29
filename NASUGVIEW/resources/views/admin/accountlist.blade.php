@extends('admin.adminlayout')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
  html, body {
    height: 100%; margin: 0; background-color: #e8f4e5; overflow-x: hidden;
  }
  .container { min-height: 100vh; }
  .card {
    max-width: 1400px;
    margin: 3rem auto;
    border-radius: 30px;
    background-color: #ffffff;
    padding: 3rem 2rem;
    border: 3px solid #14532d;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  }
  .import-header {
    font-size: 2rem; font-family: 'Georgia', serif; font-weight: bold; color: #204020;
  }
  .import-moto {
    font-size: 1rem; font-family: 'Georgia', serif; color: #4b604b; margin-bottom: 1.5rem;
  }
  .search-box input {
    padding: 8px 12px;
    border: 1px solid #14532d;
    border-radius: 8px;
    outline: none;
    width: 100%;
    max-width: 250px;
  }
  .dashed-box {
    border: 2px dashed #14532d;
    border-radius: 16px;
    padding: 2rem;
    background-color: #fdfdfd;
  }
  .grid-wrapper {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }
  .file-tile {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #204020;
    font-family: 'Georgia', serif;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    position: relative;
  }
  .file-tile i { font-size: 36px; margin-bottom: 8px; }
  .file-checkbox {
    position: absolute;
    top: -10px;
    left: -10px;
    display: none;
  }
  .file-label {
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    max-width: 120px;
    word-break: break-word;
  }
  .delete-mode .file-checkbox {
    display: block !important;
  }
  #csv_upload {
    display: none;
  }
</style>

<div class="container">
  <div class="card">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-2 mb-3">
      <div>
        <div class="import-header">Import document</div>
        <div class="import-moto">Upload business list files below</div>
      </div>
      <div class="search-box">
        <input type="text" id="fileSearchInput" placeholder="Search file...">
      </div>
    </div>

    <!-- ✅ CSV Import Form (MUST be outside the delete form) -->
    <form method="POST" action="{{ route('admin.import.account') }}" enctype="multipart/form-data">

      @csrf
      <input type="file" name="csv_file" id="csv_upload" accept=".csv" onchange="document.getElementById('csvForm').submit()">
    </form>

    <!-- ✅ Delete Form -->
    <form id="deleteForm" method="POST" action="{{ route('admin.deleteSelected') }}">
      @csrf
      <div class="dashed-box">
        <div class="grid-wrapper" id="fileGrid">
          @foreach($files as $file)
          <div class="file-tile" data-filename="{{ strtolower(preg_replace('/^\d+_/', '', $file->filename)) }}">
            <input type="checkbox" name="file_ids[]" value="{{ $file->imported_id }}" class="file-checkbox">
            <a href="{{ route('admin.viewimportedfile', ['filename' => $file->filename]) }}"
              class="text-decoration-none text-reset d-flex flex-column align-items-center">
              
              <!-- ✅ ICON ON TOP -->
              <i class="bi bi-file-earmark-text"></i>

              <!-- ✅ FILENAME BELOW -->
              <span class="file-label mt-1">{{ preg_replace('/^\d+_/', '', $file->filename) }}</span>
            </a>
          </div>
        @endforeach


          <!-- CSV Upload Button (Linked to input above) -->
          <div class="file-tile" id="uploadTile" onclick="document.getElementById('csv_upload').click()">
            <i class="bi bi-file-earmark-plus"></i>
            <span>Import CSV File</span>
          </div>
        </div>
      </div>

      <div class="text-end mt-5 pt-2">
        <button type="button" id="toggleDelete" class="btn btn-success" style="background-color: #14532d; border-color: #14532d;">
          <i class="bi bi-trash"></i> Delete
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Imported Modal -->
<div class="modal fade" id="importedModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <i class="bi bi-check-circle-fill text-success mb-2" style="font-size: 2rem;"></i>
      <p class="mb-0">Imported Successfully!</p>
    </div>
  </div>
</div>

<!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4 text-center">
      <p class="mb-3">Are you sure you want to delete this?</p>
      <div class="d-flex justify-content-center gap-3">
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">No</button>
        <button type="submit" class="btn btn-success px-4" id="confirmDelete">Yes</button>
      </div>
    </div>
  </div>
</div>

<!-- Deleted Modal -->
<div class="modal fade" id="deletedModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <i class="bi bi-check-circle-fill text-success mb-2" style="font-size: 2rem;"></i>
      <p class="mb-0">Deleted Successfully!</p>
    </div>
  </div>
</div>

<script>
  $('#toggleDelete').on('click', function () {
    const checkboxes = $('.file-checkbox');
    if (checkboxes.filter(':visible').length === 0) {
      checkboxes.show();
      $('.file-tile').addClass('delete-mode');
    } else if (checkboxes.filter(':checked').length > 0) {
      $('#confirmDeleteModal').modal('show');
    } else {
      checkboxes.hide();
      $('.file-tile').removeClass('delete-mode');
    }
  });

  $('#confirmDelete').on('click', function () {
    const selected = $('input[name="file_ids[]"]:checked');
    if (selected.length > 0) {
      $('#deleteForm').submit();
    }
  });

  @if(session('deleted'))
  $(document).ready(function () {
    const deletedModal = new bootstrap.Modal(document.getElementById('deletedModal'));
    deletedModal.show();
    setTimeout(() => { deletedModal.hide(); }, 2000);
  });
  @endif

  @if(session('imported'))
  $(document).ready(function () {
    const importedModal = new bootstrap.Modal(document.getElementById('importedModal'));
    importedModal.show();
    setTimeout(() => { importedModal.hide(); }, 2000);
  });
  @endif

  document.getElementById('fileSearchInput').addEventListener('input', function () {
    const query = this.value.toLowerCase();
    document.querySelectorAll('.file-tile').forEach(tile => {
      const filename = tile.dataset.filename;
      tile.style.display = filename.includes(query) ? 'flex' : 'none';
    });
  });
</script>
@endsection
