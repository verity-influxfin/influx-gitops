import index from "./index.js";
import collegeLoan from "./collegeLoan.js";
import freshGraduateLoan from "./freshGraduateLoan.js";
import engineerLoan from "./engineerLoan.js";
import invest from "./invest.js";
import transfer from "./transfer.js";

$(function(){
    const timeLineMax = new TimelineMax({paused: true, reversed: true}); 

    const router = new VueRouter({
        'routes':[
            { path: '/index', component:index },
            { path: '/collegeLoan', component: collegeLoan },
            { path: '/freshGraduateLoan', component: freshGraduateLoan },
            { path: '/mobileLoan', component: { template: '<div>Mobile Loan</div>' } },
            { path: '/engineerLoan', component: engineerLoan },
            { path: '/invest', component: invest },
            { path: '/transfer', component: transfer },
            { path: '/company', component: { template: '<div>company</div>' } },
            { path: '/news', component: { template: '<div>news</div>' } },
            { path: '/blog', component: { template: '<div>blog</div>' } },
            { path: '/vlog', component: { template: '<div>vlog</div>' } },
            { path: '/qa', component: { template: '<div>qa</div>' } },
        ]
    });
    
    router.replace({path:'/transfer'});

    const vue = new Vue({
        el:'#web_index',
        delimiters: ['${', '}'],
        router,
        data:{
            menuList:[
                {
                    'title':'我要借款',
                    'subMenu':[
                        {'name':'上班族貸款','href':'/freshGraduateLoan','isActive':true},
                        {'name':'學生貸款','href':'/collegeLoan','isActive':true},
                        {'name':'資訊工程師專案','href':'/engineerLoan','isActive':true},
                        {'name':'外匯車貸','href':'','isActive':false},
                        {'name':'新創企業主貸','href':'','isActive':false}
                    ]
                },
                {
                    'title':'我要投資',
                    'subMenu':[
                        {'name':'債權投資','href':'/invest','isActive':true},
                        {'name':'債權轉讓','href':'/transfer','isActive':true}
                    ]
                },
                {
                    'title':'分期付款超市',
                    'subMenu':[
                        {'name':'手機分期','href':'/mobileLoan','isActive':true}
                    ]
                },
                {
                    'title':'關於我們',
                    'subMenu':[
                        {'name':'公司介紹','href':'/company','isActive':true},
                        {'name':'最新消息','href':'/news','isActive':true},
                    ]
                },
                {
                    'title':'小學堂金融科技',
                    'subMenu':[
                        {'name':'小學堂','href':'/blog','isActive':true},
                        {'name':'小學影音','href':'/vlog','isActive':true},
                    ]
                },
                {
                    'title':'常見問題',
                    'subMenu':[]
                },
            ],
            infoList:['學生貸款','債權投資','債權轉讓','公司介紹','活動資訊','inFlux 小學堂','會員專訪'],
            actionList:['徵才服務','校園大使','社團合作','商行合作','企業合作','使用者條款','隱私條款政策','借款人服務條款']
        },
        created(){
        },
        mounted(){
            timeLineMax.to(this.$refs.afc_popup, {y: -210});
            AOS.init();
        },
        watch:{
        },
        methods:{
            display(){
                if (timeLineMax.reversed()) {
                    timeLineMax.play();
                } else {
                    timeLineMax.reverse();
                }
            }
        }
    });
});














