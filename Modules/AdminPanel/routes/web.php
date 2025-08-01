<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminPanel\App\Http\Controllers\HomeController;
use Modules\AdminPanel\App\Http\Controllers\AiapplicationController;
use Modules\AdminPanel\App\Http\Controllers\AllergyController;
use Modules\AdminPanel\App\Http\Controllers\AppointmentController;
use Modules\AdminPanel\App\Http\Controllers\AuthenticationController;
use Modules\AdminPanel\App\Http\Controllers\ChartController;
use Modules\AdminPanel\App\Http\Controllers\ChronicConditionController;
use Modules\AdminPanel\App\Http\Controllers\ComponentspageController;
use Modules\AdminPanel\App\Http\Controllers\DashboardController;
use Modules\AdminPanel\App\Http\Controllers\FormsController;
use Modules\AdminPanel\App\Http\Controllers\InvoiceController;
use Modules\AdminPanel\App\Http\Controllers\SettingsController;
use Modules\AdminPanel\App\Http\Controllers\TableController;
use Modules\AdminPanel\App\Http\Controllers\UsersController;
use Modules\AdminPanel\App\Http\Controllers\CryptocurrencyController;
use Modules\AdminPanel\App\Http\Controllers\DoctorController;
use Modules\AdminPanel\App\Http\Controllers\PaymentController;
use Modules\AdminPanel\App\Http\Controllers\SpecializationController;
use Modules\AdminPanel\App\Http\Controllers\CoverageAreaController;
use Modules\AdminPanel\App\Http\Controllers\NotificationController;
use Modules\AdminPanel\App\Http\Controllers\PatientController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('/dashboard')
        ->group(function () {

            Route::prefix('admin')->name('admin.')->group(function () {
                Route::controller(DashboardController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/profile', 'viewProfile')->name('profile');
                    Route::post('/profile', 'updateProfile')->name('profile.update');
                });

                Route::prefix('users')->name('users.')->controller(UsersController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::delete('/{user}', 'destroy')->name('destroy');
                });

                Route::prefix('doctors')->name('doctors.')->group(function () {
                    Route::controller(DoctorController::class)->group(function () {
                        Route::get('/approvals', 'doctorApprovals')->name('doctorApprovals');
                        Route::post('/{doctorProfile}/approve', 'approveDoctor')->name('approve');
                        Route::post('/{doctorProfile}/reject', 'rejectDoctor')->name('reject');
                    });
                    Route::resource('/', DoctorController::class)->parameters(['' => 'doctor']);
                });

                Route::prefix('payments')->name('payments.')->group(function () {
                    Route::get('/', [PaymentController::class, 'index'])->name('index');
                    Route::get('/pending', [PaymentController::class, 'pending'])->name('pending');
                    Route::post('/{payment}/approve', [PaymentController::class, 'approve'])->name('approve');
                    Route::post('/{payment}/reject', [PaymentController::class, 'reject'])->name('reject');
                });

                Route::prefix('appointments')->name('appointments.')->controller(AppointmentController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/pending', 'pending')->name('pending');
                    Route::get('/completed', 'completed')->name('completed');
                    Route::get('/{appointment}', 'show')->name('show');
                    Route::post('/{appointment}/status', 'updateStatus')->name('update-status');
                });

                Route::prefix('specializations')->name('specializations.')->controller(SpecializationController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::put('/{id}', 'update')->name('update');
                    Route::delete('/{id}', 'destroy')->name('destroy');
                });

                Route::prefix('coverage-areas')->name('coverageAreas.')->group(function () {
                    Route::get('/', [CoverageAreaController::class, 'index'])->name('index');
                    Route::post('/', [CoverageAreaController::class, 'store'])->name('store');
                    Route::put('/{id}', [CoverageAreaController::class, 'update'])->name('update');
                    Route::delete('/{id}', [CoverageAreaController::class, 'destroy'])->name('destroy');
                });

                Route::prefix('allergies')->name('allergies.')->group(function () {
                    Route::get('/', [AllergyController::class, 'index'])->name('index');
                    Route::post('/', [AllergyController::class, 'store'])->name('store');
                    Route::put('/{id}', [AllergyController::class, 'update'])->name('update');
                    Route::delete('/{id}', [AllergyController::class, 'destroy'])->name('destroy');
                });

                Route::prefix('chronic-condition')->name('chronicConditions.')->group(function () {
                    Route::get('/', [ChronicConditionController::class, 'index'])->name('index');
                    Route::post('/', [ChronicConditionController::class, 'store'])->name('store');
                    Route::put('/{id}', [ChronicConditionController::class, 'update'])->name('update');
                    Route::delete('/{id}', [ChronicConditionController::class, 'destroy'])->name('destroy');
                });

                Route::prefix('patients')->name('patients.')->controller(PatientController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{patient}', 'show')->name('show');
                    Route::delete('/{patient}', 'destroy')->name('destroy');
                });

                Route::prefix('notifications')->name('notifications.')->controller(NotificationController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/{id}/mark-as-read', 'markAsRead')->name('mark-as-read');
                    Route::post('/mark-all-as-read', 'markAllAsRead')->name('mark-all-as-read');
                });
            });
        });
});
Route::controller(HomeController::class)->group(function () {
    Route::get('calendar-Main', 'calendarMain')->name('calendarMain');
    Route::get('chatempty', 'chatempty')->name('chatempty');
    Route::get('chat-message', 'chatMessage')->name('chatMessage');
    Route::get('chat-profile', 'chatProfile')->name('chatProfile');
    Route::get('email', 'email')->name('email');
    Route::get('faq', 'faq')->name('faq');
    Route::get('gallery', 'gallery')->name('gallery');
    Route::get('image-upload', 'imageUpload')->name('HomeimageUpload');
    Route::get('kanban', 'kanban')->name('kanban');
    Route::get('page-error', 'pageError')->name('pageError');
    Route::get('pricing', 'pricing')->name('pricing');
    Route::get('starred', 'starred')->name('starred');
    Route::get('terms-condition', 'termsCondition')->name('termsCondition');
    Route::get('veiw-details', 'veiwDetails')->name('veiwDetails');
    Route::get('widgets', 'widgets')->name('widgets');
});

