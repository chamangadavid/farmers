<?php


use App\Http\Controllers\Audit\AuditLogController;
use App\Http\Controllers\Contacts\ContactController;
use App\Http\Controllers\Documents\DocumentController;
use App\Http\Controllers\Faqs\FaqController;
use App\Http\Controllers\Jobs\JobController;
use App\Http\Controllers\Management\TeamController;
use App\Http\Controllers\Media\AnnouncementsController;
use App\Http\Controllers\Media\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Report\ReportsController;
use App\Http\Controllers\Testimonials\TestimonyController;
use App\Http\Controllers\Gallaries\GalleryController;
use App\Http\Controllers\Vegetables\VegetableController;
use App\Http\Controllers\Vegetables\VegetableTypeController;
use App\Http\Controllers\Vegetables\VegetableProductionController;
use App\Http\Controllers\Vegetables\VegetableHarvestController;
use App\Http\Controllers\Vegetables\VegetableSaleController;
use App\Http\Controllers\Vegetables\VegetableExpenseController;
use App\Http\Controllers\Chickens\ChickenController;
use App\Http\Controllers\Chickens\ChickenBatchController;
use App\Http\Controllers\Chickens\ChickenSalesController;
use App\Http\Controllers\Chickens\ChickenReportsController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Staff\StaffsController;
use App\Http\Controllers\UserSearchController;
use App\Models\Accident\AccidentReport;
use App\Models\Contacts\Contact;
use App\Models\Jobs\Job;
use App\Models\Management\Team;
use App\Models\Regulation\NationalRegulations;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
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

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })
// ->middleware(['auth', 'verified'])
// ->name('dashboard');

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

Route::get('/product-gallery', function () {
    return Inertia::render('Site/Gallery');
})->name('productGalleries');

Route::get('/faq', function () {
    return Inertia::render('Site/FAQ');
})->name('faq');

Route::get('/contact-us', function () {
    return Inertia::render('Site/Contact');
})->name('contactDetails');

Route::get('/management-team', function () {
    return Inertia::render('Site/ManagementTeam');
})->name('managementTeam');

Route::get('/history-details', function () {
    return Inertia::render('Site/History');
})->name('history');

Route::get('/icao-annex', function () {
    return Inertia::render('Site/ICAOAnnex');
})->name('icaoAnnex');

Route::get('/document-repository', function () {
    return Inertia::render('Site/Documents');
})->name('documentRepository');

Route::get('/report-accidents', function () {
    return Inertia::render('Site/AccidentReports');
})->name('accidentPage');

Route::get('/all-news', function () {
    return Inertia::render('Site/News');
})->name('newsPage');

Route::get('/press-releases', function () {
    return Inertia::render('Site/PressReleases');
})->name('pressReleasesPage');

Route::get('/announcement', function () {
    return Inertia::render('Site/Announcements');
})->name('announcementsPage');

//details page for management team members
Route::get('/management-team/{id}', function ($id) {
    $member = Team::findOrFail($id);

    return Inertia::render('Site/ManagementTeamDetails', [
        'member' => $member
    ]);
});


//public
Route::post('/contact', [ContactController::class, 'store']);
Route::get('/faqs/all', [FaqController::class, 'getfrontIndex']); // For your FAQ page
Route::get('/public/announcements', [AnnouncementsController::class, 'publicAnnouncementsIndex']);
Route::get('/public/news', [NewsController::class, 'publicNewsIndex']);
Route::get('/all-news/{id}', [NewsController::class, 'show']);
Route::get('/public-documents/folders', [DocumentController::class, 'Publicindex']);

Route::get('/jobs/active', [JobController::class, 'getActiveJobs']);
Route::get('/public-members', [TeamController::class, 'publicMember']);
Route::get('/all-jobs-details/{job}', [JobController::class, 'details']);

