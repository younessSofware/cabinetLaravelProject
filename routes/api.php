<?php
use \App\Http\Controllers;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Public routes
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {
    Route::get('/statistics', function(){
        return response()->json([
            'status' => 'Request was successful.',
            'message' => null,
            'data' => [
                'appointement' => Appointment::count(),
                'documents' => 4,
                'patients' => Patient::count(),
                'secritaries' => User::role('secretary')->count(),
            ],
        ]);       
    });
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/test', [\App\Http\Controllers\PatientsController::class, 'test']);
    Route::resource('/appointments', \App\Http\Controllers\AppointmentsController::class);
    Route::get('/documents', [\App\Http\Controllers\AppointmentsController::class, 'passedAppointements']);
    Route::resource('/patients', \App\Http\Controllers\PatientsController::class);
    Route::post('/patients/{cin}', [\App\Http\Controllers\PatientsController::class, 'modify']);
    
    Route::resource('/secritaries', \App\Http\Controllers\SecretaryController::class);
    Route::get('/patient/{id}/report-pdf', [Controllers\PatientsController::class, 'generateReportPDF']);
    Route::put('/appointments/{appointment}/changeStatus', [Controllers\AppointmentsController::class, 'changeStatus']);
});
