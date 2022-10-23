<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PortofolioController;
use App\Http\Controllers\Api\PortofolioTypeController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::controller(AuthController::class)->group(function () {
    Route::post('/auth/register', 'register');
    Route::post('/auth/login', 'login');
    Route::post('/auth/forgot-password', 'forgot');
    Route::post('/auth/reset-password', 'reset')->name('password.reset');

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('/auth/profile')->group(function () {
            Route::get('/', 'profile');
            Route::post('/', 'update');
        });
        Route::post('/auth/logout', 'logout');
        Route::apiResource('/skills', SkillController::class);
        Route::apiResource('/portofolio-types', PortofolioTypeController::class);
        Route::get('/portofolios/get-my-portofolio', [PortofolioController::class, 'getMyPortofolio']);
        Route::post('/portofolios/add-my-portofolio', [PortofolioController::class, 'addMyPortofolio']);
        Route::post('/portofolios/add-draft-portofolio', [PortofolioController::class, 'addDraftPortofolio']);
        Route::post('/portofolios/upload-portofolio-image', [PortofolioController::class, 'uploadPortofolioImage']);
        Route::post('/portofolios/save-portofolio', [PortofolioController::class, 'savePortofolio']);
        Route::post('/portofolios/delete-portofolio', [PortofolioController::class, 'deletePortofolio']);
        Route::post('/portofolios/test-upload', [PortofolioController::class, 'testUpload']);

        //admin route
        Route::middleware('ability:admin')->group(function () {
            Route::apiResource('/users', UserController::class);
            Route::post('/users/assign-admin', [UserController::class, 'assignAdmin']);
            Route::post('/users/revoke-admin', [UserController::class, 'revokeAdmin']);
        });
    });
});

Route::post('/pages/get-portofolio-page', [PageController::class, 'getPortofolioPageByUsername']);