Route::get('/frontend/faqs', [FaqController::class, 'getfrontIndex']); 
Route::get('/frontend/testimonials', [TestimonyController::class, 'frontendTestimonials']);
Route::get('/frontend/gallery', [GalleryController::class,'frontendGallery']);



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

    Route::get('/staff-roles', [StaffsController::class, 'staffRoles'])->name('staff.index');
    Route::post('/add-users', [StaffsController::class, 'store']);
    Route::get('/get-staff-roles', [RolePermissionController::class, 'staffRoles']);
    Route::get('/search-users', [UserSearchController::class, 'search'])->name('users.search');
    Route::get('/users/{user}', [UserSearchController::class, 'show'])->name('users.show');

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

    //announcements routes
    Route::get('/all-announcements', [AnnouncementsController::class, 'GetAnnouncements'])->name('announcement.index');
    Route::get('/announcements', [AnnouncementsController::class, 'index']);
    Route::post('/announcements', [AnnouncementsController::class, 'store']);
    Route::put('/announcements/{id}', [AnnouncementsController::class, 'update']);
    Route::delete('/announcements/{id}', [AnnouncementsController::class, 'destroy']);

    //News routes
    Route::get('/latest-news', [NewsController::class, 'GetNews'])->name('news.index');
    Route::get('/news', [NewsController::class, 'index']);
    Route::post('/news', [NewsController::class, 'store']);
    Route::put('/news/{id}', [NewsController::class, 'update']);
    Route::delete('/news/{id}', [NewsController::class, 'destroy']);

    //vegetable routes
    Route::get('/all-vegetables', [VegetableController::class, 'GetVegetableIndexPage'])->name('vegetables.index');

    Route::get('/vegetable-types',[VegetableTypeController::class,'index']);
    Route::post('/vegetable-types',[VegetableTypeController::class,'store']);
    Route::get('/statistics', [VegetableTypeController::class, 'statistics']);
    Route::get('/vegetable-types/{id}',[VegetableTypeController::class,'show']);
    Route::put('/vegetable-types/{id}',[VegetableTypeController::class,'update']);
    Route::delete('/vegetable-types/{id}',[VegetableTypeController::class,'destroy']);


    Route::get('/all-vegetables-production', [VegetableController::class, 'GetVegetableProductionPage'])->name('vegetables-production.index');
    Route::get('/vegetable-productions', [VegetableProductionController::class, 'index']);
    Route::post('/vegetable-productions', [VegetableProductionController::class, 'store']);
    Route::get('/vegetable-productions/statistics', [VegetableProductionController::class, 'statistics']);
    Route::get('/vegetable-productions/vegetable-types', [VegetableProductionController::class, 'vegetableTypes']);
    Route::get('/vegetable-productions/{id}', [VegetableProductionController::class, 'show']);
    Route::put('/vegetable-productions/{id}', [VegetableProductionController::class, 'update']);
    Route::delete('/vegetable-productions/{id}', [VegetableProductionController::class, 'destroy']);




    
Route::get('/all-harvest', [VegetableController::class, 'GetVegetableHarvestPage'])->name('vegetables-harvest.index');
Route::get('/vegetable-harvests',[VegetableHarvestController::class,'index']);
Route::get('/vegetable-harvests/statistics', [VegetableHarvestController::class, 'statistics']);
Route::get('/vegetable-harvests/productions', [VegetableHarvestController::class, 'productions']);
Route::get('/vegetable-harvests/{vegetableHarvest}', [VegetableHarvestController::class, 'show']);

Route::get('/vegetable-productions/{production}/remaining-yield', [VegetableHarvestController::class, 'remainingYield']);
Route::post('/vegetable-harvests',[VegetableHarvestController::class,'store']);
Route::put('/vegetable-harvests/{harvest}',[VegetableHarvestController::class,'update']);
Route::delete('/vegetable-harvests/{harvest}',[VegetableHarvestController::class,'destroy']);
Route::get('/vegetable-harvests/{harvest}/receipt',  [VegetableHarvestController::class, 'receipt'])->name('vegetable-harvest.receipt');
Route::get('/vegetable-harvests/export/pdf', [VegetableHarvestController::class, 'exportPdf'])->name('vegetable-harvest.export.pdf');
Route::get('/vegetable-harvests/{harvest}/pdf',  [VegetableHarvestController::class, 'downloadPdf' ])->name('vegetable-harvest.pdf');







