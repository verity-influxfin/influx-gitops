<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

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

Route::post('/getIndexBanner', 'Controller@getIndexBanner');

Route::get('/getCount', 'Controller@getCount');

Route::post('/getExperiencesData', 'Controller@getExperiencesData');

Route::post('/getFeedbackImg', 'Controller@getFeedbackImg');

Route::post('/getKnowledgeData', 'Controller@getKnowledgeData');

Route::post('/getVideoData', 'Controller@getVideoData');

Route::post('/getNewsData', 'Controller@getNewsData');

Route::post('/getNewsArticle', 'Controller@getNewsArticle');

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

Route::post('/sendFeedback', 'Controller@sendFeedback');

//Account

Route::post('/getTerms', 'Accountcontroller@getTerms');

Route::post('/doLogin', 'Accountcontroller@doLogin');

Route::post('/logout', 'Accountcontroller@logout');

Route::middleware('throttle:10,1')->post('/getCaptcha', 'Accountcontroller@getCaptcha');

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

Route::get('/bakGetCount', 'Backendcontroller@getCount');

Route::post('/bakUpdateCount', 'Backendcontroller@updateCount');

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

Route::post('/bakUploadUserImg', 'Backendcontroller@uploadFUserImg');

Route::post('/readFeedbackData', 'Backendcontroller@readFeedbackData');

Route::post('/modifyFeedbackData', 'Backendcontroller@modifyFeedbackData');

Route::post('/deleteFeedbackData', 'Backendcontroller@deleteFeedbackData');

Route::get('/getCooperationData', 'Backendcontroller@getCooperationData');

Route::post('/readCooperationData', 'Backendcontroller@readCooperationData');

Route::post('/deleteCooperationData', 'Backendcontroller@deleteCooperationData');

Route::get('/getNews', 'Backendcontroller@getNews');

Route::post('/modifyNews', 'Backendcontroller@modifyNews');

Route::post('/deleteNews', 'Backendcontroller@deleteNews');

Route::post('/uploadNewsIntroImg', 'Backendcontroller@uploadNewsIntroImg');

Route::post('/uploadNewsImg', 'Backendcontroller@uploadNewsImg');

Route::get('/bakGetIndexBanner', 'Backendcontroller@getIndexBanner');

Route::post('/modifyBannerData', 'Backendcontroller@modifyBannerData');

Route::post('/deleteBannerData', 'Backendcontroller@deleteBannerData');

Route::post('/uploadBannerImg', 'Backendcontroller@uploadBannerImg');

Route::get('/bakGetCampusData', 'Backendcontroller@getCampusData');

Route::get('/getMemberFile', function (Request $request) {
    $inputs = $request->all();

    return response()->download(realpath(base_path('public')) . '/upload/campus/' . $inputs['type'] . '/' . $inputs['file'], $inputs['file']);
});

Route::get('/bakDownloadTypeFile', 'Backendcontroller@downloadTypeFile');

Route::post('/bakGetFeedbackImg', 'Backendcontroller@getFeedbackImg');

Route::post('/bakUploadFeedbackImg', 'Backendcontroller@uploadFeedbackImg');

Route::post('/bakModifyFeedbackImgData', 'Backendcontroller@modifyFeedbackImgData');

Route::post('/bakUpdateImgOrder', 'Backendcontroller@updateImgOrder');

Route::post('/bakDeleteFeedbackImg', 'Backendcontroller@deleteFeedbackImg');

// campusJoin

Route::get('/campusJoin', function () {
    return view('campusJoin');
});

Route::post('/campusUploadFile', 'Controller@campusUploadFile');

Route::post('/campusSignup', 'Controller@campusSignup');

// event

Route::post('/eventGetNum', 'Eventcontroller@getNum');

Route::post('/eventRegister', 'Eventcontroller@register');

//greeting

Route::get('/greeting', function () {
    return view('greeting');
});

Route::post('/uploadGreetingAuthorImg', 'Greetingcontroller@uploadGreetingAuthorImg');

Route::post('/deleteGreetingAuthorImg', 'Greetingcontroller@deleteGreetingAuthorImg');

Route::post('setGreetingData','Greetingcontroller@setGreetingData');

Route::post('getGreetingData','Greetingcontroller@getGreetingData');

Route::view('/greeting/{path?}', 'greeting');

// verify mail

Route::get('/verifyemail', 'Backendcontroller@verifyemail');


Route::get('/investLink', function () {
    return view('invest');
});

Route::get('/borrowLink', function () {
    return view('borrow');
});


//NewYear card Game

Route::get('/cardgame', function () {
    return view('cardgame');
});



Route::view('/{path?}', 'index');