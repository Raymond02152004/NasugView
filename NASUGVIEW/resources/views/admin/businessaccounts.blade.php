@extends('admin.adminlayout')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
  body {
    overflow-x: hidden;
    min-height: 100vh;
    margin: 0;
    padding: 0;
  }

  .card-custom {
    max-width: 100%;
    margin: 3rem auto;
    padding: 3rem 2rem;
    border-radius: 32px;
    border: 2px solid #14532d;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
  }

  .header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 20px;
  }

  .header-section h2 {
    font-family: 'Georgia', serif;
    font-weight: 700;
    color: #204020;
    font-size: 28px;
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
    margin-top: 10px;
  }

  @media (min-width: 768px) {
    .search-box {
      margin-top: 0;
    }
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

  .table td.action-col {
    width: 1%;
    white-space: nowrap;
    text-align: center;
  }

  .table-hover tbody tr:hover {
    background-color: #f1f5f1;
  }

  .btn-green {
    background-color: #14532d;
    color: #fff;
    border: none;
  }

  .btn-green:hover {
    background-color: #0f3f24;
  }

  .alert-warning {
    font-family: 'Georgia', serif;
    font-size: 17px;
    text-align: center;
    margin-top: 2rem;
  }
</style>

<div class="container-fluid px-3">
  <div class="card card-custom">

    <div class="header-section">
      <h2>Business Owner Accounts</h2>
      <input type="text" id="searchInput" class="search-box" placeholder="Search table...">
    </div>

    @if(count($accounts))
      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="accountsTable">
          <thead>
            <tr>
              <th>Username</th>
              <th>Email</th>
              <th class="action-col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($accounts as $account)
              <tr>
                <td>{{ $account->username ?? '-' }}</td>
                <td>{{ $account->email ?? '-' }}</td>
                <td class="action-col">
                  <form method="POST" action="{{ route('admin.businessaccounts.resetpassword', ['signup_id' => $account->signup_id]) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-green">Send Reset Password</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="alert alert-warning">No business owner accounts found.</div>
    @endif

  </div>
</div>

<script>
  $(document).ready(function () {
    $("#searchInput").on("keyup", function () {
      var value = $(this).val().toLowerCase();
      $("#accountsTable tbody tr").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
</script>
@endsection
