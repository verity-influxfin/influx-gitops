//vuex store
import state from './frontdesk/store/state';
import getters from './frontdesk/store/getters';
import actions from './frontdesk/store/actions';
import mutations from './frontdesk/store/mutations';

$(() => {
    const store = new Vuex.Store({
        state,
        getters,
        actions,
        mutations
    });

    const vue = new Vue({
        el: '#event_wrapper',
        store,
        data: {
            account: "",
            password: "",
            confirmPassword: "",
            code: "",
            message: "",
            registerMessage: "",
            alertText: "",
            termsTitle: "",
            termsContent: "",
            wheelDeg: 0,
            prizeNumber: 8,
            weights: 0,
            investor: 0,
            counter: 180,
            dataCache: [],
            awards: {},
            userData: {},
            rolling: false,
            disable: true,
            isRegister: false,
            isSended: false,
            isAgree: false,
            timer: null
        },
        created() {
            this.getData();
            $("title").text(`幸運轉盤 - inFlux普匯金融科技`);
        },
        mounted() {
            $(".page-header").hide();
            $(".page-footer").hide();
            $(".back-top").hide();
            $(".afc_popup").hide();
        },
        computed: {
            prizeList() {
                return this.dataCache.slice(0, this.prizeNumber);
            }
        },
        watch: {
            phone(newdata) {
                this.phone = newdata.replace(/[^\d]/g, "");
            },
            code(newdata) {
                this.code = newdata.replace(/[^\d]/g, "");
            }
        },
        methods: {
            async getData() {
                try {
                    let res = await axios.get("getRotationData");

                    res.data.forEach((item, index) => {
                        Vue.set(this.dataCache, index, item);
                        this.weights += item.weights;
                    });

                    gsap.to(this.$refs.wheel_content, 0.5, {
                        delay: 1,
                        rotation: -45,
                        opacity: 1,
                        translateX: 0,
                        translateY: 0,
                        ease: "back.out(1.7)"
                    });

                } catch (error) {
                    console.error('getRotationData 發生錯誤');
                }

                this.$nextTick(() => {
                    gsap.to(this.$refs.login, 2, {
                        delay: 1.5,
                        opacity: 1,
                        rotation: 0
                    });

                    gsap.to(this.$refs.lottery, 2, {
                        delay: 1.5,
                        opacity: 1,
                        rotation: 0
                    });

                    gsap.from(this.$refs.left_info, 2, {
                        delay: 2,
                        opacity: 0
                    });

                    gsap.from(this.$refs.right_info, 2, {
                        delay: 2,
                        opacity: 0
                    });
                });
            },
            async rotate() {
                if (this.rolling) {
                    return;
                }

                let { userData } = this;

                let re = await axios.post("ratate", userData);
                this.roll(re.data);
            },
            roll(key) {
                this.rolling = true;
                const { wheelDeg, prizeList } = this;
                this.wheelDeg = wheelDeg - (wheelDeg % 360) + 6 * 360 + (360 - (360 / prizeList.length) * key);
                setTimeout(() => {
                    this.rolling = false;
                    this.showAwards(prizeList[key]);
                }, 3000);
            },
            transformHandler(index, location) {
                let len = this.dataCache.length;
                let rotate = 360 / len;
                let rotateFrom = -rotate / 2;
                let skewY = rotate - 90;
                if (location === "prize") {
                    return `rotate(${rotateFrom + index * rotate}deg) skewY(${skewY}deg)`;
                }
                if (location === "content") {
                    return `skewY(${90 - rotate}deg) rotate(${rotate / 2}deg)`;
                }
            },
            async check() {
                if (Object.keys(this.userData).length === 0) {
                    this.alertText = "請登入才能抽獎喔";
                } else {
                    let res = await axios.post('checkStatus');
                    this.alertText = res.data.message;
                    this.disable = res.data.status;
                }

                if (this.alertText) {
                    alert(this.alertText);
                }
            },
            openLoginModal() {
                $(this.$refs.loginForm).modal("show");
            },
            doLogin() {
                let phone = this.account;
                let password = this.password;
                let investor = this.investor;

                let params = { phone, password, investor };

                axios.post('doLogin', params)
                    .then((res) => {
                        this.userData = res.data;
                        $(this.$refs.loginForm).modal("hide");
                        this.check();
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
            getTerms(termsType) {
                let $this = this;

                axios
                    .post("getTerms", { termsType })
                    .then(res => {
                        let { content, name } = res.data.data;
                        this.termsContent = content;
                        this.termsTitle = name;

                        $(this.$refs.termsForm).modal("show");
                    })
                    .catch(error => {
                        console.log("getTerms 發生錯誤，請稍後再試");
                    });
            },
            getCaptcha(type) {
                let phone = this.account;

                if (!phone) {
                    this.registerMessage = "請輸入手機";
                    return;
                }

                this.counter = 180;

                axios
                    .post("getCaptcha", { phone, type })
                    .then(res => {
                        this.isSended = true;
                        this.registerMessage = '';
                        this.timer = setInterval(() => {
                            this.reciprocal();
                        }, 1000);
                    })
                    .catch(error => {
                        let errorsData = error.response.data;
                        this.registerMessage = `${
                            this.$store.state.smsErrorCode[errorsData.error]
                            }`;
                    });
            },
            doRegister() {
                let phone = this.account;
                let password = this.password;
                let password_confirmation = this.confirmPassword;
                let code = this.code;

                axios
                    .post("doRegister", { phone, password, password_confirmation, code })
                    .then(res => {
                        axios.post('doLogin', { phone, password, investor: this.investor })
                            .then((res) => {
                                this.userData = res.data;
                                $(this.$refs.loginForm).modal("hide");
                                this.check();
                            })
                    })
                    .catch(error => {
                        let errorsData = error.response.data;
                        if (errorsData.message) {
                            let messages = [];
                            $.each(errorsData.errors, (key, item) => {
                                item.forEach((message, k) => {
                                    messages.push(message);
                                });
                            });
                            this.message = messages.join("、");
                        } else {
                            this.message = `${
                                this.$store.state.pwdErrorCode[errorsData.error]
                                }`;
                        }
                    });
            },
            reciprocal() {
                this.counter--;
                if (this.counter === 0) {
                    clearInterval(this.timer);
                    this.timer = null;
                    alert("驗證碼失效，請重新申請");
                    location.reload();
                }
            },
            showAwards(awards) {
                this.awards = awards;
                this.disable = true;
                gsap.to(this.$refs.awards_block, 0.5, { display: "block" });
            },
            hide() {
                $(this.$refs.awards_block).hide();
            }
        }
    });
});