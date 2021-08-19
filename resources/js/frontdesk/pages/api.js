import superagent from "superagent";
import dataAlesisCompanyIntroductions from "./../data/api/alesisCompanyIntroductions";
import dataAlesisIndexHumans from "./../data/api/alesisIndexHumans";
import dataAlesisIndexBanners from "./../data/api/alesisIndexBanners";
import dataAlesisIndexCounter from "./../data/api/alesisIndexCounter";
import dataAlesisCollegeHumans from "./../data/api/alesisCollegeHumans";
import dataAlesisWorkHumans from "./../data/api/alesisWorkHumans";
import dataAlesisBorrowHumans from "../data/api/alesisBorrowHumans";
import dataAlesisCollegeCounter from "./../data/api/alesisCollegeCounter";
import dataAlesisCollegeCases from "./../data/api/alesisCollegeCases";
import dataAlesisCollegeForm from "./../data/api/alesisCollegeForm";
import dataAlesisWorkForm from "./../data/api/alesisWorkForm";
import dataAlesisProjects from "./../data/api/alesisProjects";

var host = "/"

export const doLogin = async b => (await superagent.post(host + "doLogin").send(b)).body;
export const doRegister = async b => (await superagent.post(host + "doRegister").send(b)).body;
export const resetPassword = async b => (await superagent.post(host + "resetPassword").send(b)).body;
export const getCaptcha = async b => (await superagent.post(host + "getCaptcha").send(b)).body;
export const getCount = async b => (await superagent.post(host + "getCount").send(b)).body;
export const getListData = async b => (await superagent.post(host + "getListData").send(b)).body;
export const getMilestoneData = async b => (await superagent.post(host + "getMilestoneData").send(b)).body;
export const getIndexBanner = async b => (await superagent.post(host + "getIndexBanner").send(b)).body;
export const sendQuestion = async b => (await superagent.post(host + "sendQuestion").send(b)).body;
export const sendBankEvent = async b => (await superagent.post(host + "sendBankEvent").send(b)).body;
export const getExperiencesData = async b => (await superagent.post(host + "getExperiencesData").send(b)).body;
export const getBorrowReport = async b => (await superagent.post(host + "getBorrowReport").send(b)).body;
export const getCase = async b => (await superagent.post(host + "getCase").send(b)).body;

function faker(data) {
    return new Promise(resolve => {
        setTimeout(() => {
            resolve(data);
        }, 500);
    });
}

// alesisCompanyIntroductions 是首頁 - 公司簡介
// * dataAlesisCompanyIntroductions
// * /getMilestoneData
export const alesisCompanyIntroductions = async b => (await axios.post(`${location.origin}/getMilestoneData`, b)).data;
// alesisIndexHumans 是首頁 - 用戶分享區
// * dataAlesisIndexHumans
// * /getExperiencesData
export const alesisIndexHumans = async b => {
    var data = new FormData();
    data.append('category', 'loan');
    return (await axios.post(`${location.origin}/getExperiencesData`, data)).data;
    //return (await faker(dataAlesisIndexHumans))
};
// alesisIndexBanners 是首頁 - Banner
// * dataAlesisIndexBanners
// * /getIndexBanner
export const alesisIndexBanners = async b => (await axios.post(`${location.origin}/getIndexBanner`, b)).data;
// alesisIndexCounter 是首頁 - 累積註冊用戶
// * dataAlesisIndexCounter
// * /getCount
export const alesisIndexCounter = async b => (await axios.get(`${location.origin}/getCount`, b)).data;
// alesisCollegeHumans 是學生貸 - 用戶分享區
// * dataAlesisCollegeHumans
// * /getExperiencesData
export const alesisCollegeHumans = async b => {
    var data = new FormData();
    data.append('category', 'loan');
    data.append('rank', 'student');
    return (await axios.post(`${location.origin}/getExperiencesData`, data)).data
    //return (await faker(dataAlesisCollegeHumans))
};
// alesisWorkHumans 是工作貸 - 用戶分享區
// * dataAlesisWorkHumans
// * /getExperiencesData
export const alesisWorkHumans = async b => {
    var data = new FormData();
    data.append('category', 'loan');
    data.append('rank', 'officeWorker');
    return (await axios.post(`${location.origin}/getExperiencesData`, data)).data
    //return (await faker(dataAlesisWorkHumans))
};
// alesisBorrowHumans 是我要借款 - 用戶分享區
// * dataAlesisBorrowHumans
// * /getExperiencesData
export const alesisBorrowHumans = async b => {
    var data = new FormData();
    data.append('category', 'loan');
    return (await axios.post(`${location.origin}/getExperiencesData`, data)).data
    //return (await faker(dataAlesisBorrowHumans))
};
// alesisProjects 是案件總覽 - 目前案件/已成交案件
// * dataAlesisProjects
// * /getCase
export const alesisProjects = async b => {
    var data = new FormData();
    data.append('product_id', b.product_id);
    data.append('status', b.status);
    data.append('orderby', b.orderby);
    data.append('sort', b.sort);
    return (await axios.post(`${location.origin}/getCase`, data)).data
    //return (await faker(dataAlesisProjects))
};