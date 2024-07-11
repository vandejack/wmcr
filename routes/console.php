<?php

use Illuminate\Foundation\Inspiring;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('alert_ttr_customer {witel} {status}', function ($witel, $status) {
	OrderController::alert_ttr_customer($witel, $status);
});
Artisan::command('starclick_to_basket {witel} {tipe} {date}', function ($witel, $tipe, $date) {
	OrderController::starclick_to_basket($witel, $tipe, $date);
});
Artisan::command('insera_to_basket {witel} {date}', function ($witel, $date) {
	OrderController::insera_to_basket($witel, $date);
});