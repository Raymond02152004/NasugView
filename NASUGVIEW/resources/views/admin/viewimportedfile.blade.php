@extends('admin.adminlayout')

@section('content')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

  .back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background-color: #14532d;
    color: #fff;
    border: none;
    padding: 6px 14px;
    border-radius: 8px;
    font-family: 'Georgia', serif;
    font-weight: 600;
    font-size: 14px;
    line-height: 1.2;
    text-decoration: none;
    margin-bottom: 1.2rem;
    max-width: fit-content;
  }

  .back-btn:hover {
    background-color: #0f3f24;
  }

  .back-btn i {
    font-size: 16px;
  }

  .header-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
  }

  @media (min-width: 768px) {
    .header-section {
      flex-direction: row;
      align-items: center;
    }
  }

  .card-custom h2 {
    font-family: 'Georgia', serif;
    font-weight: 700;
    color: #204020;
    font-size: 28px;
    text-transform: capitalize;
    margin: 0;
  }

  .search-box {
    width: 100%;
    max-width: 300px;
    font-family: 'Georgia', serif;
    font-size: 16px;
    padding: 0.5rem 1rem;
    border: 2px solid #14532d;
    border-radius: 8px;
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

  .pagination {
    margin-top: 1rem;
    justify-content: center;
    flex-wrap: wrap;
  }

  .alert-warning {
    font-family: 'Georgia', serif;
    font-size: 17px;
    text-align: center;
    margin-top: 2rem;
  }

  .pagination .page-link {
    color: #14532d;
    border: 1px solid #14532d;
    font-family: 'Georgia', serif;
  }

  .pagination .page-link:hover {
    background-color: #cce5cc;
    color: #0f3f24;
  }

  .pagination .page-item.active .page-link {
    background-color: #14532d;
    border-color: #14532d;
    color: #fff;
  }
</style>

<div class="container-fluid px-3">
  <div class="card card-custom">
    <a href="{{ route('admin.accountlist') }}" class="back-btn">
      <i class="bi bi-arrow-left"></i> Back
    </a>

    @php
      $displayName = preg_replace('/^\d+_/', '', pathinfo($filename, PATHINFO_FILENAME));
    @endphp

    <div class="header-section">
      <h2>{{ $displayName }}</h2>
      <input type="text" id="searchInput" class="search-box" placeholder="Search table...">
    </div>

    @if(count($rows))
      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="csvTable">
          <thead>
            <tr>
              @foreach($rows[0] as $header)
                <th>{{ $header }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach(array_slice($rows, 1) as $row)
              <tr>
                @foreach($row as $cell)
                  <td>{{ $cell }}</td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <nav><ul class="pagination" id="pagination"></ul></nav>
    @else
      <div class="alert alert-warning">No data found in file.</div>
    @endif
  </div>
</div>

<script>
  $(document).ready(function () {
    const rowsPerPage = 10;
    const rows = $("#csvTable tbody tr");
    const totalRows = rows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    let currentPage = 1;

    function showPage(page) {
      rows.hide();
      rows.slice((page - 1) * rowsPerPage, page * rowsPerPage).show();

      $("#pagination").empty();
      for (let i = 1; i <= totalPages; i++) {
        $("#pagination").append(
          `<li class="page-item ${i === page ? 'active' : ''}">` +
          `<a class="page-link" href="#">${i}</a></li>`
        );
      }
    }

    $(document).on("click", "#pagination a", function (e) {
      e.preventDefault();
      currentPage = parseInt($(this).text());
      showPage(currentPage);
    });

    $("#searchInput").on("keyup", function () {
      const value = $(this).val().toLowerCase();
      $("#csvTable tbody tr").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
    });

    showPage(currentPage);
  });
</script>
@endsection
