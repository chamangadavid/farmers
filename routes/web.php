<?php

use App\Http\Controllers\Api\QrCodeController;
use App\Http\Controllers\Api\QrTypeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserSearchController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})
->middleware(['auth', 'verified'])
->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::get('/test-imagick', function() {
    phpinfo();
});


/*
|--------------------------------------------------------------------------
| Site Pages
|--------------------------------------------------------------------------
*/

Route::get('/about-us', function () {
    return Inertia::render('Site/aboutUs');
})->name('aboutUs');

Route::get('/job-vacancies', function () {
    return Inertia::render('Site/JobVacancies');
})->name('jobVacancies');

Route::get('/report-accident', function () {
    return Inertia::render('Site/ReportAccident');
})->name('reportAccident');

Route::get('/faq', function () {
    return Inertia::render('Site/FAQ');
})->name('faq');

Route::get('/contact-us', function () {
    return Inertia::render('Site/Contact');
})->name('contactDetails');

Route::get('/management-team', function () {
    return Inertia::render('Site/ManagementTeam');
})->name('managementTeam');

Route::get('/mandates', function () {
    return Inertia::render('Site/Mandate');
})->name('mandate');

Route::get('/history-details', function () {
    return Inertia::render('Site/History');
})->name('history');

Route::get('/icao-annex', function () {
    return Inertia::render('Site/ICAOAnnex');
})->name('icaoAnnex');

Route::get('/national-regulation', function () {
    return Inertia::render('Site/NationalRegulation');
})->name('nationalRegulation');

Route::get('/investigation-process', function () {
    return Inertia::render('Site/InvestigationProcess');
})->name('investigationPage');

Route::get('/accident-reports', function () {
    return Inertia::render('Site/AccidentReports');
})->name('accidentPage');

Route::get('/news', function () {
    return Inertia::render('Site/News');
})->name('newsPage');

Route::get('/press-releases', function () {
    return Inertia::render('Site/PressReleases');
})->name('pressReleasesPage');

Route::get('/announcements', function () {
    return Inertia::render('Site/Announcements');
})->name('announcementsPage');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/roles-permissions', fn () => Inertia::render('RolesPermissions'))->name('roles.permissions');
    Route::get('/roles', [RolePermissionController::class, 'roles']);
    Route::post('/roles', [RolePermissionController::class, 'storeRole']);
    Route::put('/roles/{id}', [RolePermissionController::class, 'update']);
    Route::delete('/roles/{id}', [RolePermissionController::class, 'destroy']);
    Route::post('/roles/bulk-delete', [RolePermissionController::class, 'bulkDestroy']);
    Route::get('/permissions', [RolePermissionController::class, 'permissions']);
    Route::post('/permissions', [RolePermissionController::class, 'storePermission']);
    Route::get('/rolesAndPermission', [RolePermissionController::class, 'rolesAndPermission'])->name('admin.rolesAndPermission');
    Route::get('/rolesAndPermission', [RolePermissionController::class, 'rolesAndPermission'])->name('admin.rolesAndPermission');
    
    //Cards & Assign
    Route::get('/eBusinessCards', [RolePermissionController::class, 'eBusinessCards'])->name('admin.eBusinessCards');
    Route::get('/users', [RolePermissionController::class, 'users']);
    Route::post('/users/assign-role', [RolePermissionController::class, 'assignRole']);


     // QR Types
    Route::get('/qr-types', [QrTypeController::class, 'index']);
    Route::get('/qr-types/{slug}', [QrTypeController::class, 'show']);
    
    // QR Code Generation
    Route::post('/qr/generate', [QrCodeController::class, 'generate']);
    Route::get('/qr/history', [QrCodeController::class, 'history']);
    Route::delete('/qr/{id}', [QrCodeController::class, 'destroy']);
    Route::get('/qr/{id}', [QrCodeController::class, 'show']);
    Route::get('/qr/{id}/download', [QrCodeController::class, 'download']);
    Route::delete('/qr/{id}', [QrCodeController::class, 'destroy']);
    Route::get('/search-users', [UserSearchController::class, 'search'])->name('users.search');
    Route::get('/users/{user}', [UserSearchController::class, 'show'])->name('users.show');


});







require __DIR__ . '/auth.php';
