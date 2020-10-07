<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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
    return view('index');
});

Route::post('/getListData', 'Controller@getListData');

Route::post('/getExperiencesData', 'Controller@getExperiencesData');

Route::post('/getKnowledgeData', 'Controller@getKnowledgeData');

Route::post('/getVideoData', 'Controller@getVideoData');

Route::post('/getNewsData', 'Controller@getNewsData');

Route::post('/getServiceData', 'Controller@getServiceData');

Route::post('/getQaData', 'Controller@getQaData');

Route::post('/getMobileData', 'Controller@getMobileData');

Route::post('/getMilestoneData', 'Controller@getMilestoneData');

Route::post('/getMediaData', 'Controller@getMediaData');

Route::post('/getPartnerData', 'Controller@getPartnerData');

Route::post('/getBannerData', 'Controller@getBannerData');

Route::post('/getApplydata', 'Controller@getApplydata');

Route::post('/getInvestTonicData', 'Controller@getInvestTonicData');

Route::post('/getReportData', 'Controller@getReportData');

Route::post('/getArticleData', 'Controller@getArticleData');

Route::post('/getVideoPage', 'Controller@getVideoPage');

Route::post('/action', 'Controller@action');


//Account


Route::post('/getTerms', 'Accountcontroller@getTerms');

Route::post('/doLogin', 'Accountcontroller@doLogin');

Route::post('/logout', 'Accountcontroller@logout');

Route::post('/getCaptcha', 'Accountcontroller@getCaptcha');

Route::post('/resetPassword', 'Accountcontroller@resetPassword');

Route::post('/doRegister', 'Accountcontroller@doRegister');


//Membercentre


Route::get('/getMyRepayment', 'Membercentrecontroller@getMyRepayment');

Route::post('/getNotification', 'Membercentrecontroller@getNotification');

Route::post('/getRepaymentList', 'Membercentrecontroller@getRepaymentList');

Route::post('/getDetail', 'Membercentrecontroller@getDetail');

Route::post('/getTansactionDetails', 'Membercentrecontroller@getTansactionDetails');

Route::post('/read', 'Membercentrecontroller@read');

Route::get('/allRead', 'Membercentrecontroller@allRead');

Route::get('/getMyInvestment', 'Membercentrecontroller@getMyInvestment');

Route::post('/getRecoveriesList', 'Membercentrecontroller@getRecoveriesList');

Route::post('/getRecoveriesFinished', 'Membercentrecontroller@getRecoveriesFinished');

Route::post('/getRecoveriesInfo', 'Membercentrecontroller@getRecoveriesInfo');

Route::get('/downloadStatement', 'Membercentrecontroller@downloadStatement');


// backstage


Route::get('/web-admin', function () {
    if (Session::get('isLogin')) {
        return view('admin', Session::all());
    } else {
        return view('login');
    }
});

Route::post('/baklogin', 'Backendcontroller@login');

Route::post('/baklogout', 'Backendcontroller@logout');

Route::get('/checkCooperation', 'Backendcontroller@checkCooperation');

Route::get('/checkFeedback', 'Backendcontroller@checkFeedback');

Route::post('/getKnowledge', 'Backendcontroller@getKnowledge');

Route::post('/modifyKnowledge', 'Backendcontroller@modifyKnowledge');

Route::post('/deleteKonwledge', 'Backendcontroller@deleteKonwledge');

Route::post('/uploadKnowledgeIntroImg', 'Backendcontroller@uploadKnowledgeIntroImg');

Route::post('/uploadKnowledgeImg', 'Backendcontroller@uploadKnowledgeImg');

Route::post('/getknowledgeVideoData', 'Backendcontroller@getknowledgeVideoData');

Route::post('/uploadVideoIntroImg', 'Backendcontroller@uploadVideoIntroImg');

Route::post('/uploadVideoImg', 'Backendcontroller@uploadVideoImg');

Route::post('/getPhoneData', 'Backendcontroller@getPhoneData');

Route::post('/uploadPhoneFile', 'Backendcontroller@uploadPhoneFile');

Route::post('/modifyPhoneData', 'Backendcontroller@modifyPhoneData');

Route::post('/deletePhoneData', 'Backendcontroller@deletePhoneData');

Route::post('/getMilestoneData', 'Backendcontroller@getMilestoneData');

Route::post('/modifyMilestoneData', 'Backendcontroller@modifyMilestoneData');

Route::post('/deleteMilestoneData', 'Backendcontroller@deleteMilestoneData');

Route::post('/recaptcha', 'Backendcontroller@recaptcha');

Route::get('/getMediaData', 'Backendcontroller@getMediaData');

Route::post('/modifyMediaData', 'Backendcontroller@modifyMediaData');

Route::post('/deleteMediaData', 'Backendcontroller@deleteMediaData');

Route::get('/getPartnerData', 'Backendcontroller@getPartnerData');

Route::post('/modifyPartnerData', 'Backendcontroller@modifyPartnerData');

Route::post('/deletePartnerData', 'Backendcontroller@deletePartnerData');

Route::post('/uploadPartnerImg', 'Backendcontroller@uploadPartnerImg');

Route::get('/getFeedbackData', 'Backendcontroller@getFeedbackData');

Route::post('/readFeedbackData', 'Backendcontroller@readFeedbackData');

Route::post('/modifyFeedbackData', 'Backendcontroller@modifyFeedbackData');

Route::post('/deleteFeedbackData', 'Backendcontroller@deleteFeedbackData');

Route::get('/getCooperationData', 'Backendcontroller@getCooperationData');

Route::post('/readCooperationData', 'Backendcontroller@readCooperationData');

Route::post('/deleteCooperationData', 'Backendcontroller@deleteCooperationData');


// verify mail


Route::get('/verifyemail', 'Backendcontroller@verifyemail');


// event


Route::get('/event', function () {
    return view('event');
});


Route::get('/getRotationData', 'Backendcontroller@getRotationData');

Route::post('/ratate', 'Backendcontroller@ratate');

Route::post('/checkStatus', 'Backendcontroller@checkStatus');


// feedback
Route::post('/sendFeedback', 'Controller@sendFeedback');


//
// Route::get('/zxc', 'Controller@zxc');
Route::get('/openaccount', function () {
    return view('openaccount');
});

Route::get('/immediately', function () {
    return view('immediately');
});


Route::view('/{path?}', 'index');
