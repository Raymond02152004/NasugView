@extends('negosyo.negosyolayout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
  body {
    background-color: #d0e7cc;
    overflow-x: hidden;
  }

  .container {
    padding: 1.5rem;
  }

  .welcome-card {
    margin: 2rem auto 1rem;
    padding: 1.5rem 2rem;
    border-radius: 20px;
    background-color: #ffffff;
    border: 2px solid #14532d;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    flex-wrap: wrap;
  }

  .welcome-card img {
    width: 35px; /* Reduced profile image size */
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #14532d;
  }

  .welcome-text {
    font-size: 1.2rem;
    color: #14532d;
    font-weight: 600;
    font-family: 'Segoe UI', sans-serif;
  }

  .stats-summary-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 2rem;
  }

  .stat-card {
    flex: 1 1 240px;
    background: #14532d;
    color: #ffffff;
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
  }

  .stat-card h5 {
    font-size: 0.9rem;
    text-transform: uppercase;
    color: #ffffff !important;
  }

  .stat-value {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 10px;
    gap: 10px;
  }

  .stat-value h2 {
    font-size: 2rem;
    font-weight: bold;
    font-family: monospace;
    color: #ffffff;
  }

  .stat-value i {
    font-size: 1.8rem;
    color: #e3f6df;
  }

  .overview-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 2rem;
  }

  .overview-card,
  .summary-card {
    flex: 1 1 48%;
    background-color: #ffffff;
    border: 2px solid #14532d;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    min-height: 250px;
  }

  .overview-card h5,
  .summary-card h4 {
    font-weight: bold;
    color: #14532d;
    font-family: 'Georgia', serif;
    font-size: 1rem;
  }

  .filter-button {
    background-color: #14532d;
    border: none;
    color: #fff;
    padding: 5px 14px;
    border-radius: 8px;
    font-size: 0.9rem;
  }

  .filter-button:hover {
    background-color: #104220;
  }

  .chart-card {
    background-color: #ffffff;
    border: 2px solid #14532d;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
  }

  .business-item {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 15px;
  }

  .business-item img {
    width: 50px;
    height: 50px;
    border-radius: 50%; /* Circular images */
  }

  .rank {
    font-size: 1.5rem;
    font-weight: bold;
    color: #14532d;
    margin-right: 10px;
    background-color: #e3f6df;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .star-rating {
    color: #FFD700; /* Gold color for stars */
    font-size: 1rem;
  }

  @media screen and (max-width: 992px) {
    .overview-card,
    .summary-card,
    .stat-card {
      flex: 1 1 100%;
    }

    .stats-summary-row,
    .overview-wrapper {
      flex-direction: column;
    }

    .welcome-card {
      flex-direction: column;
      align-items: flex-start;
    }
  }
</style>

