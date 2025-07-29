<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ImportFileController;
use App\Http\Controllers\Business\BusinessController;
use App\Http\Controllers\Consumer\HomeController;
use App\Http\Controllers\Consumer\NotificationController;
use App\Http\Controllers\Consumer\MarketplaceController;
use App\Http\Controllers\Consumer\ConsumerController;
use App\Http\Controllers\Consumer\PostController;
use App\Http\Controllers\Consumer\ProfileController;
use App\Http\Controllers\negosyo\EventController;
use App\Http\Controllers\Business\BusinessHomeController;
use App\Http\Controllers\Business\BusinessNotificationController;
use App\Http\Controllers\Business\BusinessMarketplaceController;
use App\Http\Controllers\Admin\BusinessOwnerAccountController;
use App\Http\Controllers\Business\BusinessProfileController;
use App\Http\Controllers\Business\BusinessPostController;
use App\Http\Controllers\Admin\AdminManagePostController;
use App\Http\Controllers\negosyo\AnalyticsController;
use App\Http\Controllers\Business\PostingController;
use App\Models\Post;

Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login-submit', [LoginController::class, 'loginSubmit'])->name('login.submit');
Route::post('/register-submit', [LoginController::class, 'registerSubmit'])->name('register.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('auth/{provider}', [SocialAuthController::class, 'redirect']);
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback']);

 
// ✅ Admin Routes
Route::prefix('admin')->middleware(['auth.session'])->name('admin.')->group(function () {

    // Manage Accounts
    Route::get('/account', [AccountController::class, 'showImportPage'])->name('account');
    Route::get('/accountlist', [AccountController::class, 'showAccountList'])->name('accountlist');
    Route::post('/import', [AccountController::class, 'importAccounts'])->name('import.account');
    Route::get('/view-imported-file/{filename}', [AccountController::class, 'viewImportedFile'])->name('viewimportedfile');
    Route::post('/delete-selected', [AccountController::class, 'deleteSelected'])->name('deleteSelected');

    // Business Accounts
    Route::get('/businesslist', [AccountController::class, 'showBusinessList'])->name('businesslist');
    Route::get('/business-accounts', [BusinessOwnerAccountController::class, 'index'])->name('businessaccounts');
    Route::post('/business-accounts/{signup_id}/reset-password', [BusinessOwnerAccountController::class, 'sendPasswordReset'])->name('businessaccounts.resetpassword');

    // System Configuration
    Route::view('/systemconfig', 'admin.systemconfig')->name('systemconfig');

    
    // Manage Posts
    Route::get('/manage-posts', [AdminManagePostController::class, 'index'])->name('posts.manage');
    Route::post('/manage-posts/{id}/approve', [AdminManagePostController::class, 'approve'])->name('posts.approve');
    Route::post('/manage-posts/{id}/reject', [AdminManagePostController::class, 'reject'])->name('posts.reject');
    Route::get('/manage-posts/{id}/view', [AdminManagePostController::class, 'view'])->name('posts.view');

});


// Negosyo
Route::prefix('negosyo')->name('negosyo.')->group(function () {
    Route::get('/dashboard', [AnalyticsController::class, 'index'])->name('dashboard');
    Route::view('/events', 'negosyo.events')->name('events.index');
    Route::view('/certificate', 'negosyo.certificate')->name('certificate');
    Route::get('/business', [App\Http\Controllers\Negosyo\BusinessController::class, 'index'])->name('business');
});

Route::get('/negosyo/events/filter', [AnalyticsController::class, 'filterEvents']);
Route::get('/negosyo/attendees/filter', [AnalyticsController::class, 'filterAttendees']);

// consumer
Route::get('/home', [HomeController::class, 'index'])->name('consumer.home');
Route::get('/notification', [NotificationController::class, 'index'])->name('consumer.notification');
Route::get('/business', [MarketplaceController::class, 'index'])->name('consumer.marketplace');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/consumer/home', [HomeController::class, 'index'])->name('consumer.home');
// Route::get('/consumer/profile', [ProfileController::class, 'show'])->name('consumer.profile'); 
Route::get('/consumer/profile', [ProfileController::class, 'show'])->name('consumer.profile.own');
Route::get('/consumer/profile/{id}', [ProfileController::class, 'showProfile'])->name('consumer.profile.view');

Route::get('/consumer/post-images/{id}', function ($id) {
    $post = \App\Models\Post::find($id);

    if (!$post || !$post->media_paths) {
        return response()->json(['media' => []]);
    }

    $rawPaths = json_decode($post->media_paths, true);
    $mediaUrls = [];

    foreach ($rawPaths as $media) {
        $media = str_replace('\\', '/', $media); // ✅ fix path slashes
        if (file_exists(public_path('storage/' . $media))) {
            $mediaUrls[] = asset('storage/' . $media);
        }
    }

    return response()->json(['media' => $mediaUrls]);
});

Route::prefix('negosyo')->name('negosyo.')->group(function () {
    // List all events
    Route::get('/events', [EventController::class, 'index'])->name('events.index');

    // Show the create event form
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');

    // Store the new event
    Route::post('/events', [EventController::class, 'store'])->name('events.store');

    // Show a specific event
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

    // Delete an event
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::resource('negosyo/events', App\Http\Controllers\negosyo\EventController::class)->names('negosyo.events');
});




// Business User Routes
Route::prefix('business')->middleware(['auth.session'])->name('business.')->group(function () {

    // Dashboard, Notifications, Marketplace
    Route::get('/home', [BusinessHomeController::class, 'index'])->name('home');
    Route::get('/notification', [BusinessNotificationController::class, 'index'])->name('notification');
    Route::get('/marketplace', [BusinessMarketplaceController::class, 'index'])->name('marketplace');

    // Profile Routes
    Route::get('/profile', [BusinessProfileController::class, 'show'])->name('profile');
    Route::get('/profile/{signup_id}', [BusinessProfileController::class, 'showProfile'])->name('profile.view');

    // Post Creation
    Route::post('/posts/store', [BusinessPostController::class, 'store'])->name('posts.store');

});

/// 📌 Main marketplace form (only shown if no post exists)
Route::get('/business/marketplace', [BusinessMarketplaceController::class, 'index'])->name('business.marketplace');

// 📩 Store the business post (only once)
Route::post('/business/marketplace/store', [BusinessMarketplaceController::class, 'store'])->name('business.marketplace.store');

// 📄 View the posted business (used after posting or when revisiting tab)
Route::get('/business/view-post/{id}', [BusinessMarketplaceController::class, 'viewPost'])->name('business.viewpost');

// ✏️ Edit the posted business
Route::get('/business/edit-post/{id}', [BusinessMarketplaceController::class, 'edit'])->name('business.editpost');

Route::post('/business/{id}/postservice', [PostingController::class, 'postService'])->name('business.postservice');
Route::post('/business/{id}/postproduct', [PostingController::class, 'postProduct'])->name('business.postproduct');
Route::post('/business/{id}/postmenu', [PostingController::class, 'postMenu'])->name('business.postmenu');