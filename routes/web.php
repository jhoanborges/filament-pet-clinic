<?php

use Illuminate\Http\Request;
use Filament\Pages\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use App\Filament\Doctor\Resources\PetResource;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/subscription-checkout', function (Request $request) {

    return $request->user()
        ->newSubscription('default', env('CASHIER_STRIPE_SUBSCRIPTION_DEFAULT_PRICE_ID'))
        //->createAndSendInvoice()
        ->trialDays(0)
        ->allowPromotionCodes()
        ->checkout([
            'success_url' => route('filament.doctor.pages.dashboard'),
            'cancel_url' => route('home'),
        ]);
})->name('subscription-checkout');