<div class="container">
  <div class="welcome-card">
    <img src="{{ session('profile_pic') ?? asset('img/profile.png') }}" alt="Profile">
    <div class="welcome-text">Welcome back, {{ session('username') ?? 'User' }}!</div>
  </div>

  <div class="stats-summary-row">
    <div class="stat-card">
      <h5>NO. OF CONSUMER</h5>
      <div class="stat-value">
        <h2>{{ number_format($consumerCount) }}</h2>
        <i class="bi bi-people-fill"></i>
      </div>
    </div>
    <div class="stat-card">
      <h5>NO. OF BUSINESS OWNER</h5>
      <div class="stat-value">
        <h2>{{ number_format($businessOwnerCount) }}</h2>
        <i class="bi bi-shop-window"></i>
      </div>
    </div>
    <div class="stat-card">
      <h5>TOTAL NUMBER OF USERS</h5>
      <div class="stat-value">
        <h2>{{ number_format($consumerCount + $businessOwnerCount) }}</h2>
        <i class="bi bi-person-lines-fill"></i>
      </div>
    </div>
  </div>

  <div class="overview-wrapper">
    <div class="overview-card">
      <h5>Business Type Distribution</h5>
      <canvas id="businessTypeChart" style="max-height: 180px;"></canvas>
    </div>
    <div class="summary-card">
      <h4>Top Rated Businesses</h4>
      <div class="business-item">
        <div class="rank">1</div>
        <img src="https://th.bing.com/th/id/OIP.H3W4yMaKlxlJ7YK3ay6HDgHaHa?w=173&h=180&c=7&r=0&o=7&pid=1.7&rm=3" alt="Business 1">
        <div>
          <div><strong>Ikaw Bahala Shop</strong></div>
          <div class="star-rating">
            &#9733;&#9733;&#9733;&#9733;&#9733; <!-- 5 stars -->
          </div>
        </div>
      </div>
      <div class="business-item">
        <div class="rank">2</div>
        <img src="https://kahit-saan.weebly.com/uploads/4/0/7/1/40717055/1412908487.png" alt="Business 2">
        <div>
          <div><strong>Kahit Saan</strong></div>
          <div class="star-rating">
            &#9733;&#9733;&#9733;&#9733;&#9734; <!-- 4 stars -->
          </div>
        </div>
      </div>
      <div class="business-item">
        <div class="rank">3</div>
        <img src="https://th.bing.com/th/id/OIP.uompDkzYBlSMhTLs_rrl9gHaFB?w=257&h=180&c=7&r=0&o=7&pid=1.7&rm=3" alt="Business 3">
        <div>
          <div><strong>Kainan sa Dalampasigan</strong></div>
          <div class="star-rating">
            &#9733;&#9733;&#9733;&#9733;&#9734; <!-- 4 stars -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="overview-wrapper">
    <div class="overview-card w-100">
      <h5 class="mb-3">Event Statistics</h5>
      <div class="d-flex flex-wrap gap-3">
        <div class="stat-card position-relative flex-grow-1">
          <div class="d-flex justify-content-between">
            <h5>NO. OF EVENTS</h5>
            <div class="dropdown">
              <button class="filter-button dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-funnel-fill me-1"></i> Filter
              </button>
              <ul class="dropdown-menu">
                @foreach(['01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December'] as $num => $name)
                  <li><a class="dropdown-item event-month-option" href="#" data-value="{{ $num }}">{{ $name }}</a></li>
                @endforeach
              </ul>
            </div>
          </div>
          <div class="stat-value mt-3" id="event-count">
            <h2>{{ number_format($eventCount ?? 0) }}</h2>
            <i class="bi bi-calendar-event"></i>
          </div>
        </div>

        <div class="stat-card position-relative flex-grow-1">
          <div class="d-flex justify-content-between">
            <h5>NO. OF ATTENDEES</h5>
            <div class="dropdown">
              <button class="filter-button dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-funnel-fill me-1"></i> Filter
              </button>
              <ul class="dropdown-menu" id="attendee-event-list">
                @foreach(App\Models\Form::pluck('title') as $title)
                  <li><a class="dropdown-item attendee-title-option" href="#" data-title="{{ $title }}">{{ $title }}</a></li>
                @endforeach
              </ul>
            </div>
          </div>
          <div class="stat-value mt-3" id="attendee-count">
            <h2>{{ number_format($attendeeCount ?? 0) }}</h2>
            <i class="bi bi-people"></i>
          </div>
        </div>
      </div>

      <div class="chart-card mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-semibold mb-0" style="color: #14532d;">Monthly Event Trend</h5>
          <div class="dropdown">
            <button class="dropdown-toggle border-0 text-white px-3 py-1 rounded-3" style="background-color: #14532d;" type="button" data-bs-toggle="dropdown">
              <i class="bi bi-funnel-fill me-1"></i> Filter
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li class="dropdown-header">By Year</li>
              @foreach($availableYears as $year)
                <li><a class="dropdown-item year-filter-option" href="#" data-year="{{ $year }}">{{ $year }}</a></li>
              @endforeach
            </ul>
          </div>
        </div>
        <canvas id="eventLineChart" height="100"></canvas>
      </div>
    </div>
  </div>

  <form id="lineChartFilterForm" method="GET" action="{{ route('negosyo.dashboard') }}">
    <input type="hidden" name="year" id="filterYearInput" value="{{ $selectedYear }}">
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const businessTypeCtx = document.getElementById('businessTypeChart').getContext('2d');
  new Chart(businessTypeCtx, {
    type: 'pie',
    data: {
      labels: {!! json_encode($businessTypeCounts->keys()) !!},
      datasets: [{
        data: {!! json_encode($businessTypeCounts->values()) !!},
        backgroundColor: ['#14532d', '#2e7d32', '#4caf50', '#66bb6a', '#81c784']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom' }
      }
    }
  });

  const ctx = document.getElementById('eventLineChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [{
        label: 'Events in {{ $selectedYear }}',
        data: {!! json_encode(array_values($eventMonthlyData->toArray())) !!},
        borderColor: '#14532d',
        backgroundColor: '#14532d',
        pointBackgroundColor: '#14532d',
        fill: false,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1 }
        }
      }
    }
  });

  document.querySelectorAll('.event-month-option').forEach(item => {
    item.addEventListener('click', e => {
      e.preventDefault();
      const month = item.dataset.value;
      fetch(`/negosyo/events/filter?month=${month}`)
        .then(res => res.json())
        .then(data => {
          document.getElementById('event-count').innerHTML = `<h2>${data.count.toLocaleString()}</h2><i class="bi bi-calendar-event"></i>`;
        });
    });
  });

  document.querySelectorAll('.attendee-title-option').forEach(item => {
    item.addEventListener('click', e => {
      e.preventDefault();
      const title = item.dataset.title;
      fetch(`/negosyo/attendees/filter?title=${encodeURIComponent(title)}`)
        .then(res => res.json())
        .then(data => {
          document.getElementById('attendee-count').innerHTML = `<h2>${data.count.toLocaleString()}</h2><i class="bi bi-people"></i>`;
        });
    });
  });

  document.querySelectorAll('.year-filter-option').forEach(item => {
    item.addEventListener('click', e => {
      e.preventDefault();
      document.getElementById('filterYearInput').value = item.dataset.year;
      document.getElementById('lineChartFilterForm').submit();
    });
  });
</script>
@endsection
