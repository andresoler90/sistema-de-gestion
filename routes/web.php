<?php

use App\Http\Controllers\Admin\Cruds\CrudSettingsController;
use App\Http\Controllers\Libs\MiProveedor;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use \App\Http\Controllers\Reports\ReportRequestsController;
use \App\Http\Controllers\Analyst\AnalystTaskController;

use App\Http\Controllers\Admin\Cruds\CrudUserController;
use App\Http\Controllers\Admin\Cruds\CrudClientController;
use App\Http\Controllers\Admin\Cruds\CrudClientDocumentController;
use App\Http\Controllers\Admin\Cruds\CrudDocumentController;
use App\Http\Controllers\Admin\Cruds\CrudConfigurableWordController;
use App\Http\Controllers\Admin\Cruds\CrudPriceListController;
use App\Http\Controllers\Admin\Cruds\CrudTaskController;
use App\Http\Controllers\Client\ClientCountryController;
use App\Http\Controllers\Client\ConfigurableWordClientController;
use App\Http\Controllers\Client\RegisterController;
use App\Http\Controllers\Client\ScaledRegisterController;
use App\Http\Controllers\Client\RegisterSalesForceController;
use App\Http\Controllers\Client\ConfigurationAlertController;

use App\Http\Controllers\Coordinator\ManagementScaledController;
use App\Http\Controllers\Coordinator\PriorityTaskController;
use App\Http\Controllers\Coordinator\TrackingTaskController;
use App\Http\Controllers\Coordinator\DocumentManagementTasksController;
use App\Http\Controllers\Coordinator\SuspendedController;
use App\Http\Controllers\Reports\ReportANSController;
use App\Http\Controllers\Reports\ReportInternalManagementController;
use App\Http\Controllers\Reports\ReportManagementController;
use App\Http\Controllers\Reports\ReportQualityController;
use App\Http\Controllers\Reports\ReportDashboardController;

