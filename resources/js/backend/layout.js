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
                let $this = this;
                let account = $this.account;
                let password = $this.password;

                $.ajax({
                    url: 'baklogin',
                    type: 'POST',
                    dataType: 'json',
                    data: { account, password },
                    success(data) {
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
                            $this.message = messages.join('、');
                        } else {
                            $this.message = e.responseJSON.join('、')
                        }
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
        }else{
            next();
        }
    });
    
    const admin = new Vue({
        el: '#web_admin',
        router,
        data:{
            islogin:null
        },
        methods: {
            logout() {
                $.ajax({
                    url: 'baklogout',
                    type: 'POST',
                    dataType: 'json',
                    success(data) {
                        location.reload();
                    }
                });
            }
        }
    });
});