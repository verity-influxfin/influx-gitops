export default {
    experiences: [],
    knowledge: [],
    video: [],
    interview: [],
    news: [],
    userData: {},
    applyList:{},
    investAccountData:{},
    loginHideOption: false,
    openLogin:false,
    loginErrorCode: {
        302: "會員不存在",
        304: "密碼錯誤",
        312: "密碼長度錯誤",
        200: "參數錯誤",
        121: "登入失敗3次，自動鎖定30分鐘，可風控提早解除",
        120: "登入失敗10次，自動永久鎖定，需風控解除",
        101: "帳戶已黑名單"
    },
    smsErrorCode: {
        301: "會員已存在",
        302: "會員不存在",
        307: "發送簡訊間隔過短",
        200: "參數錯誤"
    },
    pwdErrorCode: {
        302: "會員不存在",
        303: "驗證碼錯誤",
        312: "密碼長度錯誤",
        200: "參數錯誤",
        201: "新增時發生錯誤"
    },
    registerErrorCode: {
        301: "會員已存在",
        303: "驗證碼錯誤",
        312: "密碼長度錯誤",
        305: "AccessToken無效",
        308: "此FB帳號已綁定過",
        200: "參數錯誤",
        201: "新增時發生錯誤"
    }
}
