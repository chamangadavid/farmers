<?php

use App\Http\Controllers\Accident\AccidentsController;
use App\Http\Controllers\Api\QrCodeController;
use App\Http\Controllers\Api\QrTypeController;
use App\Http\Controllers\Contacts\ContactController;
use App\Http\Controllers\Faqs\FaqController;
use App\Http\Controllers\Incident\IncidentsController;
use App\Http\Controllers\Investigation\InvestigationsController;
use App\Http\Controllers\Jobs\JobController;
use App\Http\Controllers\Management\TeamController;
use App\Http\Controllers\Media\AnnouncementsController;
use App\Http\Controllers\Media\NewsController;
use App\Http\Controllers\Media\PressController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Regulation\IcaoAnnexController;
use App\Http\Controllers\Report\ReportsController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserSearchController;
use App\Models\Management\Team;
use App\Models\Regulation\NationalRegulations;
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

//details page for management team members
Route::get('/management-team/{id}', function ($id) {
    $member = Team::findOrFail($id);

    return Inertia::render('Site/ManagementTeamDetails', [
        'member' => $member
    ]);
});

Route::post('/contact', [ContactController::class, 'store']);
Route::get('/faqs/all', [FaqController::class, 'getfrontIndex']); // For your FAQ page


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

    //Management routes
    Route::get('/managment-team', [TeamController::class, 'managementTeam'])->name('management.index');
    Route::get('/members', [TeamController::class, 'index']);
    Route::post('/members', [TeamController::class, 'store']);
    Route::put('/members/{id}', [TeamController::class, 'update']);
    Route::delete('/members/{id}', [TeamController::class, 'destroy']);

    //contact routes
    Route::get('/contact-messages', [ContactController::class, 'ContactUs'])->name('contact.index');
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);
    
    //FAQs routes
    Route::get('/faqs', [JobController::class, 'GetFaqs'])->name('faq.index');
    Route::get('/all-faqs', [FaqController::class, 'index']);        
    Route::post('/faqs', [FaqController::class, 'store']);      
    Route::get('/faqs/{faq}', [FaqController::class, 'show']);    
    Route::put('/faqs/{faq}', [FaqController::class, 'update']);  
    Route::delete('/faqs/{faq}', [FaqController::class, 'destroy']); 







    //Job Vacancies routes
    Route::get('/jobs', [jobController::class, 'JobVacancies'])->name('job.index');
    



  


    
    //accidents routes
    Route::get('/all-accidents', [AccidentsController::class, 'GetAccidents'])->name('accidents.index');
    


     //incident routes
    Route::get('/all-incidents', [IncidentsController::class, 'GetIncidents'])->name('incidents.index');
    
    
   //incident routes
    Route::get('/all-investigations', [InvestigationsController::class, 'GetInvestigations'])->name('investigations.index');
    

    //News routes
    Route::get('/latest-news', [NewsController::class, 'GetNews'])->name('news.index');
    

    //press releases routes
    Route::get('/all-press-releases', [PressController::class, 'GetPressReleases'])->name('press.index');
    

    //announcements routes
    Route::get('/all-announcements', [AnnouncementsController::class, 'GetAnnouncements'])->name('announcement.index');
    
    


     //reports routes
    Route::get('/all-reports', [ReportsController::class, 'GetReports'])->name('reports.index');
    
    

     //national regulational routes
    Route::get('/all-nation-regulations', [NationalRegulations::class, 'GetNationalRegulations'])->name('regulations.index');
    

    //icao annex routes
    Route::get('/all-icao-annex', [IcaoAnnexController::class, 'GetIcaoAnnex'])->name('icao.index');
    
















    
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
