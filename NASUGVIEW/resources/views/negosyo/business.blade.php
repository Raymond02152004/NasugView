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
</style>

<div class="container-fluid px-3">
  <div class="card card-custom">

    <!-- 🔁 Responsive Header + Search Row -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
      <h2 class="mb-2 mb-md-0">Business List</h2>
      <input type="text" id="searchInput" class="search-box" placeholder="Search business...">
    </div>

    @if(!empty($businesses) && count($businesses))
    <div class="table-responsive">
      <table class="table table-bordered table-hover" id="businessTable">
        <thead>
          <tr>
            <th>Business No.</th>
            <th>Business Name</th>
            <th>Business Type</th>
            <th>Business Address</th>
          </tr>
        </thead>
        <tbody>
          @foreach($businesses as $biz)
          <tr>
            <td>{{ $biz->business_id }}</td>
            <td>{{ $biz->business_name }}</td>
            <td>{{ $biz->business_type }}</td>
            <td>{{ $biz->business_address }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
      <div class="alert alert-warning">No business records found.</div>
    @endif
  </div>
</div>

<script>
  $(document).ready(function () {
    $('#searchInput').on('input', function () {
      const value = $(this).val().toLowerCase().trim();

      $('#businessTable tbody tr').each(function () {
        const row = $(this).text().toLowerCase();
        $(this).toggle(row.includes(value));
      });
    });
  });
</script>
@endsection
