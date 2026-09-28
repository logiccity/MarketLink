<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ConfigController as AdminConfigController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FarmerController as AdminFarmerController;
use App\Http\Controllers\Admin\MarketController as AdminMarketController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductModerationController as AdminProductModerationController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ReviewModerationController as AdminReviewModerationController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\FavoriteController as CustomerFavoriteController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\ReviewController as CustomerReviewController;
use App\Http\Controllers\Customer\SearchController as CustomerSearchController;
use App\Http\Controllers\Farmer\DashboardController as FarmerDashboardController;
use App\Http\Controllers\Farmer\MarketController as FarmerMarketController;
use App\Http\Controllers\Farmer\OrderController as FarmerOrderController;
use App\Http\Controllers\Farmer\PickupSlotController as FarmerPickupSlotController;
use App\Http\Controllers\Farmer\ProductController as FarmerProductController;
use App\Http\Controllers\Farmer\ProfileController as FarmerProfileController;
use App\Http\Controllers\Farmer\ReviewController as FarmerReviewController;
use App\Http\Controllers\Farmer\SalesController as FarmerSalesController;
use App\Http\Controllers\Farmer\WeeklyStockController as FarmerWeeklyStockController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\ProductController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/faq', fn() => view('faq'))->name('faq');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Markets
Route::get('/markets', [MarketController::class, 'index'])->name('markets.index');
Route::get('/markets/{market}', [MarketController::class, 'show'])->name('markets.show');

// Farmers
Route::get('/farmers', [FarmerController::class, 'index'])->name('farmers.index');
Route::get('/farmers/{farmer}', [FarmerController::class, 'show'])->name('farmers.show');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// AI Assistant
Route::match(['post', 'get'], '/ai-assistant/ask', [AiAssistantController::class, 'ask'])->name('ai.assistant.ask');
Route::match(['post', 'get'], '/ai/ask', [AiAssistantController::class, 'ask']);

// Favorites
Route::post('/favorites/toggle', [CustomerFavoriteController::class, 'toggle'])->name('favorites.toggle')->middleware('auth');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showChooseRole'])->name('register');
    Route::get('/register/customer', [AuthController::class, 'showCustomerRegister'])->name('register.customer');
    Route::post('/register/customer', [AuthController::class, 'registerCustomer'])->name('register.customer.submit');
    Route::get('/register/farmer', [AuthController::class, 'showFarmerRegister'])->name('register.farmer');
    Route::post('/register/farmer', [AuthController::class, 'registerFarmer'])->name('register.farmer.submit');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Checkout
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
});

