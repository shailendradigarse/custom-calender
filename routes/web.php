<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HolidayController;

Route::get('/', [HolidayController::class, 'showCalendar'])->name('holiday.calendar');
Route::post('/holiday/add', [HolidayController::class, 'addHoliday'])->name('holiday.add');
Route::post('/holiday/edit', [HolidayController::class, 'editHoliday'])->name('holiday.edit');
Route::post('/holiday/delete', [HolidayController::class, 'deleteHoliday'])->name('holiday.delete');
