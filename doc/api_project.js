define({
  "name": "手機ATM API Doccument",
  "version": "0.0.0",
  "description": "手機ATM API，先做登入，若會員不存在則進行註冊流程<br>其他 API 皆需於 header 帶入 request_token",
  "title": "手機ATM API Doccument",
  "url": "https://api.influxfin.com/api",
  "order": [
    "User",
    "PostUserRegisterphone",
    "PostUserRegister",
    "PostUserLogin",
    "GetUserInfo",
    "PostUserBind",
    "PostUserSociallogin",
    "GetUserEditpwphone",
    "PostUserEditpw",
    "PostUserSmsloginphone",
    "PostUserSmslogin",
    "PostUserForgotpw",
    "PostUserContact",
    "Certification",
    "GetCertificationList",
    "PostCertificationIdcard",
    "GetCertificationIdcard",
    "PostCertificationHealthcard",
    "GetCertificationHealthcard",
    "PostCertificationStudent",
    "GetCertificationStudent",
    "Product",
    "GetProductCategory",
    "GetProductList",
    "GetProductInfoId",
    "PostProductApply",
    "GetProductSigningId",
    "GetProductCancelId",
    "GetProductApplylist",
    "GetProductApplyinfoId",
    "Repayment",
    "Target",
    "Recoveries",
    "GetRecoveriesDashboard",
    "GetRecoveriesList",
    "GetRecoveriesInfoId",
    "GetRecoveriesPassbook",
    "PostRecoveriesWithdraw",
    "PostRecoveriesPretransfer",
    "PostRecoveriesTransfer",
    "Agreement",
    "GetAgreementList",
    "GetAgreementInfoAlias",
    "Notification",
    "GetNotificationList",
    "GetNotificationInfoId",
    "GetNotificationReadall"
  ],
  "sampleUrl": false,
  "defaultVersion": "0.0.0",
  "apidoc": "0.3.0",
  "generator": {
    "name": "apidoc",
    "time": "2018-08-02T14:30:22.409Z",
    "url": "http://apidocjs.com",
    "version": "0.17.6"
  }
});
