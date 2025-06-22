<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/1/2019
 * Time: 10:02 AM
 */
use Illuminate\Support\Facades\Route;
Route::group(['prefix' => 'booking'],function (){
    // Route::get('/','BookingController@index')->name('report.admin.booking');
    Route::get('/email_preview/{id}','BookingController@email_preview')->name('report.admin.booking.email_preview');
    Route::post('/bulkEdit','BookingController@bulkEdit')->name('report.admin.booking.bulkEdit');
    Route::post('/cancellation','BookingController@cancellationRquest')->name('report.admin.booking.cancellationRquest');
    Route::post('/add_confirmation_number','BookingController@addConfirmationNumber')->name('report.admin.booking.addConfirmationNumber');
});
Route::get('/enquiry','EnquiryController@index')->name('report.admin.enquiry.index');

Route::post('/enquiry/bulkEdit','EnquiryController@bulkEdit')->name('report.admin.enquiry.bulkEdit');

Route::get('/enquiry/{enquiry}/reply','EnquiryController@reply')->name('report.admin.enquiry.reply');
Route::post('/enquiry/{enquiry}/reply/store','EnquiryController@replyStore')->name('report.admin.enquiry.replyStore');

Route::get('/booking','BookingController@index')->name('report.admin.booking');

Route::post('/booking/bulkEdit','BookingController@bulkEdit')->name('report.admin.booking.bulkEdit');

Route::get('/booking/{booking}/reply','BookingController@reply')->name('report.admin.booking.reply');
Route::post('/booking/{booking}/reply/store','BookingController@replyStore')->name('report.admin.booking.replyStore');


Route::get('/statistic','StatisticController@index')->name('report.admin.statistic.index');
Route::match(['get','post'],'/statistic/reloadChart','StatisticController@reloadChart')->name('report.admin.statistic.reloadChart');
