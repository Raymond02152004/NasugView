@extends('.consumer.consumerlayout')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background-color: #ffffff;
        overflow-x: hidden;
    }

    #sidebar {
        width: 260px;
        height: 100vh;
        background-color: #084d1a;
        color: white;
        padding: 25px 20px;
        border-top-right-radius: 12px;
        position: fixed;
        top: 0;
        left: 0;
        overflow-y: auto;
    }

    .content-wrapper {
        margin-left: 260px;
        padding: 30px;
    }

    #sidebar h5 {
        font-size: 20px;
        font-weight: 600;
        color: #fff;
        margin-bottom: 20px;
    }

    #sidebar input {
        border-radius: 20px;
        font-size: 13px;
        padding: 6px 12px;
        border: none;
        width: 100%;
        margin-bottom: 20px;
    }

    #sidebar a {
        text-decoration: none;
        color: #fff;
        display: flex;
        align-items: center;
        padding: 8px 10px;
        gap: 12px;
        font-size: 14px;
        border-radius: 6px;
        transition: background-color 0.2s ease;
        margin-bottom: 6px;
    }

    #sidebar a:hover {
        background-color: #4b9c4e;
    }

    .create-btn {
        background-color: #e6f4ea;
        border: none;
        color: #084d1a;
        font-weight: 600;
        border-radius: 8px;
        padding: 10px;
        width: 100%;
        font-size: 14px;
        margin: 25px 0 15px 0;
    }

    .location-text {
        font-size: 12px;
        color: #cfd8cf;
        margin-top: 2px;
        margin-bottom: 15px;
    }

    .category-title {
        font-weight: 600;
        font-size: 13px;
        margin-top: 20px;
        color: #fff;
        margin-bottom: 8px;
    }

    .product-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
        width: 280px;
        transition: 0.3s;
        background: #fff;
    }

    .product-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .product-card-body {
        padding: 15px;
    }

    .product-title {
        font-weight: 600;
        font-size: 16px;
    }

    .product-price {
        color: green;
        font-weight: bold;
        margin-top: 5px;
    }

    .product-location {
        font-size: 13px;
        color: #666;
    }

    @media (max-width: 768px) {
        #sidebar {
            position: relative;
            width: 100%;
            height: auto;
            padding: 20px;
            border-radius: 0;
        }

        .content-wrapper {
            margin-left: 0;
            padding: 20px;
        }

        .product-card {
            width: 100%;
        }
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Sidebar -->
<div id="sidebar">
    <h5>Marketplace</h5>
    <input type="text" class="form-control" placeholder="Search Marketplace">

    <div>
        <a href="#"><i class="bi bi-shop"></i> Browse all</a>
        <a href="#"><i class="bi bi-bell"></i> Notifications</a>
        <a href="#"><i class="bi bi-envelope"></i> Inbox</a>
        <a href="#"><i class="bi bi-door-open"></i> Marketplace access</a>
        <a href="#"><i class="bi bi-cart"></i> Buying</a>
        <a href="#"><i class="bi bi-tag"></i> Selling</a>
    </div>

    <button class="create-btn">+ Create new listing</button>

    <div>
        <p class="category-title">Location</p>
        <p class="location-text">Nasugbu, Batangas · Within 65 km</p>

        <p class="category-title">Categories</p>
        <a href="#"><i class="bi bi-truck-front"></i> Vehicles</a>
        <a href="#"><i class="bi bi-house-door"></i> Property for rent</a>
        <a href="#"><i class="bi bi-journal"></i> Classifieds</a>
    </div>
</div>

<!-- Main Content -->
<div class="content-wrapper">
    <div class="card p-4">
        <div class="d-flex flex-wrap justify-content-start gap-4">
            <!-- iPhone 16 -->
            <div class="product-card">
                <img src="https://th.bing.com/th/id/OIP.oKvS7ro4WHZfkt1cfhyn5AHaE8?w=298&h=187&c=7&r=0&o=7&pid=1.7&rm=3" alt="iPhone 16">
                <div class="product-card-body">
                    <div class="product-title">iPhone 16</div>
                    <div class="product-price">PHP 10,000</div>
                    <div class="product-location">BRGY. 11, Nasugbu, Batangas</div>
                </div>
            </div>

            <!-- iPhone 15 -->
            <div class="product-card">
                <img src="https://ts3.mm.bing.net/th?id=OIP.O2kz5tTobhQmZ8vWKa14RQHaHa&pid=15.1" alt="iPhone 15">
                <div class="product-card-body">
                    <div class="product-title">iPhone 15</div>
                    <div class="product-price">PHP 5,000</div>
                    <div class="product-location">BRGY. 6, Nasugbu, Batangas</div>
                </div>
            </div>

            <!-- iPhone 14 -->
            <div class="product-card">
                <img src="https://th.bing.com/th/id/OIP.nbV7lXq8tjn6z65vO38PXgHaFj?w=228&h=180&c=7&r=0&o=7&pid=1.7&rm=3" alt="iPhone 14">
                <div class="product-card-body">
                    <div class="product-title">iPhone 14</div>
                    <div class="product-price">PHP 3,500</div>
                    <div class="product-location">BRGY. 1, Nasugbu, Batangas</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