Route::get('/all-vegetable-sales', [VegetableController::class, 'GetVegetableSalesPage'])->name('vegetables-sale.index');
Route::get('/vegetable-sales', [VegetableSaleController::class,'index']);
Route::get('/vegetable-sales/{sale}', [VegetableSaleController::class,'show']);
Route::get('/all-vegetable-sales/harvests', [VegetableSaleController::class, 'harvests']);
Route::post('/vegetable-sales', [VegetableSaleController::class,'store']);
Route::put('/vegetable-sales/{sale}', [VegetableSaleController::class,'update']);
Route::delete('/vegetable-sales/{sale}', [VegetableSaleController::class,'destroy']);
Route::get('/vegetable-sales/export/pdf', [VegetableSaleController::class,'exportPdf']);
Route::get('/vegetable-sales/export/excel', [VegetableSaleController::class,'exportExcel']);
Route::get('/vegetable-sales/{sale}/receipt', [VegetableSaleController::class,'receipt']);
Route::get('/download-vegetable-sales/{sale}/pdf', [VegetableSaleController::class,'downloadPdf'])->name('vegetable-sales.pdf');
Route::get('/vegetable-sales/report/pdf', [VegetableSaleController::class, 'exportPdf'])->name('vegetable-sales.report.pdf');













Route::get('/all-vegetable-expenses', [VegetableController::class, 'GetVegetableExpensesPage'])->name('vegetables-expenses.index');

 Route::get('/vegetable-expenses', [ VegetableExpenseController::class, 'index']);

    Route::post('/vegetable-expenses', [VegetableExpenseController::class, 'store']);

    Route::put('/vegetable-expenses/{expense}', [VegetableExpenseController::class, 'update']);

    Route::delete('/vegetable-expenses/{expense}', [VegetableExpenseController::class, 'destroy']);

    Route::get('/vegetable-expenses/pdf', [VegetableExpenseController::class, 'exportPdf']);

    Route::get('/vegetable-expenses/excel', [VegetableExpenseController::class, 'exportExcel']);

    Route::get('/vegetable-expenses/{expense}', [VegetableExpenseController::class, 'show']);

    Route::get('/vegetable-productions-all', [VegetableExpenseController::class, 'productions']);























    //chicken routes
    Route::get('/all-chickens', [ChickenController::class, 'GetChickensIndexPage'])->name('chicken.index');
    Route::get('/chickens', [ChickenBatchController::class, 'index']);
    Route::post('/chickens', [ChickenBatchController::class, 'store']);
    Route::put('/chickens/{chicken}', [ChickenBatchController::class,'update']);
    Route::get('/chicken-sales',[ChickenSalesController::class,'index']);
    Route::get('/chicken-sales/{batch}', [ChickenSalesController::class,'show']);
    Route::post('/chicken-sales',[ChickenSalesController::class,'store']);
    Route::put('/chicken-sales/{sale}',[ChickenSalesController::class,'update']);
    Route::put('/update-chicken-sales/{sale}', [ChickenSalesController::class,'updateSales']);
    Route::get('/chicken-sales/{sale}/receipt', [ChickenSalesController::class,'receipt']);
    Route::get('/chicken-sales/{sale}/receipt/download', [ChickenSalesController::class,'downloadReceipt']);
    Route::delete('/chicken-sales/{sale}', [ChickenSalesController::class,'destroy'])->name('chicken-sales.destroy');
    Route::get('/chicken-reports/monthly-summary', [ChickenReportsController::class,'monthlySummary']);
    Route::get('/chicken-reports/sales', [ChickenReportsController::class,'salesReport']);
    Route::get('/chicken-reports/expenses', [ChickenReportsController::class,'expenseReport']);
    Route::get('/chicken-reports/revenue', [ChickenReportsController::class,'revenueReport']);
    Route::get('/all-sales-reports', [ChickenReportsController::class, 'GetSalesReportIndexPage'])->name('sales-report.index');
    Route::get('/chicken-reports/dashboard', [ChickenReportsController::class,'dashboard']);
    Route::get('/summary-reports/pdf', [ChickenReportsController::class,'downloadPdf']);
    Route::get('/chicken-reports/excel', [ChickenReportsController::class,'downloadExcel']);
    Route::get('/chicken-reports/pdf', [ChickenReportsController::class,'pdf']);







       //sales routes
     Route::get('/all-sales', [SalesController::class, 'GetSalesIndexPage'])->name('sales.index');
     


    //document repository routes
    Route::get('/all-documents', [DocumentController::class, 'GetIDocuments'])->name('document.index');
    Route::get('/documents/folders', [DocumentController::class, 'index']);
    Route::post('/documents/folder', [DocumentController::class, 'storeFolder']);
    Route::post('/documents/upload', [DocumentController::class, 'upload']);
    Route::put('/documents/{id}/rename', [DocumentController::class, 'rename']);
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy']);

    //Job Vacancies routes
    Route::get('/all-jobs', [jobController::class, 'JobVacancies'])->name('job.index');
    Route::get('/jobs', [JobController::class, 'index']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::post('/job-apply', [JobController::class, 'apply']);
    Route::get('/jobs/{job}', [JobController::class, 'show']);
    Route::put('/jobs/{job}', [JobController::class, 'update']);
    Route::delete('/jobs/{job}', [JobController::class, 'destroy']);
    Route::get('/job-applications', [JobController::class, 'fetchApplications']);
    Route::get('/job-applications/{id}', [JobController::class, 'showApplication']);
    Route::delete('/job-applications/{id}', [JobController::class, 'destroyApplication']);
    Route::get('/job-applications/{id}/download/{fileType}', [JobController::class, 'downloadFile']);


    //testmonies
    Route::get('/all-testimonies', [TestimonyController::class, 'indexPage'])->name('testimonials.index');
    Route::get('/testimonials', [TestimonyController::class, 'index']);
    Route::post('/testimonials', [TestimonyController::class, 'store']);
    Route::put('/testimonials/{testimonial}', [TestimonyController::class, 'update']);
    Route::delete('/testimonials/{testimonial}', [TestimonyController::class, 'destroy']);
    Route::get('/testimonials/{testimonial}', [TestimonyController::class, 'show']);
   



    //gallery products
    // Route::get('/all-gallery-products', [ProductController::class, 'indexPage'])->name('products.index');
    

    Route::get('/all-gallery', [GalleryController::class, 'indexPage'])->name('galleries.index');


   // Route::get('/myfarmer/gallery', [GalleryController::class,'index'])->name('gallery.index');


// Fetch gallery list for axios
// Route::get(
//     '/gallery',
//     [GalleryController::class,'index']
// );


Route::get('/gallery', [GalleryController::class,'getGallery']);

// Store
Route::post('/gallery', [GalleryController::class,'store']);


// Update
Route::post('/gallery/{gallery}', [GalleryController::class,'update']);
Route::delete('/gallery/image/{image}', [GalleryController::class, 'destroyImage']);
// Delete
Route::delete('/gallery/{gallery}', [GalleryController::class,'destroy']);
// View single
Route::get('/gallery/{gallery}', [GalleryController::class,'show']);


// Homepage API

























    //Trail Audit
    Route::get('/trail-audit', [AuditLogController::class, 'getAudits'])->name('trail-audit.index');
    Route::get('/audit-logs', [AuditLogController::class, 'index']);
    Route::get('/audits/{id}', [AuditLogController::class, 'showPage']);
    











  


    


 
    


     //reports routes
    Route::get('/all-reports', [ReportsController::class, 'GetReports'])->name('reports.index');
    
    

     //national regulational routes
    Route::get('/all-nation-regulations', [NationalRegulations::class, 'GetNationalRegulations'])->name('regulations.index');
    

  




    













    
    //Cards & Assign
    Route::get('/eBusinessCards', [RolePermissionController::class, 'eBusinessCards'])->name('admin.eBusinessCards');
    Route::get('/users', [RolePermissionController::class, 'users']);
    Route::post('/users/assign-role', [RolePermissionController::class, 'assignRole']);

});







require __DIR__ . '/auth.php';
