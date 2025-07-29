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

  .search-box {
    max-width: 300px;
    font-family: 'Georgia', serif;
    font-size: 15px;
    padding: 8px 12px;
    border: 2px solid #14532d;
    border-radius: 8px;
    width: 100%;
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

  .form-select {
    font-family: 'Georgia', serif;
    font-size: 15px;
  }
</style>

<div class="container-fluid px-3">
  <div class="card card-custom">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
      <h2 class="mb-2 mb-md-0">Certificate</h2>
      <div class="d-flex gap-2">
        <!-- 🔽 Dropdown Filter -->
        <select class="form-select" style="max-width: 200px; border: 2px solid #14532d;">
          <option selected disabled>Filter by...</option>
          <option value="issued">Issued</option>
          <option value="pending">Pending</option>
          <option value="expired">Expired</option>
        </select>

        <!-- 🔍 Search -->
        <input type="text" id="searchInput" class="search-box" placeholder="Search certificate...">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover" id="certificateTable">
        <thead>
          <tr>
            <th>Business Name</th>
            <th>Date Issued</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @if(!empty($certificates) && count($certificates))
            @foreach($certificates as $cert)
              <tr>
                <td>{{ $cert->certificate_no }}</td>
                <td>{{ $cert->business_name }}</td>
                <td>{{ $cert->date_issued }}</td>
                <td>{{ $cert->status }}</td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="4" class="text-center text-muted"></td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('#searchInput').on('input', function () {
      const value = $(this).val().toLowerCase().trim();

      $('#certificateTable tbody tr').each(function () {
        const row = $(this).text().toLowerCase();
        $(this).toggle(row.includes(value));
      });
    });
  });
</script>
@endsection
