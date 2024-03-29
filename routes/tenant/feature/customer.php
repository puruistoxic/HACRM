<?php

use App\Http\Controllers\Tenant\Contacts\AddressController;
use App\Http\Controllers\Tenant\Contacts\ContactPersonController;
use App\Http\Controllers\Tenant\Contacts\NoteController;
use App\Http\Controllers\Tenant\Contacts\PaymentController;
use App\Http\Controllers\Tenant\Contacts\ActivityController;
use App\Http\Controllers\Tenant\Contacts\CustomerController;
use App\Http\Controllers\Tenant\Contacts\CustomerDetailsController;
use App\Http\Controllers\Tenant\Contacts\CustomerImportController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function (Router $route) {

    $route->post('customers/bulk-import', [CustomerImportController::class, 'store'])
        ->name('customers.bulk-import');

    $route->post('customers-import', [CustomerImportController::class, 'preview'])
        ->name('customer-import');


    $route->apiResource('customers', CustomerController::class);

    $route->get('customer/info/{customer}', [CustomerDetailsController::class, 'customerInfo'])->name('customer.info');

    $route->apiResource('{type}/address', AddressController::class);

    $route->apiResource('{type}/contactperson', ContactPersonController::class);

    $route->apiResource('{type}/note', NoteController::class);

    $route->apiResource('{type}/payment', PaymentController::class);
    
    $route->apiResource('{type}/activity', ActivityController::class);

    $route->post('customer/profile/picture/{customer}', [CustomerDetailsController::class, 'profilePictureUpload'])
        ->name('customer.profile.upload');

    $route->post('customer/profile/picture/{customer}', [CustomerDetailsController::class, 'profilePictureUpload'])
        ->name('customer.profile.upload');
});