// Customer portal
Route::prefix('customer')->name('customer.')->middleware(['auth', 'role:customer,admin'])->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Orders & reviews
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::match(['post', 'patch'], '/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
    Route::patch('/orders/{order}/modify', [CustomerOrderController::class, 'modify'])->name('orders.modify');
    Route::post('/orders/{order}/reorder', [CustomerOrderController::class, 'reorder'])->name('orders.reorder');
    Route::get('/orders/{order}/review', fn(Order $order) => redirect()->route('customer.orders.show', $order->id))->name('orders.review');
    Route::post('/orders/{order}/reviews', [CustomerReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews', [CustomerReviewController::class, 'index'])->name('reviews.index');

    // Favorites
    Route::get('/favorites', [CustomerFavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle', [CustomerFavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/favorites/product/{product}', [CustomerFavoriteController::class, 'toggleProduct'])->name('favorites.product.toggle');
    Route::post('/favorites/farmer/{farmer}', [CustomerFavoriteController::class, 'toggleFarmer'])->name('favorites.farmer.toggle');
    Route::post('/favorites/market/{market}', [CustomerFavoriteController::class, 'toggleMarket'])->name('favorites.market.toggle');

    // Profile & household pickup
    Route::get('/profile', [CustomerProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [CustomerProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/family-members', [CustomerProfileController::class, 'addFamilyMember'])->name('profile.family.add');
    Route::delete('/profile/family-members/{index}', [CustomerProfileController::class, 'removeFamilyMember'])->name('profile.family.remove');

    // Notifications
    Route::get('/notifications', [CustomerNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [CustomerNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [CustomerNotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    Route::get('/search', [CustomerSearchController::class, 'index'])->name('search');
    Route::get('/ai-assistant', fn() => view('customer.ai-assistant'))->name('ai-assistant');
});

// Farmer portal
Route::prefix('farmer')->name('farmer.')->middleware(['auth', 'role:farmer,admin'])->group(function () {
    Route::get('/dashboard', [FarmerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [FarmerProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [FarmerProfileController::class, 'update'])->name('profile.update');

    Route::middleware(['approved_farmer'])->group(function () {
        // Markets
        Route::get('/markets', [FarmerMarketController::class, 'index'])->name('markets.index');
        Route::post('/markets/attach', [FarmerMarketController::class, 'attach'])->name('markets.attach');
        Route::delete('/markets/{market}/detach', [FarmerMarketController::class, 'detach'])->name('markets.detach');

        // Products
        Route::patch('/products/{product}/toggle-status', [FarmerProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::resource('products', FarmerProductController::class);

        // Weekly stock management
        Route::get('/weekly-stock', [FarmerWeeklyStockController::class, 'index'])->name('weekly-stock.index');
        Route::post('/weekly-stock/template', [FarmerWeeklyStockController::class, 'updateTemplate'])->name('weekly-stock.template.update');
        Route::post('/weekly-stock/apply-template', [FarmerWeeklyStockController::class, 'applyTemplateToWeek'])->name('weekly-stock.template.apply');
        Route::patch('/weekly-stock/items/{item}', [FarmerWeeklyStockController::class, 'updateItem'])->name('weekly-stock.item.update');

        // Pickup slots
        Route::get('/pickup-slots', [FarmerPickupSlotController::class, 'index'])->name('pickup-slots.index');
        Route::post('/pickup-slots', [FarmerPickupSlotController::class, 'store'])->name('pickup-slots.store');
        Route::patch('/pickup-slots/{slot}/toggle', [FarmerPickupSlotController::class, 'toggleActive'])->name('pickup-slots.toggle');
        Route::delete('/pickup-slots/{slot}', [FarmerPickupSlotController::class, 'destroy'])->name('pickup-slots.destroy');

        // Orders
        Route::get('/orders', [FarmerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [FarmerOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [FarmerOrderController::class, 'updateStatus'])->name('orders.status');
        Route::match(['post', 'patch'], '/orders/{order}/confirm', [FarmerOrderController::class, 'confirm'])->name('orders.confirm');

        // Analytics & reviews
        Route::get('/sales', [FarmerSalesController::class, 'index'])->name('sales.index');
        Route::get('/reviews', [FarmerReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/respond', [FarmerReviewController::class, 'respond'])->name('reviews.respond');
    });
});

// Admin portal
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [AdminDashboardController::class, 'search'])->name('search');
    Route::get('/notifications', [AdminDashboardController::class, 'notifications'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [AdminDashboardController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [AdminDashboardController::class, 'markAllNotificationsRead'])->name('notifications.readAll');

    // Farmers
    Route::get('/farmers', [AdminFarmerController::class, 'index'])->name('farmers.index');
    Route::get('/farmers/{farmer}', [AdminFarmerController::class, 'show'])->name('farmers.show');
    Route::patch('/farmers/{farmer}/status', [AdminFarmerController::class, 'updateStatus'])->name('farmers.status');
    Route::match(['post', 'patch'], '/farmers/{farmer}/approve', [AdminFarmerController::class, 'approve'])->name('farmers.approve');
    Route::match(['post', 'patch'], '/farmers/{farmer}/reject', [AdminFarmerController::class, 'reject'])->name('farmers.reject');
    Route::match(['post', 'patch'], '/farmers/{farmer}/suspend', [AdminFarmerController::class, 'suspend'])->name('farmers.suspend');
    Route::match(['post', 'delete'], '/farmers/{farmer}', [AdminFarmerController::class, 'destroy'])->name('farmers.destroy');

    // Customers
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
    Route::patch('/customers/{customer}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggleStatus');

    // Resources
    Route::resource('markets', AdminMarketController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('announcements', AdminAnnouncementController::class);

    // Products & reviews oversight
    Route::get('/products', [AdminProductModerationController::class, 'index'])->name('products.index');
    Route::patch('/products/{product}/status', [AdminProductModerationController::class, 'updateStatus'])->name('products.status');
    Route::delete('/products/{product}', [AdminProductModerationController::class, 'destroy'])->name('products.destroy');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');

    Route::get('/reviews', [AdminReviewModerationController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/status', [AdminReviewModerationController::class, 'updateStatus'])->name('reviews.status');
    Route::delete('/reviews/{review}', [AdminReviewModerationController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');

    // Inquiries
    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::patch('/contacts/{contactMessage}/status', [AdminContactController::class, 'updateStatus'])->name('contacts.status');
    Route::delete('/contacts/{contactMessage}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');

    // Settings
    Route::get('/config', [AdminConfigController::class, 'index'])->name('config.index');
    Route::post('/config', [AdminConfigController::class, 'update'])->name('config.update');
    Route::post('/config/clear-cache', [AdminConfigController::class, 'clearCache'])->name('config.clear-cache');
});
