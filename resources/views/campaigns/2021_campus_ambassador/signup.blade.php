@php
function image_url(string $filename) {
    return '/images/campaigns/2021-campus-ambassador/' . $filename;
}
@endphp

@extends('campaigns.layout', [
    'using_vuejs' => TRUE,
    'page' => [
        'title'         => '2021普匯校園大使徵選開跑 - inFlux普匯金融科技',
        'description'   => '普匯校園大使首屆擴大招募！不只法商文組，連資訊理工科系學生也搶破頭想進入的FinTech產業，即日起至9月28日熱烈徵選，歡迎你來大展身手！從教育訓練、CEO面對面提案，一直到執行專案、成果檢視，不限科系，只要你充滿熱情、勇於挑戰，即獲取報名門票。',
        'cover_image'   => url('/images/campaigns/2021-campus-ambassador/banner.jpg'),
        'site_url'      => url()->current(),
        'meta_keywords' => [
            '2021普匯校園大使',
            '金融科技'
        ],
        'GTM' => env('APP_ENV') == 'production' ? 'GTM-5Z439PW' : 'GTM-589Z9H6'
    ],
    'event' => [
        'name'        => '2021普匯校園大使徵選開跑',
        'description' => '成為普匯校園大使，完成屬於自己大學生涯的校園專案，不限科系，只要你充滿熱情、勇於挑戰，即獲取報名門票！',
        'start_date'  => '2021-09-01',
        'end_date'    => '2021-09-28',
        'signup_url'  => url('/campaign/2021-campus-ambassador/signup'),
    ]
])

