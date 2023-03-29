<?php

use App\Models\KnowledgeArticle;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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


Route::fallback(function () {
    $default_desc = '普匯金融科技擁有全台首創風控審核無人化融資系統。普匯提供小額信用貸款申貸服務，資金用途涵蓋購房、購車，或是房屋裝修潢。您可在普匯官網取得貸款額度試算結果！現在就來體驗最新的p2p金融科技吧！除了個人信貸，普匯也提供中小企業融資，幫助業主轉型智慧製造。';
    $default_title = 'inFlux普匯金融科技';
    $default_og_img = asset('images/site_icon.png');
    $latestArticles = (new App\Http\Controllers\KnowledgeArticleController)->get_knowledge_articles();
    return view('index', [
        'meta_description' => $default_desc,
        'meta_og_description' => $default_desc,
        'web_title' => $default_title,
        'meta_og_title' =>  $default_title,
        'meta_og_image' =>  $default_og_img,
        'latestArticles' => $latestArticles
    ]);
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

Route::post('/getBorrowReport', 'Controller@getBorrowReport');

Route::post('/sendQuestion', 'Controller@sendQuestion');

Route::post('/getCase', 'Controller@getCase');

Route::post('/getTransferCase', 'Controller@getTransferCase');
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

Route::post('/getInvestReport', 'Membercentrecontroller@getInvestReport')
    ->middleware('cert.chk:identity');

Route::get('/downloadInvestReport', 'Membercentrecontroller@downloadInvestReport');

Route::get('/getPromoteCode', 'Membercentrecontroller@getPromoteCode');
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

Route::post('/uploadJobApplication', 'Backendcontroller@uploadJobApplication');

Route::post('/uploadGoogleQA', 'Backendcontroller@uploadGoogleQA');

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
// 校園大使舊版頁面轉址
Route::get('/campusJoin',  function () {
    return redirect('/campaign/2021-campus-ambassador');
});
// 校園大使舊版頁面轉址
Route::get('/campuspartner',  function () {
    return redirect('/campaign/2021-campus-ambassador');
});

Route::post('/campusUploadFile', 'Controller@campusUploadFile');

Route::post('/campusSignup', 'Controller@campusSignup');

// event

Route::post('/eventGetNum', 'Eventcontroller@getNum');

Route::post('/eventRegister', 'Eventcontroller@register');

Route::post('/newyearRegister', 'Eventcontroller@newyearRegister');
Route::post('/newyearLogin', 'Eventcontroller@newyearLogin');

Route::post('/sendBankEvent', 'Eventcontroller@bankEvent');

//greeting

Route::get('/greeting', function () {
    return view('greeting');
});

Route::post('/uploadGreetingAuthorImg', 'Greetingcontroller@uploadGreetingAuthorImg');

Route::post('/deleteGreetingAuthorImg', 'Greetingcontroller@deleteGreetingAuthorImg');

Route::post('setGreetingData', 'Greetingcontroller@setGreetingData');

Route::post('getGreetingData', 'Greetingcontroller@getGreetingData');

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
Route::post('getAns', 'Cardgamecontroller@getAns');
Route::post('getData', 'Cardgamecontroller@getData');
Route::post('setGamePrize', 'Cardgamecontroller@setGamePrize');

Route::view('/cardgame/{path?}', 'cardgame');

Route::get('/campaign/{name}/{path?}', function (string $name, string $path = 'index') {

    $name = str_replace('-', '_', strtolower($name));

    switch (true) {

            // 報名截止跳轉活動介紹頁
        case $name == '2021_campus_ambassador' && $path != 'index':
            return redirect('/campaign/2021-campus-ambassador');

        case view()->exists($path = sprintf('campaigns/%s/%s', $name, $path)):
            return view($path);
    }
    throw new NotFoundHttpException();
});

Route::get('/articlepage/{path?}', function (Request $request, $path = '') {
    $ArticleController = (new App\Http\Controllers\KnowledgeArticleController);
    $latestArticles = $ArticleController->get_knowledge_articles();
    if(!empty($path)){
        // 自定義網址轉換
        $article = $ArticleController->get_knowledge_article_by_path($path);
        // check article not null
        if (empty($article)) {
            return redirect('/');
        }
        $meta_data = $ArticleController->get_meta_info($article->id);
        $meta_data['link'] = $request->fullUrl();
        return view('articlePage', [
            'type' => 'knowledge',
            'article' => $article,
            'latestArticles' => $latestArticles,
            'meta_data' => $meta_data
        ]);
    }
    $input = $request->all();
    @list($type, $params) = explode('-', $input['q']);
    if ($type == 'knowledge') {
        $meta_data = $ArticleController->get_meta_info($params);
        $meta_data['link'] = $request->fullUrl();
        $article = $ArticleController->get_knowledge_article($params);
        // check article not null
        if (empty($article)) {
            return redirect('/');
        }
        if (!empty($article->path)) {
            return redirect('/articlepage/' . $article->path, 301);
        }
        return view('articlePage', [
            'type' => $type,
            'article' => $article,
            'latestArticles' => $latestArticles,
            'meta_data' => $meta_data
        ]);
    } else if ($type == 'news') {
        //get news
        $newsController = (new App\Http\Controllers\NewsController);
        $meta_data = $newsController->get_meta_info($params);
        $meta_data['link'] = $request->fullUrl();
        $news = $newsController->get_news($params);
        if (empty($news)){
            return redirect('/');
        }
        return view('articlePage', [
            'type' => $type,
            'article' => $news,
            'meta_data' => $meta_data,
            'latestArticles' => $latestArticles,
        ]);
    } else {
        return redirect('/');
    }

});

Route::get('/{path?}', function (Request $request, $path = '') {
    $default_desc = '普匯金融科技擁有全台首創風控審核無人化融資系統。普匯提供小額信用貸款申貸服務，資金用途涵蓋購房、購車，或是房屋裝修潢。您可在普匯官網取得貸款額度試算結果！現在就來體驗最新的p2p金融科技吧！除了個人信貸，普匯也提供中小企業融資，幫助業主轉型智慧製造。';
    $default_title = 'inFlux普匯金融科技';
    $default_og_img = asset('images/site_icon.png');
    $latestArticles = (new App\Http\Controllers\KnowledgeArticleController)->get_knowledge_articles();
    if(!empty($path)){
      $meta_data = (new App\Http\Controllers\RouteMetaController)->getMeta($path);
    }
    return view('index', [
        'meta_description' => !empty($meta_data['meta_description']) ? $meta_data['meta_description'] : $default_desc,
        'meta_og_description' => !empty($meta_data['meta_og_description']) ? $meta_data['meta_og_description'] : $default_desc,
        'web_title' => !empty($meta_data['web_title']) ? $meta_data['web_title'] : $default_title,
        'meta_og_title' => !empty($meta_data['meta_og_title']) ? $meta_data['meta_og_title'] : $default_title,
        'meta_og_image' => !empty($meta_data['meta_og_image']) ? $meta_data['meta_og_image'] : $default_og_img,
        'meta_canonical' => !empty($meta_data['link']) ? $meta_data['link'] : '',
        'latestArticles' => $latestArticles
    ]);
});

// for deep route
Route::get('/{path}/{path2?}', function (Request $request, $path = '', $path2 = '') {
    $default_desc = '普匯金融科技擁有全台首創風控審核無人化融資系統。普匯提供小額信用貸款申貸服務，資金用途涵蓋購房、購車，或是房屋裝修潢。您可在普匯官網取得貸款額度試算結果！現在就來體驗最新的p2p金融科技吧！除了個人信貸，普匯也提供中小企業融資，幫助業主轉型智慧製造。';
    $default_title = 'inFlux普匯金融科技';
    $default_og_img = asset('images/site_icon.png');
    $latestArticles = (new App\Http\Controllers\KnowledgeArticleController)->get_knowledge_articles();
    if(!empty($path2)){
      $meta_data = (new App\Http\Controllers\RouteMetaController)->getMeta($path . '/' . $path2);
    }
    return view('index', [
        'meta_description' => !empty($meta_data['meta_description']) ? $meta_data['meta_description'] : $default_desc,
        'meta_og_description' => !empty($meta_data['meta_og_description']) ? $meta_data['meta_og_description'] : $default_desc,
        'web_title' => !empty($meta_data['web_title']) ? $meta_data['web_title'] : $default_title,
        'meta_og_title' => !empty($meta_data['meta_og_title']) ? $meta_data['meta_og_title'] : $default_title,
        'meta_og_image' => !empty($meta_data['meta_og_image']) ? $meta_data['meta_og_image'] : $default_og_img,
        'meta_canonical' => !empty($meta_data['link']) ? $meta_data['link'] : '',
        'latestArticles' => $latestArticles
    ]);
});


// API v1
Route::prefix('api/v1')->group(function () {

    // 全站搜尋
    Route::get('search', 'SearchController@page');

    // 立即申辦表單
    Route::post('saveApplyForm', 'SmeFormController@saveApplyForm');

    // 我要諮詢表單
    Route::post('saveConsultForm', 'SmeFormController@saveConsultForm');

    // 統一編號取公司名稱
    Route::get('getCompanyName', 'SmeFormController@getCompanyName');

    // 企業
    Route::get('product/applylist', 'ProductController@getApplyList');
    Route::get('/product/applyinfo', 'ProductController@getApplyInfo');
    Route::post('/certification/judicial_file_upload', 'ProductController@postCertFileUpload');
    Route::post('/certification/natural_file_upload', 'ProductController@postNaturalFileUpload');
    Route::post('/certification/profile', 'ProductController@postCertificationProfile');
    Route::post('/certification/email', 'ProductController@postCertificationEmail');
    Route::post('/certification/profilejudicial', 'ProductController@postCertificationProfilejudicial');
    Route::post('/user/upload_pdf', 'ProductController@postUploadPdf');
    Route::post('/user/upload', 'ProductController@postUpload');

    // 上班族貸
    Route::post('/work-loan/contact', 'WorkLoanController@save_contact');
    Route::post('/work-loan/share', 'WorkLoanController@save_share');

    // 上班族貸
    Route::post('/business-loan/contact', 'BusinessLoanController@save_contact');

    // 活動
    Route::get('/campaign2022/list', 'Campaign2022Controller@get_all');
    Route::get('/campaign2022/list/page/{page}', 'Campaign2022Controller@get_by_page');
    Route::get('/campaign2022/list/search/{keyword}/page/{page}', 'Campaign2022Controller@get_by_keyword');
    Route::get('/campaign2022/data/{id}', 'Campaign2022Controller@get_one');
    Route::post('/campaign2022/upload', 'Campaign2022Controller@save_file');
    Route::post('/campaign2022/vote', 'Campaign2022Controller@save_vote');
    Route::get('/campaign2022/montage', 'Campaign2022Controller@get_montage');

    // 校園大使 (換年度，換Controller)
    Route::prefix('/campus-ambassador')->group(function () {
        // 報名
        Route::group([
            'prefix' => '/sign-up',
            'middleware' => 'member.login.chk'
        ], function () {
            // 個人組
            Route::post('/individual', 'CampusAmbassador2022Controller@sign_up_individual');
            // 團體組
            Route::post('/group', 'CampusAmbassador2022Controller@sign_up_group');
        });
    });

    // 風險報告書
    Route::get('/risk_report/{year}/{month}', 'RiskReportController@get_info_by_month');
    Route::get('/risk_report_list', 'RiskReportController@get_list')->middleware('cert.chk:identity');
});

// 捐款動畫 SSE API
Route::get('/event/charity/donation', 'CharityController@getDonation');

// 遊客捐款 & 查詢捐款 API
Route::post('/charity/donate/anonymous', 'CharityController@visitorDonate');
Route::get('/charity/donate/anonymous', 'CharityController@visitorSearch');


Route::prefix('/chk/cert')->group(function () {
    // 個別檢查徵信項
    Route::get('/{cert_alias}', [
        'middleware' => 'cert.chk',
        'uses' => 'CertController@chk_status'
    ]);
});
