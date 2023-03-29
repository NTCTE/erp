<?php

use App\Http\Controllers\EdPart\Schedule\Legacy;
use App\Http\Controllers\EdPart\Schedule\LegacyController;
use App\Http\Controllers\System\MachinesController;
use Illuminate\Http\Request;
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

/**
 * Legacy support of schedule
 */
Route::get('schedule/legacy/{date?}', [LegacyController::class, 'getSchedule']);
Route::get('schedule/legacy/{date}/{type}/{name?}', [LegacyController::class, 'getList']);

// Machines
// /machine/register
Route::post('machine/register', [MachinesController::class, 'register']);
// /machine/{uuid}
Route::get('machine/{uuid}', [MachinesController::class, 'get_commands']);
// /machine/{uuid}/set
Route::post('machine/{uuid}/set', [MachinesController::class, 'set_command']);