@section('content')
<div class="container">

    <div class="row">
        <section class="banner mb-5">
            <div class="col">
                <img src="{{image_url('banner.jpg')}}" class="img-fluid d-none d-lg-inline" alt="2021普匯校園大使徵選開跑">
                <img src="{{image_url('banner_m.jpg')}}" class="img-fluid d-inline d-lg-none" alt="2021普匯校園大使徵選開跑">
            </div>
        </section>
    </div>
    <div id="app" class="row">
        <section class="form-area">
            <div class="col px-3">
                <div class="row">
                    <div class="col">
                        <h2>普匯校園大使報名資料填寫</h2>
                        <p class="lead">
                            同團隊只需指派一名同學填表即可
                        </p>
                    </div>
                    <div class="col text-end">
                        <a class="link-secondary text-decoration-none" href="{{url('campaign/2021-campus-ambassador')}}">
                            <i class="bi bi-chevron-double-left"></i> 回活動頁面
                        </a>
                    </div>
                </div>
                <hr class="my-5" />

                <div class="row mb-3">
                    <label for="school" class="col-sm-2 col-form-label">
                        學校 <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-10">
                        <input id="school" v-model="data.school" type="text" class="form-control" :class="{'is-invalid':errors.school}" placeholder="請填寫學校名稱" required>
                        <div class="invalid-feedback">要提供學校名稱喔</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="team_name" class="col-sm-2 col-form-label">
                        團隊名稱 <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-10">
                        <input id="team_name" v-model="data.team_name" maxlength="255" type="text" class="form-control" placeholder="個人報名也來取個有記憶點的隊名吧！" :class="{'is-invalid':errors.team_name}" required>
                        <div class="invalid-feedback">團隊名稱也要寫喔</div>
                    </div>
                </div>

                <div class="row mb-5">
                    <label for="team_intro" class="col-sm-2 col-form-label">
                        團隊介紹
                    </label>
                    <div class="col-sm-10">
                        <textarea id="team_intro" v-model="data.team_intro" placeholder="300字內，個人報名可省略" class="form-control" :class="{'is-invalid':errors.team_intro}"></textarea>
                        <div class="invalid-feedback">幫團隊介紹一下吧!</div>
                    </div>
                </div>

                <h3>隊員資料</h3>

                <hr class="my-4" />

                <div class="row mb-5">
                    <div class="col">
                        {{-- teammate-info card --}}
                        <div class="card teammate-info my-3" v-for="(m,index) in data.members">
                            <div class="card-body">
                                <div class="d-inline-block mb-1 w-100" v-if="data.members.length>1">
                                    <button type="button" title="移除隊員" class="btn-close float-end" aria-label="Close" @click="rm_member(index)"></button>
                                </div>

                                <div class="row mb-3">
                                    <label :for="elid('name', index)" class="col-sm-3 col-form-label">
                                        姓名 <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input v-model="m.name" :id="elid('name', index)" type="text" class="form-control" placeholder="請填寫姓名" :class="{'is-invalid':errors.members[index].name}" required>
                                        <div class="invalid-feedback">沒有填寫姓名喔</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label :for="elid('dept', index)" class="col-sm-3 col-form-label">
                                        科系 <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input v-model="m.dept" :id="elid('dept', index)" type="text" class="form-control" placeholder="請填寫系所名稱" :class="{'is-invalid':errors.members[index].dept}" required>
                                        <div class="invalid-feedback">沒有填寫系所名稱喔</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label :for="elid('grade', index)" class="col-sm-3 col-form-label">
                                        學位年級 <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input v-model="m.grade" :id="elid('grade', index)" type="text" class="form-control" placeholder="請填寫年級 範例：學士大三/ 碩士大二" :class="{'is-invalid':errors.members[index].grade}" required>
                                        <div class="invalid-feedback">沒有填寫年級喔</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label :for="elid('mobile', index)" class="col-sm-3 col-form-label">
                                        手機 <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input v-model="m.mobile" :id="elid('mobile', index)" type="text" class="form-control" placeholder="請填寫手機號碼 ex:0987654321" maxlength="10" :class="{'is-invalid':errors.members[index].mobile}" required>
                                        <div class="invalid-feedback">手機號碼不正確喔</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label :for="elid('email', index)" class="col-sm-3 col-form-label">
                                        E-mail <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input v-model="m.email" :id="elid('email', index)" type="email" class="form-control" placeholder="請填寫電子信箱" :class="{'is-invalid':errors.members[index].email}" required>
                                        <div class="invalid-feedback">電子信箱不正確喔</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label :for="elid('self_intro', index)" class="col-sm-3 col-form-label">
                                        自我簡介 <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <textarea v-model="m.self_intro" :id="elid('self_intro', index)" class="form-control" placeholder="200字內" maxlength="200" :class="{'is-invalid':errors.members[index].self_intro}" required></textarea>
                                        <div class="invalid-feedback">沒有填寫自我簡介喔</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label :for="elid('motivation', index)" class="col-sm-3 col-form-label">
                                        報名動機 <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <textarea v-model="m.motivation" :id="elid('motivation', index)" class="form-control" placeholder="200字內" maxlength="200" :class="{'is-invalid':errors.members[index].motivation}" required></textarea>
                                        <div class="invalid-feedback">沒有填寫報名動機喔</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label :for="elid('fb_link', index)" class="col-sm-3 col-form-label">
                                        個人臉書連結
                                    </label>
                                    <div class="col-sm-9">
                                        <input v-model="m.fb_link" type="text" :id="elid('fb_link', index)" class="form-control">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label :for="elid('ig_link', index)" class="col-sm-3 col-form-label">
                                        個人IG連結
                                    </label>
                                    <div class="col-sm-9">
                                        <input v-model="m.ig_link" type="text" :id="elid('ig_link', index)" class="form-control">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label :for="elid('resume', index)" class="col-sm-3 col-form-label">
                                        個人履歷 <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input @change="set_member_file($event, index)" class="form-control" type="file" :id="elid('resume', index)" accept=".pdf" :class="{'is-invalid':errors.members[index].resume}" required>
                                        <div class="form-text">上傳檔案，檔名為隊名_姓名_履歷，格式須為pdf，兩頁為限</div>
                                        <div class="invalid-feedback">沒有上傳個人履歷喔</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label :for="elid('portfolio', index)" class="col-sm-3 col-form-label">
                                        個人作品集
                                    </label>
                                    <div class="col-sm-9">
                                        <input @change="set_member_file($event, index)" class="form-control" type="file" :id="elid('portfolio', index)" accept=".pdf">
                                        <div class="form-text">上傳檔案，檔名為隊名_姓名_作品集，格式須為pdf，檔案大小上限5M</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label :for="elid('bonus', index)" class="col-sm-3 col-form-label">
                                        其他加分項
                                    </label>
                                    <div class="col-sm-9">
                                        <textarea v-model="m.bonus" :id="elid('bonus', index)" class="form-control" placeholder="可貼連結"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- End teammate-info card --}}
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col">
                        <div class="d-grid d-lg-block">
                            <button v-if="data.members.length<3" @click="add_member" type="button" class="btn btn-warning mb-3">
                                <i class="bi bi-plus"></i> 新增隊員
                            </button>
                            <button v-else type="button" class="btn btn-secondary mb-3" disabled>
                                <i class="bi bi-times"></i> 團體報名人數上限為三人
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="d-grid">
                            <button type="button" @click="submit" class="btn btn-primary mb-3" :disabled="submited">送出</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="dialog" class="modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" v-if="!response.success">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3 text-center">
                                <div class="col">
                                    <div class="fs-1">
                                        <i class="bi bi-check-lg text-success" v-if="response.success"></i>
                                        <i class="bi bi-exclamation-triangle-fill text-warning" v-else></i>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 text-center">
                                <div class="col">
                                    <p v-if="response.success">恭喜您報名成功！</p>
                                    <p v-else>@{{response.message}}</p>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col">
                                    <a href="{{url('/campaign/2021-campus-ambassador')}}" class="btn btn-primary" v-if="response.success">返回活動頁面</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" v-else>返回修改資料</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="row">
        @include('campaigns.2021_campus_ambassador.footer')
    </div>
</div>
@endsection

