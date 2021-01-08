<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <title>校園大使 - 加入我們</title>
    <link rel="icon" href="{{ asset('/images/site_icon.png') }}">

    <!-- package -->
    <link rel="stylesheet" href="{{ asset('css/package/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/pagination.min.css') }}">

    <!-- local -->
    <link rel="stylesheet" href="{{ asset('css/campusJoin.css') }}">
</head>

<body>
    <div id="campusJoin_wrapper">
        <nav class="page-header navbar navbar-expand-lg sticky">
            <div class="web-logo">
                <a href="/index"><img src=" {{ asset('images/logo_new.png') }}" class="img-fluid"></a>
            </div>
        </nav>

        <template v-if="!isSingup">
            <div class="from-area">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">隊伍名稱<i>*</i></span>
                        </div>
                        <input type="text" id="teamName" class="form-control" v-model="teamName" @blur="checked($event)" />
                    </div>
                </div>

                <div class="action-bar form-group">
                    <button class="btn btn-primary btn-sm float-right" @click="createMember()">
                        <i class="fas fa-plus"></i>
                        <span>新增隊員</span>
                    </button>
                </div>

                <template v-for="(item,index) in memberList">
                    <div class="box">
                        <div class="form-group" style="overflow:auto">
                            <button class="btn btn-danger btn-sm mg" @click="deleteRow(index)">
                                移除隊員
                            </button>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">姓名<i>*</i></span>
                                </div>
                                <input type="text" :id="`name_${index}`" class="form-control" autocomplete="off" v-model="item.name" @blur="checked($event)" />
                                <div class="input-group-prepend">
                                    <span class="input-group-text">年級<i>*</i></span>
                                </div>
                                <input type="text" :id="`grade_${index}`" class="form-control" autocomplete="off" v-model="item.grade" @blur="checked($event)" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">學校<i>*</i></span>
                                </div>
                                <input type="text" :id="`school_${index}`" class="form-control" autocomplete="off" v-model="item.school" @blur="checked($event)" />
                                <div class="input-group-prepend">
                                    <span class="input-group-text">科系<i>*</i></span>
                                </div>
                                <input type="text" :id="`department_${index}`" class="form-control" autocomplete="off" v-model="item.department" @blur="checked($event)" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">手機<i>*</i></span>
                                </div>
                                <input type="text" :id="`mobile_${index}`" class="form-control" autocomplete="off" v-model="item.mobile" @blur="checked($event)" />
                                <div class="input-group-prepend">
                                    <span class="input-group-text">E-Mail<i>*</i></span>
                                </div>
                                <input type="text" :id="`email_${index}`" class="form-control" autocomplete="off" v-model="item.email" @blur="checked($event)" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">簡單自我介紹<i>*</i></span>
                                </div>
                                <textarea type="text" :id="`selfIntro_${index}`" class="form-control" autocomplete="off" v-model="item.selfIntro" @blur="checked($event)"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">個人簡歷表<i>*</i></span>
                                </div>
                                <input type="file" :id="`resume_${index}`" name="resume" class="form-control" @change="uploadFile($event, index)" @blur="checked($event)" />
                                <span class="ps">格式限制為PDF</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">企劃書</span>
                                </div>
                                <input type="file" :id="`proposal_${index}`" name="proposal" class="form-control" @change="uploadFile($event, index)" />
                                <span class="ps">格式限制為PDF</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">作品集</span>
                                </div>
                                <input type="file" :id="`portfolio_${index}`" name="portfolio" class="form-control" @change="uploadFile($event, index)" />
                                <span class="ps">格式限制為PDF</span>
                            </div>
                        </div>
                    </div>
                </template>

                <button class="btn btn-success btn-custom" @click="submit()" :disabled="memberList.length === 0">送出</button>
            </div>
        </template>
        <template v-else>
            <!-- to do 報名成功 QRCODE -->
            <div class="from-area success">
                <h3 class="">報名成功，請留意面試通知</h3>
                <p>滾滾長江東逝水滾滾長江東逝水滾滾長江東逝水滾滾長江東逝水滾滾長江東逝水滾滾長江東逝水滾滾長江東逝水滾滾長江東逝水</p>
                <div class="img"><img class="img-fluid" src="{{ asset('/images/downloadAPP.svg') }}"></div>
            </div>
        </template>
    </div>

    <!-- package -->
    <script type="text/javascript" src="{{ asset('js/package/es6-promise.auto.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/jQuery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/axios.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vue.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vuex.min.js') }}"></script>
    <!-- local -->
    <script type="text/javascript" src="{{ asset('js/campusJoin.js') }}"></script>
</body>

</html>