use \App\Http\Controllers\Tasks\DocumentManagementController;
use \App\Http\Controllers\Tasks\VerificationController;
use \App\Http\Controllers\Tasks\RetrievalController;
use \App\Http\Controllers\Tasks\QualityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['register' => false]);

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {

    Route::middleware('role:administrador')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::prefix('cruds')->group(function () {

                Route::resources(['users' => CrudUserController::class]);
                Route::get('users/create/{clients_id?}', [CrudUserController::class, 'create'])->name('users.create');
                Route::get('users/filter/search', [CrudUserController::class, 'search'])->name('users.search'); //[YA]

                Route::post('users/add/permissions', [CrudUserController::class, 'addPermissions'])->name('users.add.permission');
                Route::delete('users/destroy/permissions/{permission}', [CrudUserController::class, 'destroyPermissions'])->name('users.destroy.permission');

                Route::resource('clients', CrudClientController::class)->except('show');

                Route::post('clients/task/save', [CrudClientController::class, 'taskSave'])->name('clients.task.save');
                Route::get('clients/search', [CrudClientController::class, 'search'])->name('clients.search');

                Route::resources(['tasks' => CrudTaskController::class]);

                Route::resource('documents', CrudDocumentController::class)->except('show');

                Route::prefix('configurable')->name('configurable.')->group(function (){
                    Route::resource('words', CrudConfigurableWordController::class)->except('show');
                });
                Route::prefix('configurable/words')->name('configurable.words.')->group(function (){
                    Route::resource('clients', ConfigurableWordClientController::class)->except(['show','index','create']);
                    Route::get('clients/create/{clients_id}', [ConfigurableWordClientController::class, 'create'])->name('clients.create');
                });
                Route::prefix('client/countries')->name('clients.countries.')->group(function (){
                    Route::get('create/{clients_id}', [ClientCountryController::class, 'create'])->name('create');
                    Route::post('store', [ClientCountryController::class, 'store'])->name('store');
                    Route::delete('destroy/{client_country}',[ClientCountryController::class,'destroy'])->name('destroy');
                });
                Route::name('client.')->group(function () {
                    Route::resource('client/document', CrudClientDocumentController::class)->except(['index','create','show']);
                    Route::get('client/document/create/{clients_id}', [CrudClientDocumentController::class, 'create'])->name('document.create');
                });
                Route::prefix('settings')->name('settings.')->group(function (){
                    Route::get('/',[CrudSettingsController::class,'index'])->name('index');
                    Route::post('field',[CrudSettingsController::class,'fieldSave'])->name('field.save');
                    Route::put('field/update/{field}',[CrudSettingsController::class,'fieldUpdate'])->name('field.update');
                    Route::post('field/client',[CrudSettingsController::class,'fieldClientSave'])->name('field_client.save');
                    Route::post('field/client/update',[CrudSettingsController::class,'fieldClientUpdate'])->name('field_client.update');
                });

                Route::prefix('price/list')->name('price.list.')->group(function (){
                    Route::get('/',[CrudPriceListController::class,'index'])->name('index');
                    Route::get('search',[CrudPriceListController::class,'search'])->name('search');
                    Route::delete('destroy/{pricelist}',[CrudPriceListController::class,'destroy'])->name('destroy');
                    Route::get('create', [CrudPriceListController::class, 'create'])->name('create');
                    Route::post('store', [CrudPriceListController::class, 'store'])->name('store');
                    Route::get('edit/{pricelist}',[CrudPriceListController::class,'edit'])->name('edit');
                    Route::put('update/{pricelist}',[CrudPriceListController::class,'update'])->name('update');
                    Route::get('salesforce', [CrudPriceListController::class, 'getPriceListSalesForce'])->name('salesforce');
                });
            });
        });
    });

    Route::middleware('role:administrador|analista|coordinador|gerente')->group(function () {
        Route::prefix('commercial')->name('commercial.')->group(function (){
            Route::get('',[RegisterSalesForceController::class,'index'])->name('register.index');
            Route::get('/view/contact/{register}/{contact}',[RegisterSalesForceController::class,'show_contact'])->name('contact.show');
            Route::get('/register/{register}',[RegisterSalesForceController::class,'register_show'])->name('register.show');
            Route::get('/view/activity/{register}/{activity}',[RegisterSalesForceController::class,'show_activity'])->name('activity.show');
        });
        Route::prefix('commercial/management')->group(function () {
            Route::get('/execute/{salesforce}', [RegisterSalesForceController::class, 'execute_salesforce_opportunity'])->name('execute.salesforce.opportunity');
        });
    });


    Route::impersonate();
    Route::post('analyst/task/save', [CrudUserController::class, 'taskSave'])->name('analyst.task.save');
    Route::prefix('client/config/alerts')->middleware('role:administrador')->group(function () {
        Route::get('/', [ConfigurationAlertController::class, 'index'])->name('client.config.alert');
        Route::post('/store', [ConfigurationAlertController::class, 'store'])->name('client.config.alert.store');
    });
    Route::middleware('role:analista')->group(function () {
        Route::prefix('analyst')->group(function () {
            Route::prefix('task')->group(function () {

                Route::get('/', [AnalystTaskController::class, 'index'])->name('analyst.tasks.index');
                Route::get('/search', [AnalystTaskController::class, 'search'])->name('analyst.tasks.search'); //[YA]


                // Tareas Gestión documental
                Route::prefix('documents/management')->group(function () {
                    Route::get('/detail/{register_task}/{continue?}', [DocumentManagementController::class, 'detail'])->name('task.document.detail');
                    Route::post('/store', [DocumentManagementController::class, 'store'])->name('task.document.store');
                    Route::get('/execute/{id}', [DocumentManagementController::class, 'executeProviderTask'])->name('execute.provider.task');
                });

                // Tareas Verificación
                Route::prefix('verification')->group(function () {
                    Route::get('/basic/detail/{register_task}', [VerificationController::class, 'basicDetail'])->name('task.basic.verification.detail');
                    Route::get('/experience/detail/{register_task}', [VerificationController::class, 'experienceDetail'])->name('task.experience.verification.detail');
                    Route::get('/financial/detail/{register_task}', [VerificationController::class, 'financialDetail'])->name('task.financial.verification.detail');
                    Route::get('/document/detail/{register_task}', [VerificationController::class, 'documentDetail'])->name('task.document.verification.detail');
                    Route::post('/store', [VerificationController::class, 'store'])->name('task.verification.store');
                });

                // Tareas Subsanación
                Route::prefix('retrieval')->group(function () {
                    Route::get('/basic/detail/{register_task}/{continue?}', [RetrievalController::class, 'basicDetail'])->name('task.basic.retrieval.detail');
                    Route::get('/experience/detail/{register_task}/{continue?}', [RetrievalController::class, 'experienceDetail'])->name('task.experience.retrieval.detail');
                    Route::get('/financial/detail/{register_task}/{continue?}', [RetrievalController::class, 'financialDetail'])->name('task.financial.retrieval.detail');
                    Route::get('/document/detail/{register_task}/{continue?}', [RetrievalController::class, 'documentDetail'])->name('task.document.retrieval.detail');
                    Route::post('/store', [RetrievalController::class, 'store'])->name('task.retrieval.store');

                    Route::get('/execute/{id}', [RetrievalController::class, 'executeProviderRetrievalTask'])->name('execute.provider.retrieval.task');
                });

                // Tareas Calidad
                Route::prefix('quality')->group(function () {
                    Route::get('/detail/{register_task}', [QualityController::class, 'detail'])->name('task.quality.detail');
                    Route::post('/store', [QualityController::class, 'store'])->name('task.quality.store');

                    Route::prefix('retrieval')->group(function () {
                        Route::get('/basic/detail/{register_task}/{continue?}', [QualityController::class, 'retrievalBasic'])->name('task.quality.basic.retrieval.detail');
                        Route::get('/experience/detail/{register_task}/{continue?}', [QualityController::class, 'retrievalExperience'])->name('task.quality.experience.retrieval.detail');
                        Route::get('/financial/detail/{register_task}/{continue?}', [QualityController::class, 'retrievalFinancial'])->name('task.quality.financial.retrieval.detail');
                        Route::get('/document/detail/{register_task}/{continue?}', [QualityController::class, 'retrievalDocument'])->name('task.quality.document.retrieval.detail');
                        Route::post('/store', [QualityController::class, 'retrievalStore'])->name('task.quality.retrieval.store');
                    });

                });
            });
        });
        Route::get('get/users/{id}', [CrudClientController::class, 'getUsers'])->name('client.get.users');
    });

    Route::middleware('role:administrador|analista|cliente')->group(function () {
        Route::prefix('reports')->group(function () {

            // Estado de las solicitudes
            Route::prefix('requests')->name('reports.request.')->group(function () {
                Route::get('/', [ReportRequestsController::class, 'index'])->name('index');
                Route::get('search', [ReportRequestsController::class, 'search'])->name('search');
                Route::get('detail/{register}', [ReportRequestsController::class, 'detail'])->name('detail');
            });

            // Gestión
            Route::prefix('management')->name('reports.management.')->group(function () {
                Route::get('/', [ReportManagementController::class, 'index'])->name('index');
                Route::get('search', [ReportManagementController::class, 'search'])->name('search');
                Route::get('export', [ReportManagementController::class, 'export'])->name('export');
            });

            // ANS
            Route::prefix('ans')->name('reports.ans.')->group(function () {
                Route::get('/', [ReportANSController::class, 'index'])->name('index');
                Route::get('detail/{register}', [ReportANSController::class, 'detail'])->name('detail');
                Route::get('search', [ReportANSController::class, 'search'])->name('search');
                Route::get('export', [ReportANSController::class, 'export'])->name('export');
                Route::get('export/detail/{register}', [ReportANSController::class, 'exportDetail'])->name('export.detail');
            });

            // Gestión interna
            Route::prefix('internal/management')->name('reports.internal.management.')->group(function () {
                Route::get('/', [ReportInternalManagementController::class, 'index'])->name('index');
                Route::get('search', [ReportInternalManagementController::class, 'search'])->name('search');
                Route::get('analyst/{analyst}', [ReportInternalManagementController::class, 'analyst'])->name('analyst');
                Route::get('verification', [ReportInternalManagementController::class, 'verification'])->name('verification');
                Route::get('export', [ReportInternalManagementController::class, 'export'])->name('export');
            });

            // Calidad
            Route::prefix('quality')->name('reports.quality.')->group(function () {
                Route::get('/', [ReportQualityController::class, 'index'])->name('index');
                Route::get('search', [ReportQualityController::class, 'search'])->name('search');
                Route::get('export', [ReportQualityController::class, 'export'])->name('export');
            });

            // Dashboard
            Route::prefix('dashboard')->name('reports.dashboard.')->group(function () {
                Route::get('/', [ReportDashboardController::class, 'index'])->name('index');
                Route::get('search', [ReportDashboardController::class, 'search'])->name('search');
                Route::get('export', [ReportDashboardController::class, 'export'])->name('export');
            });
        });
    });

    Route::middleware('role:cliente|analista')->group(function () {
        //TODO : Crear midleware para la validación de permisos sobre las rutas
        Route::resource('registers', RegisterController::class)->except(['edit','update','destroy']);

        Route::post('get/provider', [RegisterController::class, 'getProvider'])->name('register.get.provider');
        Route::post('get/price/list', [RegisterController::class, 'getPriceList'])->name('register.get.pricelist');

        Route::prefix('activities')->group(function () {
            Route::get('type/{act1_id?}', [MiProveedor::class, 'getTypeActivity'])->name('activities.get.type');
            Route::get('category/{act1_id?}/{act10_id?}', [MiProveedor::class, 'getCategoryActivity'])->name('activities.get.category');
            Route::get('group/{act1_id?}/{act10_id?}/{act11_id?}', [MiProveedor::class, 'getGroupActivity'])->name('activities.get.group');
            Route::get('activity/{act1_id?}/{act10_id?}/{act11_id?}/{act3_id?}', [MiProveedor::class, 'getActivity'])->name('activities.get.activity');
            Route::get('master/{act1_id?}', [MiProveedor::class, 'getMasterActivity'])->name('activities.get.master');
        });
    });

    Route::middleware('role:cliente|analista|coordinador|administrador')->group(function () {
        Route::post('get/tracking', [DocumentManagementController::class, 'getTracking'])->name('task.document.get.tracking');
        Route::post('get/tracking/verification', [DocumentManagementController::class, 'getTrackingVerification'])->name('verification.get.tracking');
        Route::post('get/tracking/quality', [DocumentManagementController::class, 'getTrackingQuality'])->name('quality.get.tracking');
    });

    Route::middleware('role:cliente')->group(function () {
        Route::get('scaled/registers/index', [ScaledRegisterController::class, 'index'])->name('scaled.registers.index');
        Route::get('scaled/registers/document/management/show/{register}', [ScaledRegisterController::class, 'showDocumentManagement'])->name('scaled.registers.document.management.show');
        Route::get('scaled/registers/retrieval/show/{register}', [ScaledRegisterController::class, 'showRetrieval'])->name('scaled.registers.retrieval.show');
        Route::post('scaled/registers/store', [ScaledRegisterController::class, 'store'])->name('scaled.registers.store');
    });

    Route::middleware('role:coordinador')->group(function () {
        Route::get('management/scaled/index', [ManagementScaledController::class, 'index'])->name('management.scaled.index');
        Route::get('management/scaled/document/management/show/{register}', [ManagementScaledController::class, 'showDocumentManagement'])->name('management.scaled.document.management.show');
        Route::get('management/scaled/retrieval/show/{register}', [ManagementScaledController::class, 'showRetrieval'])->name('management.scaled.retrieval.show');
        Route::post('management/scaled/store', [ManagementScaledController::class, 'store'])->name('management.scaled.store');

        Route::get('registers/suspended/index', [SuspendedController::class, 'index'])->name('suspended.index');
        Route::get('registers/suspended/open/{registers_id}', [SuspendedController::class, 'open'])->name('suspended.open');

        Route::prefix('priority/tasks')->name('priority.tasks.')->group(function () {
            Route::get('index', [PriorityTaskController::class, 'index'])->name('index');
            Route::get('show/{task}', [PriorityTaskController::class, 'show'])->name('show');
            Route::post('store', [PriorityTaskController::class, 'store'])->name('store');
        });

        Route::get('document/management/tasks/index', [DocumentManagementTasksController::class, 'index'])->name('document.management.tasks.index');
        Route::get('document/management/tasks/search', [DocumentManagementTasksController::class, 'search'])->name('document.management.tasks.search'); //[YA]

        Route::get('tracking/tasks/index', [TrackingTaskController::class, 'index'])->name('tracking.tasks.index');
        Route::get('tracking/tasks/show/{register}', [TrackingTaskController::class, 'show'])->name('tracking.tasks.show');
        Route::post('tracking/tasks/store', [TrackingTaskController::class, 'store'])->name('tracking.tasks.store');
    });

    Route::get('locale/{locale}', function ($locale) {
        Session::put('locale', $locale);
        return redirect()->back();
    })->name('locale');

    Route::get('prueba', [HomeController::class, 'prueba']);

});
