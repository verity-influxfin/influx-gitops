//vue router
import routers from './router/router';

$(() => {
    const login = new Vue({
        el: '#login',
        delimiters: ['${', '}'],
        data: {
            account: '',
            password: '',
            message: ''
        },
        methods: {
            login() {
                let account = this.account;
                let password = this.password;

                axios.post('baklogin', { account, password }).then((res) => {
                    location.reload();
                }).catch((error) => {
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
                        this.message = e.responseJSON.join('、');
                    }
                });
            }
        }
    });

    const router = new VueRouter({
        routes: routers
    });

    router.beforeEach((to, from, next) => {
        if (to.path === "/") {
            next('/index');
        } else {
            next();
        }
    });

    const admin = new Vue({
        el: '#web_admin',
        router,
        data: {
            islogin: null
        },
        methods: {
            logout() {
                axios.post('baklogout').then((res) => {
                    location.reload();
                })
            }
        }
    });
});