@section('styles')
<style type="text/css">
    section.banner img.img-fluid{
        width: 100%;
    }

    section.form-area .teammate-info {
        position: relative;
    }
    section.form-area button.btn-close {
        position: relative;
        top: 0;
        right: 5px;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript">
    var dialog_model;

    const def_member = {
        name       : '',
        dept       : '',
        grade      : '',
        mobile     : '',
        email      : '',
        self_intro : '',
        resume     : '',
        motivation : '',
        portfolio  : '',
        fb_link    : '',
        ig_link    : '',
        bonus      : '',
    };

    const err_member = {
        name       : false,
        dept       : false,
        grade      : false,
        mobile     : false,
        email      : false,
        self_intro : false,
        resume     : false,
        motivation : false,
        portfolio  : false,
        fb_link    : false,
        ig_link    : false,
        bonus      : false,
    };

    function make_file_request(type, file) {
        let formdata = new FormData();
        formdata.append('file',     file);
        formdata.append('fileType', type);

        return axios({
            method: 'POST',
            url   : '/campusUploadFile',
            headers: { 'Content-Type': 'multipart/form-data' },
            data  : formdata
        }).then(res => console.log(res), err => {
            console.log(err)
        });
    }

    function file_generator(files) {
        let retval = [];
        for (i=0;i<files.length;i++) {
            if (files[i]?.resume) {
                retval.push(make_file_request('resume', files[i].resume));
            }

            if (files[i]?.portfolio) {
                retval.push(make_file_request('portfolio', files[i].portfolio));
            }
        }
        return retval;
    }

    const vue = new Vue({
        el: '#app',
        data: {
            submited: false,
            data: {
                school: '',
                team_name: '',
                team_intro: '',
                members:[
                ]
            },
            errors: {
                school    : false,
                team_name : false,
                team_intro: false,
                members:[]
            },
            response: {
                success: false,
                message: 'no message'
            },
            files: []
        },
        mounted() {
            this.add_member();
            dialog_model = new bootstrap.Modal(
                document.getElementById('dialog'), {
                    backdrop: 'static'
                }
            );
        },
        methods: {
            elid(name, index) {
                return name + '_' + index;
            },
            add_member() {
                if (this.data.members.length < 3) {
                    this.data.members.push(
                        JSON.parse(JSON.stringify(def_member))
                    );
                    this.files.push({
                        'resume': null,
                        'portfolio': null,
                    });
                    this.errors.members.push(
                        JSON.parse(JSON.stringify(err_member))
                    );
                }
            },
            rm_member(index) {
                if (confirm('確定移除此隊員?')) {
                    this.data.members.splice(index, 1);
                    this.errors.members.splice(index, 1);
                    this.files.splice(index, 1);
                }
            },
            set_member_file(e, index) {
                let name = e.target.id.replace(/_[0-9]$/, '');
                let file = e.target.files[0];
                let time = moment().format('YYYYMMDDhhmmss');

                if (file && file.type == 'application/pdf') {
                    let blob = file.slice(0, file.size, file.type); 
                    nfile = new File([blob], `${time}_${file.name}`, {type: file.type});

                    this.files[index][name] = nfile;
                    this.data.members[index][name] = nfile.name;
                } else {
                    this.files[index][name] = null;
                    this.data.members[index][name] = null;
                }
            },
            check_form() {
                try {
                    if (this.data.members.length > 1 && ! this.data.team_intro) {
                        this.errors.team_intro = true;
                        throw 'form input error.';
                    } else {
                        this.errors.team_intro = false;
                    }

                    // required
                    ['school', 'team_name'].forEach((name) => {
                        if (this.data[name]) {
                            this.errors[name] = false;
                        } else {
                            this.errors[name] = true;
                            throw 'form input error.';
                        }
                    })
                    this.data.members.forEach((m, i) => {
                        [
                            'name',
                            'dept',
                            'grade',
                            'self_intro',
                            'resume',
                        ].forEach(name => {
                            if (m[name]) {
                                this.errors.members[i][name] = false;
                            } else {
                                this.errors.members[i][name] = true;
                                throw 'form input error.';
                            }
                        });

                        if (m.mobile.match(/^09\d{8}$/)){
                            this.errors.members[i].mobile = false;
                        } else {
                            this.errors.members[i].mobile = true;
                            throw 'form input error.';
                        }

                        if (m.email.match(/^\w+((-\w+)|(\.\w+)|(\+\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/)){
                            this.errors.members[i].email = false;
                        } else {
                            this.errors.members[i].email = true;
                            throw 'form input error.';
                        }
                    });
                    return true;

                } catch (e) {
                    console.log(e);
                    return false;
                }
            },
            submit() {
                if (this.check_form()) {
                    var self = this;
                    self.submited = true;

                    axios.all(file_generator(this.files)).then((resp) => {
                        axios({
                            method: 'POST',
                            url: '/campusSignup',
                            responseType: 'json',
                            data: {
                                data: encodeURIComponent(
                                    btoa(encodeURIComponent(JSON.stringify(this.data)))
                                )
                            }
                        }).then(resp => {
                            if (resp.data?.message) {
                                self.response.message = resp.data.message;
                            }
                            self.response.success = resp.data.success;
                            if (! resp.data.success) {
                                self.submited = false;
                            }
                            dialog_model.show();
                        });
                    });
                }
            }
        }
    });
</script>
@endsection