// aiApplication
Route::prefix('aiapplication')->group(function () {
    Route::controller(AiapplicationController::class)->group(function () {
        Route::get('/code-generator', 'codeGenerator')->name('codeGenerator');
        Route::get('/code-generatornew', 'codeGeneratorNew')->name('codeGeneratorNew');
        Route::get('/image-generator', 'imageGenerator')->name('imageGenerator');
        Route::get('/text-generator', 'textGenerator')->name('textGenerator');
        Route::get('/text-generatornew', 'textGeneratorNew')->name('textGeneratorNew');
        Route::get('/video-generator', 'videoGenerator')->name('videoGenerator');
        Route::get('/voice-generator', 'voiceGenerator')->name('voiceGenerator');
    });
});

// Authentication
Route::prefix('authentication')->group(function () {
    Route::controller(AuthenticationController::class)->group(function () {
        Route::get('/forgot-password', 'forgotPassword')->name('forgotPassword');
        Route::get('/sign-in', 'signin')->name('signin');
        Route::get('/sign-up', 'signup')->name('signup');
    });
});

// chart
Route::prefix('chart')->group(function () {
    Route::controller(ChartController::class)->group(function () {
        Route::get('/column-chart', 'columnChart')->name('columnChart');
        Route::get('/line-chart', 'lineChart')->name('lineChart');
        Route::get('/pie-chart', 'pieChart')->name('pieChart');
    });
});

