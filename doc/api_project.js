define({
  "name": "P2p lending API Doccument",
  "version": "0.0.0",
  "description": "P2p lending 前台 API，先做登入，若會員不存在則進行註冊流程<br>其他 API 皆需於 header 帶入 request_token",
  "title": "P2p lending API Doccument",
  "url": "http://p2p-api.clockin.com.tw/api",
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
    "time": "2018-05-17T10:39:56.931Z",
    "url": "http://apidocjs.com",
    "version": "0.17.6"
  }
});
