//vuex store
import state from './store/state';
import getters from './store/getters';
import actions from './store/actions';
import mutations from './store/mutations';
//vue router
import routers from './router/router';

$(function () {
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    const timeLineMax = new TimelineMax({ paused: true, reversed: true });

    const router = new VueRouter({
        routes: routers
    });

    router.beforeEach((to, from, next) => {
        if(to.path ==="/"){
            next('/index');
        }else{
            $(window).scrollTop('0');
            next();
        }
    })

    const store = new Vuex.Store({
        state,
        getters,
        actions,
        mutations
    });

    const vue = new Vue({
        el: '#web_index',
        store,
        delimiters: ['${', '}'],
        router,
        data: {
            menuList: [],
            infoList: [],
            actionList: []
        },
        created() {
            this.getListData();
        },
        mounted() {
            timeLineMax.to(this.$refs.afc_popup, { y: -210 });
            AOS.init();
        },
        methods: {
            getListData(){
                let $this = this;

                $.ajax({
                    url:'getListData',
                    type:'POST',
                    dataType:'json',
                    success(data){
                        $this.menuList = data.menuList;
                        $this.infoList = data.infoList;
                        $this.actionList = data.actionList;
                    }
                });
            },
            display() {
                if (timeLineMax.reversed()) {
                    timeLineMax.play();
                } else {
                    timeLineMax.reverse();
                }
            }
        }
    });
});














