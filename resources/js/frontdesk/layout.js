//vuex store
import state from './store/state';
import getters from './store/getters';
import actions from './store/actions';
import mutations from './store/mutations';
//vue router
import routers from './router/router';

$(() => {
    const sessionStoragePlugin = store => {
        store.subscribe((mutation, { userData }) => {
            if (mutation.type === "mutationUserData") {
                sessionStorage.setItem("flag", Object.keys(userData).length !== 0 ? "login" : "logout");
                sessionStorage.setItem("loginTime", Object.keys(userData).length !== 0 ? new Date().getTime() : 0);
                sessionStorage.setItem("userData", JSON.stringify(userData));
            }
        });
    };

    const timeLineMax = new TimelineMax({ paused: true, reversed: true });

    const router = new VueRouter({
        routes: routers
    });

    router.beforeEach((to, from, next) => {
        if (to.path === "/") {
            next('/index');
        } else {
            $(".page-header").show();
            $(".page-footer").show();
            $(".back-top").show();

            $(window).scrollTop(0);
            next();
        }
    });

    const store = new Vuex.Store({
        state,
        getters,
        actions,
        mutations,
        plugins: [sessionStoragePlugin]
    });

    const vue = new Vue({
        el: '#web_index',
        store,
        delimiters: ['${', '}'],
        router,
        data: {
            menuList: [],
            infoList: [],
            actionList: [],
            isCompany: false,
            isRememberAccount: $cookies.get('account') ? true : false,
            isReset: false,
            IsSended: false,
            businessNum: '',
            account: '',
            password: '',
            phone: '',
            newPassword: '',
            confirmPassword: '',
            code: '',
            message: '',
            pwdMessage: '',
            flag: sessionStorage.length !== 0 ? sessionStorage.getItem("flag") : '',
            userData: sessionStorage.length !== 0 ? JSON.parse(sessionStorage.getItem("userData")) : {},
            timer: null,
            counter: 180,
            loginTime: 0,
            currentTime: 0,
            altered: false
        },
        created() {
            this.account = $cookies.get('account') ? $cookies.get('account') : '';
            this.businessNum = $cookies.get('businessNum') ? $cookies.get('businessNum') : '';
            this.getListData();
        },
        mounted() {
            this.createFooterSlick();
            timeLineMax.to(this.$refs.afc_popup, { y: -210 });
            AOS.init();
        },
        watch: {
            '$store.state.userData'() {
                this.flag = localStorage.getItem("flag");
                this.userData = JSON.parse(localStorage.getItem("userData"));
            },
            phone() {
                this.phone = this.phone.replace(/[^\d]/g, '');
            },
            businessNum() {
                this.businessNum = this.businessNum.replace(/[^\d]/g, '');
            },
            account() {
                this.account = this.account.replace(/[^\d]/g, '');
            }
        },
        methods: {
            getListData() {
                axios.post('getListData')
                    .then((res) => {
                        this.menuList = res.data.menuList;
                        this.infoList = res.data.infoList;
                        this.actionList = res.data.actionList;
                    })
                    .catch((error) => {
                        console.error('getListData 發生錯誤，請稍後再試');
                    });
            },
            createFooterSlick() {
                $(this.$refs.footer_slick).slick({
                    infinite: true,
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    autoplay: true,
                    prevArrow: '<i></i>',
                    nextArrow: '<i></i>',
                    responsive: [
                        {
                            breakpoint: 1023,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
            },
            display() {
                if (timeLineMax.reversed()) {
                    timeLineMax.play();
                } else {
                    timeLineMax.reverse();
                }
            },
            backtotop() {
                $(window).scrollTop('0');
            },
            openLoginModal(message) {
                this.message = message;
                $(this.$refs.loginForm).modal("show");
            },
            switchTag(evt) {
                if (!$(evt.target).hasClass('checked')) {
                    this.isCompany = !this.isCompany;
                }
            },
            switchForm() {
                clearInterval(this.timer);
                this.counter = 180;
                this.IsSended = false;
                this.isReset = !this.isReset;
            },
            doLogin() {
                if (this.isRememberAccount) {
                    $cookies.set('account', this.account);
                    $cookies.set('businessNum', this.businessNum);
                } else {
                    $cookies.remove('account');
                    $cookies.remove('businessNum');
                }

                let phone = this.account;
                let password = this.password;

                let params = { phone, password, 'investor': 0 };

                if (this.isCompany) {
                    let tax_id = this.businessNum;
                    Object.assign(params, { tax_id });
                }

                axios.post('doLogin', params)
                    .then((res) => {
                        this.$store.commit('mutationUserData', res.data);
                        if (this.$router.history.pending) {
                            $(this.$refs.loginForm).modal("hide");
                            this.$router.replace(this.$router.history.pending.path);
                        }

                        location.reload();
                    })
                    .catch((error) => {
                        let errorsData = error.response.data;
                        if (errorsData.message) {
                            let messages = [];
                            $.each(errorsData.errors, (key, item) => {
                                item.forEach((message, k) => {
                                    messages.push(message);
                                });
                            });
                            this.message = messages.join('、');
                        } else {
                            this.message = `${this.$store.state.loginErrorCode[errorsData.error]}
                                                     ${errorsData.data ? `剩餘錯誤次數(${errorsData.data.remind_count})` : ''}`;
                        }
                    });
            },
            logout() {
                axios.post('logout').then((res) => {
                    this.$store.commit('mutationUserData', {});
                    location.reload();
                });
            },
            setAccount() {
                this.isRememberAccount = !this.isRememberAccount;
                if (this.isRememberAccount) {
                    $cookies.set('account', this.account);
                } else {
                    $cookies.remove('account');
                }
            },
            getCaptcha(type) {
                let phone = this.phone;

                if (!phone) {
                    this.pwdMessage = '請輸入手機';
                    return;
                }

                this.counter = 180;

                axios.post('getCaptcha', { phone, type })
                    .then((res) => {
                        this.IsSended = true;
                        this.timer = setInterval(() => { $this.reciprocal() }, 1000);
                    })
                    .catch((error) => {
                        let errorsData = error.response.data;
                        this.pwdMessage = `${$this.$store.state.smsErrorCode[errorsData.error]}`;
                    });
            },
            submit() {
                let phone = this.phone;
                let new_password = this.newPassword;
                let new_password_confirmation = this.confirmPassword;
                let code = this.code;

                axios.post('resetPassword', { phone, new_password, new_password_confirmation, code })
                    .then((res) => {
                        alert('修改成功，請以新密碼登入');
                        location.reload();
                    })
                    .catch((error) => {
                        let errorsData = error.response.data;

                        if (errorsData.message) {
                            let messages = [];
                            $.each(errorsData.errors, (key, item) => {
                                item.forEach((message, k) => {
                                    messages.push(message);
                                });
                            });
                            this.pwdMessage = messages.join('、');
                        } else {
                            this.pwdMessage = `${$this.$store.state.pwdErrorCode[errorsData.error]}`;
                        }
                    });
            },
            reciprocal() {
                this.counter--;
                if (this.counter === 0) {
                    clearInterval(this.timer);
                    this.timer = null;
                    alert('驗證碼失效，請重新申請');
                    location.reload();
                }
            },
            clicked() {
                if (sessionStorage.getItem('flag') === 'login') {
                    this.currentTime = new Date().getTime();
                    let passTime = this.currentTime - sessionStorage.getItem('loginTime');

                    if (passTime >= 30 * 60 * 1000 && !this.altered) {
                        this.altered = true;
                        this.logout();
                        alert('連線逾時，請重新登入');
                    }
                }
            }
        }
    });

    $('.back-top').fadeOut();
    $(document).scroll(function () {
        var y = $(this).scrollTop();
        if (y > 800) {
            $('.back-top').fadeIn();
        } else {
            $('.back-top').fadeOut();
        }
    });
});