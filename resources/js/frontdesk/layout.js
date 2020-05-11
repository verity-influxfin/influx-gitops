//vuex store
import state from './store/state';
import getters from './store/getters';
import actions from './store/actions';
import mutations from './store/mutations';
//vue router
import routers from './router/router';

$(() => {
    const localStoragePlugin = store => {
        store.subscribe((mutation, { userData }) => {
            if (mutation.type === "mutationUserData") {
                localStorage.clear();
                localStorage.setItem("flag", Object.keys(userData).length !== 0 ? "login" : "logout");
                localStorage.setItem("userData", JSON.stringify(userData));
            }
        });
    };

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    const timeLineMax = new TimelineMax({ paused: true, reversed: true });

    const router = new VueRouter({
        routes: routers
    });

    router.beforeEach((to, from, next) => {
        if (to.path === "/") {
            next('/index');
        } else if (to.path === "/myinvestment") {
            if (localStorage.getItem("flag") === 'logout') {
                if (from.path === "/") {
                    next('/index');
                } else {
                    vue.openLoginModal('請登入');
                }
            } else {
                next();
            }
        } else {
            $(".page-header").show();
            $(".page-footer").show();
            $(".back-top").show();
            $(".afc_popup").show();

            $(window).scrollTop(0);
            next();
        }
    });

    const store = new Vuex.Store({
        state,
        getters,
        actions,
        mutations,
        plugins: [localStoragePlugin]
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
            flag: localStorage.getItem("flag"),
            userName: '',
            timer: null,
            counter: 180
        },
        created() {
            this.account = $cookies.get('account') ? $cookies.get('account') : '';
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
                this.userName = JSON.parse(localStorage.getItem("userData")).name;
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
                let $this = this;

                $.ajax({
                    url: 'getListData',
                    type: 'POST',
                    dataType: 'json',
                    success(data) {
                        $this.menuList = data.menuList;
                        $this.infoList = data.infoList;
                        $this.actionList = data.actionList;
                    }
                });
            },
            createFooterSlick(){
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
                let $this = this;

                if ($this.isRememberAccount) {
                    $cookies.set('account', $this.account);
                } else {
                    $cookies.remove('account');
                }

                let phone = $this.account;
                let password = $this.password;

                let params = { phone, password };

                if ($this.isCompany) {
                    let tax_id = $this.businessNum;
                    Object.assign(params, { tax_id });
                }

                $.ajax({
                    url: 'doLogin',
                    type: 'POST',
                    dataType: 'json',
                    data: params,
                    success(data) {
                        $this.$store.commit('mutationUserData', data);
                        if ($this.$router.history.pending) {
                            $($this.$refs.loginForm).modal("hide");
                            $this.$router.replace($this.$router.history.pending.path);
                        } else {
                            location.reload();
                        }
                    },
                    error(e) {
                        if (e.responseJSON.message) {
                            let messages = [];
                            $.each(e.responseJSON.errors, (key, item) => {
                                item.forEach((message, k) => {
                                    messages.push(message);
                                });
                            });
                            $this.pwdMessage = messages.join('、');
                        } else {
                            $this.message = `${$this.$store.state.loginErrorCode[e.responseJSON.error]}
                                         ${e.responseJSON.data ? `剩餘錯誤次數(${e.responseJSON.data.remind_count})` : ''}`;
                        }
                    }
                });
            },
            logout() {
                let $this = this;
                $.ajax({
                    url: 'logout',
                    type: 'POST',
                    dataType: 'json',
                    success() {
                        $this.$store.commit('mutationUserData', {});
                        location.reload();
                    }
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
                let $this = this;
                let phone = $this.phone;

                if (!phone) {
                    $this.pwdMessage = '請輸入手機';
                    return;
                }

                $this.counter = 180;

                $.ajax({
                    url: 'getCaptcha',
                    type: 'POST',
                    dataType: 'json',
                    data: { phone, type },
                    success() {
                        $this.IsSended = true;
                        $this.timer = setInterval(() => { $this.reciprocal() }, 1000);
                    },
                    error(e) {
                        $this.pwdMessage = `${$this.$store.state.smsErrorCode[e.responseJSON.error]}`;
                    }
                });
            },
            submit() {
                let $this = this;
                let phone = $this.phone;
                let new_password = $this.newPassword;
                let new_password_confirmation = $this.confirmPassword;
                let code = $this.code;

                $.ajax({
                    url: 'resetPassword',
                    type: 'POST',
                    dataType: 'json',
                    data: { phone, new_password, new_password_confirmation, code },
                    success() {
                        alert('修改成功，請以新密碼登入');
                        location.reload();
                    },
                    error(e) {
                        if (e.responseJSON.message) {
                            let messages = [];
                            $.each(e.responseJSON.errors, (key, item) => {
                                item.forEach((message, k) => {
                                    messages.push(message);
                                });
                            });
                            $this.pwdMessage = messages.join('、');
                        } else {
                            $this.pwdMessage = `${$this.$store.state.pwdErrorCode[e.responseJSON.error]}`;
                        }
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
            }
        }
    });

    $('.back-top').fadeOut();
    $(document).scroll(function() {
        var y = $(this).scrollTop();
        if (y > 800) {
          $('.back-top').fadeIn();
        } else {
          $('.back-top').fadeOut();
        }
      });
});














