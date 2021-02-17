//vue router
import routers from './router/router';

import "@splidejs/splide/dist/css/themes/splide-default.min.css";

$(() => {
    let now = new Date();
    let startDate = new Date('2021-02-01 00:00:00');
    let endDate = new Date('2021-03-10 00:00:00');
    if (startDate >= now || now > endDate) {
        alert('敬請期待！');
        location.replace('/index');
        return;
    }

    if(localStorage.getItem("flag") != "login"){
        alert('請登入會員');
        location.replace('/index');
    }

    if (window.innerWidth >= 767) {
        alert('請使用行動裝置瀏覽');
        location.replace('/index');
        return;
    }

    const router = new VueRouter({
        routes: routers,
        mode: 'history',
        base: "cardgame"
    });

    router.beforeEach((to, from, next) => {
        if (to.path === "/") {
            gtag("config", "UA-117279688-9", { page_path: '/make' });
            next('/make');
        } else {
            gtag("config", "UA-117279688-9", { page_path: to.path });
            next();
        }
    });

    const vue = new Vue({
        el: '#cardgame',
        router,
        computed: {
            isInvestor() {
                return localStorage.getItem("userData") ? JSON.parse(localStorage.getItem("userData")).investor : "0";
            },
            flag() {
                return localStorage.getItem("flag") ? localStorage.getItem("flag") : '';
            },
            userData() {
                return localStorage.getItem("userData") ? JSON.parse(localStorage.getItem("userData")) : {}
            },
        },
        created() {
        }
    });
})