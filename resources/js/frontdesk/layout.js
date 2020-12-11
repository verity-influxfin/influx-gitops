//vuex store
import state from './store/state';
import getters from './store/getters';
import actions from './store/actions';
import mutations from './store/mutations';
//vue router
import routers from './router/router';
//import Vue from 'vue'
import Vue2TouchEvents from 'vue2-touch-events'

import "@splidejs/splide/dist/css/themes/splide-default.min.css";

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
        routes: routers,
        mode: 'history',
    });

    router.beforeEach((to, from, next) => {
        if (to.path === "/") {
            gtag("config", "UA-117279688-9", { page_path: '/index' });
            next('/index');
        } else {
            gtag("config", "UA-117279688-9", { page_path: to.path });
            $(window).scrollTop(0);
            next();
        }

        if ($('.navbar-toggler').attr('aria-expanded') === 'true') {
            $('.navbar-toggler').click();
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
        router,
        data: {
            menuList: [],
            actionList: [],
            isCompany: false,
            isRememberAccount: $cookies.get('account') ? true : false,
            isReset: false,
            isSended: false,
            isInvestor: sessionStorage.length !== 0 ? JSON.parse(sessionStorage.getItem("userData")).investor : "0",
            altered: false,
            businessNum: '',
            account: '',
            password: '',
            phone: '',
            newPassword: '',
            confirmPassword: '',
            code: '',
            message: '',
            pwdMessage: '',
            investor: '0',
            flag: sessionStorage.length !== 0 ? sessionStorage.getItem("flag") : '',
            userData: sessionStorage.length !== 0 ? JSON.parse(sessionStorage.getItem("userData")) : {},
            timer: null,
            counter: 180,
            loginTime: 0,
            currentTime: 0
        },
        created() {
            this.account = $cookies.get('account') ? $cookies.get('account') : '';
            this.businessNum = $cookies.get('businessNum') ? $cookies.get('businessNum') : '';
            this.getListData();
        },
        mounted() {
            this.$nextTick(() => {
                AOS.init();
            });
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
                axios.post(`${location.origin}/getListData`)
                    .then((res) => {
                        this.menuList = res.data.menuList;
                        this.actionList = res.data.actionList;
                    })
                    .catch((error) => {
                        console.error('getListData 發生錯誤，請稍後再試');
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
                $('html').stop().animate({ scrollTop: 0 }, 1000);
                AOS.refresh();
            },
            openLoginModal() {
                $(this.$refs.loginForm).modal("show");
            },
            hideLoginModal() {
                $(this.$refs.loginForm).modal("hide");
            },
            switchTag(evt) {
                if (!$(evt.target).hasClass('checked')) {
                    this.isCompany = !this.isCompany;
                }
            },
            switchForm() {
                clearInterval(this.timer);
                this.counter = 180;
                this.isSended = false;
                this.isReset = !this.isReset;
            },
            goFeedback() {
                let { userData, $router } = this;

                if (Object.keys(userData).length === 0) {
                    $(this.$refs.loginForm).modal("show");
                    this.$router.history.pending = { path: '/feedback' };
                } else {
                    $router.push("/feedback");
                }
            },
            doLogin() {
                grecaptcha.ready(() => {
                    grecaptcha.execute('6LfQla4ZAAAAAGrpdqaZYkJgo_0Ur0fkZHQEYKa3', { action: 'submit' }).then((token) => {
                        axios.post(`${location.origin}/recaptcha`, { token }).then((res) => {
                            if (this.isRememberAccount) {
                                $cookies.set('account', this.account);
                                $cookies.set('businessNum', this.businessNum);
                            } else {
                                $cookies.remove('account');
                                $cookies.remove('businessNum');
                            }

                            let phone = this.account;
                            let password = this.password;
                            let investor = this.investor;

                            let params = { phone, password, investor };

                            if (this.isCompany) {
                                let tax_id = this.businessNum;
                                Object.assign(params, { tax_id });
                            }

                            axios.post(`${location.origin}/doLogin`, params)
                                .then((res) => {
                                    this.$store.commit('mutationUserData', res.data);
                                    $(this.$refs.loginForm).modal("hide");

                                    if (investor === '1') {
                                        this.$router.push('investnotification');
                                    } else {
                                        this.$router.push('loannotification');
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
                        })
                    });
                });

            },
            logout() {
                axios.post(`${location.origin}/logout`).then((res) => {
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

                axios.post(`${location.origin}/getCaptcha`, { phone, type })
                    .then((res) => {
                        this.isSended = true;
                        this.timer = setInterval(() => { this.reciprocal() }, 1000);
                    })
                    .catch((error) => {
                        let errorsData = error.response.data;
                        this.pwdMessage = `${this.$store.state.smsErrorCode[errorsData.error]}`;
                    });
            },
            submit() {
                let phone = this.phone;
                let new_password = this.newPassword;
                let new_password_confirmation = this.confirmPassword;
                let code = this.code;

                axios.post(`${location.origin}/resetPassword`, { phone, new_password, new_password_confirmation, code })
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
                            this.pwdMessage = `${this.$store.state.pwdErrorCode[errorsData.error]}`;
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

    Vue.use(Vue2TouchEvents)
    
    $('.back-top').fadeOut();
    // let offset = $('.blog-quiklink').offset();

    $(document).scroll(function () {
        AOS.refresh();
        window.dispatchEvent(new Event("resize"));
        var y = $(this).scrollTop();
        if (y > 800) {
            $('.back-top').fadeIn();
        } else {
            $('.back-top').fadeOut();
        }

        // if ($(window).scrollTop() > offset.top) {
        //     $('.blog-quiklink').stop().animate({
        //         marginTop: $(window).scrollTop() + offset.top
        //     });
        // } else {
        //     $('.blog-quiklink').stop().animate({
        //         marginTop: offset.top
        //     });
        // }
    });
});