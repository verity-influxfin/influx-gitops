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
                sessionStorage.setItem("userData", JSON.stringify(userData));
            }
        });
    };

    
    const store = new Vuex.Store({
        state,
        getters,
        actions,
        mutations,
        plugins: [sessionStoragePlugin]
    });

    const login = new Vue({
        el: '#login',
        store,
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
                    this.$store.commit('mutationUserData', res.data);
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
                        this.message = errorsData.join('、');
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
        store,
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