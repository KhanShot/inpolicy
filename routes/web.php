<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Route::middleware("auth")->prefix("admin")->group(function (){
    Route::get('/', 'App\Http\Controllers\Admin\AdminController@index');
    Route::resource('/roles', 'App\Http\Controllers\Admin\RolesController');
    Route::resource('/permissions', 'App\Http\Controllers\Admin\PermissionsController');
    Route::resource('/users', 'App\Http\Controllers\Admin\UsersController');
    Route::resource('/pages', 'App\Http\Controllers\Admin\PagesController');
    Route::resource('/activitylogs', 'App\Http\Controllers\Admin\ActivityLogsController')->only([
        'index', 'show', 'destroy'
    ]);
    Route::resource('/settings', 'App\Http\Controllers\Admin\SettingsController');
    Route::get('/generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
    Route::post('/generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);

    //EMAIL SENDER URLS
    Route::resource("/email", 'App\Http\Controllers\Admin\EmailSendController');

    Route::get("/send/email", "App\Http\Controllers\Admin\EmailSendController@sendEmail")->name("sendEmail");

    Route::get("/preview/email/test", "App\Http\Controllers\Admin\EmailSendController@test_email")->name("test_email");


    Route::get("/participants", "App\Http\Controllers\Admin\ParticipantsController@participants" );
    Route::get("/partners", "App\Http\Controllers\Admin\ParticipantsController@partners" );
    Route::post("/importPartners", "App\Http\Controllers\Admin\ParticipantsController@importPartners")->name("importPartners");



    Route::get("/getParticipants", "App\Http\Controllers\Admin\ParticipantsController@getParticipants" )->name("getParticipants");
    Route::post("/addParticipants", "App\Http\Controllers\Admin\ParticipantsController@addParticipants")->name("addParticipants");

    Route::post("/generateCertificatePartners", "App\Http\Controllers\Admin\ParticipantsController@generate_cert_partners")->name("generate_cert_partners");


    Route::get("/speakers", "App\Http\Controllers\Admin\ParticipantsController@speakers" );
    Route::post("/importSpeakers", "App\Http\Controllers\Admin\ParticipantsController@importSpeakers")->name("importSpeakers");
    Route::post("/generateCertificateSpeakers", "App\Http\Controllers\Admin\ParticipantsController@generate_cert_speakers")->name("generate_cert_speakers");
    Route::get("/deleteSpeakers/{id}", "App\Http\Controllers\Admin\ParticipantsController@deleteSpeakers")->name("deleteSpeakers");
    Route::get("/testingSpeakers/{id}", "App\Http\Controllers\Admin\ParticipantsController@testingSpeakers")->name("testingSpeakers");

    Route::post("import", "App\Http\Controllers\Admin\EmailSendController@import")->name("import");

});


Route::get("/download/pdf/{id}/{name}", "App\Http\Controllers\Admin\EmailSendController@downloadPDF")->name("pdf");

Route::get("/email/test", function (){
   return view("emails.test");
});

Route::get("/download/pdf/", "App\Http\Controllers\Admin\EmailSendController@downloadPDFTest")->name("pdfTest");

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