// Componentpage
Route::prefix('componentspage')->group(function () {
    Route::controller(ComponentspageController::class)->group(function () {
        Route::get('/alert', 'alert')->name('alert');
        Route::get('/avatar', 'avatar')->name('avatar');
        Route::get('/badges', 'badges')->name('badges');
        Route::get('/button', 'button')->name('button');
        Route::get('/calendar', 'calendar')->name('calendar');
        Route::get('/card', 'card')->name('card');
        Route::get('/carousel', 'carousel')->name('carousel');
        Route::get('/colors', 'colors')->name('colors');
        Route::get('/dropdown', 'dropdown')->name('dropdown');
        Route::get('/imageupload', 'imageUpload')->name('imageUpload');
        Route::get('/list', 'list')->name('list');
        Route::get('/pagination', 'pagination')->name('pagination');
        Route::get('/progress', 'progress')->name('progress');
        Route::get('/radio', 'radio')->name('radio');
        Route::get('/star-rating', 'starRating')->name('starRating');
        Route::get('/switch', 'switch')->name('switch');
        Route::get('/tabs', 'tabs')->name('tabs');
        Route::get('/tags', 'tags')->name('tags');
        Route::get('/tooltip', 'tooltip')->name('tooltip');
        Route::get('/typography', 'typography')->name('typography');
        Route::get('/videos', 'videos')->name('videos');
    });
});

// Dashboard
Route::prefix('cryptocurrency')->group(function () {
    Route::controller(CryptocurrencyController::class)->group(function () {
        Route::get('/wallet', 'wallet')->name('wallet');
    });
});

// Dashboard
Route::prefix('dashboard')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/index', 'index')->name('index1');
        Route::get('/index-2', 'index2')->name('index2');
        Route::get('/index-3', 'index3')->name('index3');
        Route::get('/index-4', 'index4')->name('index4');
        Route::get('/index-5', 'index5')->name('index5');
        Route::get('/index-6', 'index6')->name('index6');
        Route::get('/index-7', 'index7')->name('index7');
        Route::get('/index-8', 'index8')->name('index8');
        Route::get('/index-9', 'index9')->name('index9');
    });
});

// Forms
Route::prefix('forms')->group(function () {
    Route::controller(FormsController::class)->group(function () {
        Route::get('/form', 'form')->name('form');
        Route::get('/form-layout', 'formLayout')->name('formLayout');
        Route::get('/form-validation', 'formValidation')->name('formValidation');
        Route::get('/wizard', 'wizard')->name('wizard');
    });
});

// invoice/invoiceList
Route::prefix('invoice')->group(function () {
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoice-add', 'invoiceAdd')->name('invoiceAdd');
        Route::get('/invoice-edit', 'invoiceEdit')->name('invoiceEdit');
        Route::get('/invoice-list', 'invoiceList')->name('invoiceList');
        Route::get('/invoice-preview', 'invoicePreview')->name('invoicePreview');
    });
});

// Settings
Route::prefix('settings')->group(function () {
    Route::controller(SettingsController::class)->group(function () {
        Route::get('/company', 'company')->name('company');
        Route::get('/currencies', 'currencies')->name('currencies');
        Route::get('/language', 'language')->name('language');
        Route::get('/notification', 'notification')->name('notification');
        Route::get('/notification-alert', 'notificationAlert')->name('notificationAlert');
        Route::get('/payment-gateway', 'paymentGateway')->name('paymentGateway');
        Route::get('/theme', 'theme')->name('theme');
    });
});

// Table
Route::prefix('table')->group(function () {
    Route::controller(TableController::class)->group(function () {
        Route::get('/table-basic', 'tableBasic')->name('tableBasic');
        Route::get('/table-data', 'tableData')->name('tableData');
    });
});

// Users
Route::prefix('users')->group(function () {
    Route::controller(UsersController::class)->group(function () {
        Route::get('/add-user', 'addUser')->name('addUser');
        Route::get('/users-grid', 'usersGrid')->name('usersGrid');
        Route::get('/users-list', 'usersList')->name('usersList');
        Route::get('/view-profile', 'viewProfile')->name('viewProfile');
    });
});
