import engineerLoan from "../pages/engineerLoan";
import transfer from "../pages/transfer";
import company from "../pages/company";
import news from "../pages/news";
import ntu from "../pages/ntu"
import charitableStatus from "../pages/charitableStatus"
import blog from "../pages/blog";
import vlog from "../pages/vlog";
import videoPage from '../pages/videoPage';
import articlePage from '../pages/articlePage';
import userTerms from '../pages/userTerms';
import privacyTerms from '../pages/privacyTerms';
import loanerTerms from '../pages/loanerTerms';
import lenderTerms from '../pages/lenderTerms';
import transferTerms from '../pages/transferTerms';
import recruiting from '../pages/recruiting';
import campusPartner from '../pages/campusPartner';
import clubCooperation from '../pages/clubCooperation';
import firmCooperation from '../pages/firmCooperation';
import companyCooperation from '../pages/companyCooperation';
import register from '../pages/register';
import myInvestment from '../pages/myInvestment';
import promoteCode from '../pages/promoteCode.vue'
import debt from '../pages/debt';
import closedcase from '../pages/closedcase';
import detail from '../pages/detail';
import myLoan from '../pages/myLoan';
import notification from '../pages/notification';
import myrepayment from '../pages/myrepayment';
import feedback from '../pages/feedback';
import event from '../pages/event';
import scsbank from '../pages/scsbank';
import skbank from '../pages/skbank';
import obank from '../pages/obank';

import index from "../pages/alesis-index";
import borrow from '../pages/alesis-borrow';
import invest from '../pages/alesis-invest';
import faq from '../pages/alesis-faq';
import risk from '../pages/alesis-risk';
import projects from '../pages/alesis-projects';
import workLoan from '../pages/workLoanV2';
import collegeLoan from "../pages/collegeLoanV2";
import investReport from "../pages/investReport"
import search from "../pages/search.vue";
import promoteCodeIntro from '../pages/promoteCodeIntro.vue'
import businessIndex from '../pages/BusinessLoan/index'
import businessLoan from '../pages/BusinessLoan/businessLoan'
import businessEnd from '../pages/BusinessLoan/end'
import smeIndex from '../pages/BusinessLoan/smeLoan'
import smeApply from '../pages/BusinessLoan/sme/apply'
import smeConsult from '../pages/BusinessLoan/sme/consult'
import campus2022 from '../pages/2022_campus_ambassador/index'
import campus2022Apply from '../pages/2022_campus_ambassador/applyTemplate'
import campus2022Group from '../pages/2022_campus_ambassador/group'
import campus2022Result from '../pages/2022_campus_ambassador/result'
import campus2022Personal from '../pages/2022_campus_ambassador/personal'

let routers = [
    { path: '*', redirect: '/index' },
    { path: '/index', component: index },
    { path: '/borrow', component: borrow },
    { path: '/workLoan', component: workLoan },
    { path: '/collegeLoan', component: collegeLoan },
    { path: '/faq', component: faq },
    { path: '/risk', component: risk },
    { path: '/projects', component: projects },
    { path: '/promote-code-intro', component: promoteCodeIntro },
    // redirect for google搜尋
    { path: '/freshGraduateLoan', redirect: '/workLoan' },
    { path: '/engineerLoan', component: engineerLoan },
    { path: '/investment', component: invest },
    { path: '/transfer', component: transfer },
    { path: '/company', component: company },
    { path: '/news', component: news },
    { path: '/blog', component: blog },
    { path: '/vlog', component: vlog },
    { path: '/invest', component: invest },
    { path: '/videopage', component: videoPage },
    { path: '/articlepage', component: articlePage },
    { path: '/userTerms', component: userTerms },
    // redirect for facebook
    { path: '/privacy-policy', redirect: '/privacyTerms' },
    { path: '/privacyTerms', component: privacyTerms },
    { path: '/loanerTerms', component: loanerTerms },
    { path: '/lenderTerms', component: lenderTerms },
    { path: '/transferTerms', component: transferTerms },
    { path: '/register', component: register },
    {
        path: '/myloan', component: myLoan, children: [
            { path: '/loannotification', component: notification, name: 'loan-notification' },
            { path: '/myrepayment', component: myrepayment }
        ]
    },
    { path: '/promoteCode', component: promoteCode},
    {
        path: '/business-loan',
        component: businessIndex,
        children: [
            {
                path: '',
                component: businessLoan
            },
            {
                path: 'sme',
                component: businessIndex,
                children: [
                    {
                        path: '',
                        component: smeIndex
                    },
                    {
                        name: 'sme-apply',
                        path: 'apply',
                        component: smeApply
                    },
                    {
                        name: 'sme-consult',
                        path: 'consult',
                        component: smeConsult
                    }
                ],

            },
            {
                name: 'end',
                path: 'end',
                props: true,
                component: businessEnd
            }
        ]
    },
    {
        path: '/myinvestment', component: myInvestment, children: [
            { path: '/investnotification', component: notification, name: 'invest-notification' },
            { path: '/debt', component: debt },
            { path: '/closedcase', component: closedcase },
            { path: '/detail', component: detail },
        ]
    },
    { path: '/invest-report', component: investReport },
    { path: '/recruiting', component: recruiting },
    { path: '/campuspartner', component: campusPartner },
    { path: '/clubcooperation', component: clubCooperation },
    { path: '/firmcooperation', component: firmCooperation },
    { path: '/companycooperation', component: companyCooperation },
    { path: '/feedback', component: feedback },
    { path: '/event', component: event },
    { path: '/scsbank', component: scsbank },
    { path: '/skbank', component: skbank },
    { path: '/obank', component: obank },
    {path:'/charitable',component:ntu},
    { path: '/charitable-status', component: charitableStatus},
    { path: '/search',name:'search', component: search },
    { path: '/2022-campus-ambassador',name:'campus2022', component: campus2022 },
    { path: '/2022-campus-ambassador/result', name: 'campus2022Result', component: campus2022Result },
    {
      path: '/2022-campus-ambassador', component: campus2022Apply, children: [
        { path: 'group', name: 'campus2022Group', component: campus2022Group },
        { path: 'personal',name:'campus2022', component: campus2022Personal },
      ]
    },
];

export default routers;
