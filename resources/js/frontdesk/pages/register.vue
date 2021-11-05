<template>
  <div class="register-wrapper">
    <div class="register-dialog">
      <div class="ic">
        <h3>會員註冊</h3>
      </div>
      <div class="re-form">
        <div class="dialog-header">
          <router-link to="index" class="header-img">
            <img :src="'/images/logo.png'" class="img-fluid" />
          </router-link>
        </div>
        <template v-if="isRegisterSuccess">
          <div class="dialog-success">
            <p>註冊成功！</p>
            <p>請至APP平台完成認證開始實現你的目標吧！</p>
            <div class="link-box">
              <a
                class="btn btn-go"
                href="https://event.influxfin.com/R/url?p=webbanner"
                target="_blank"
                >點我前往</a
              >
              <router-link class="btn btn-home" to="/index">回首頁</router-link>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="dialog-content">
            <div class="input-group">
              <span class="input-group-addon label-text">手機：</span>
              <input
                type="text"
                class="form-control label-input"
                placeholder="請輸入手機號碼"
                v-model="phone"
                maxlength="10"
              />
            </div>
            <div class="input-group">
              <span class="input-group-addon label-text">密碼：</span>
              <input
                type="password"
                class="form-control label-input"
                placeholder="請輸入密碼"
                v-model="password"
              />
            </div>
            <div class="input-group">
              <span class="input-group-addon label-text">確認密碼：</span>
              <input
                type="password"
                class="form-control label-input"
                placeholder="請再次輸入密碼"
                v-model="confirmPassword"
              />
            </div>
            <div class="input-group">
              <span class="input-group-addon label-text">推薦人：</span>
              <input
                class="form-control label-input mr-2 mr-0-sm mb-2 mb-0-sm"
                placeholder="請輸入暱稱"
                v-model="recommenderNickName"
              />
              <input
                class="form-control label-input"
                placeholder="請輸入推薦人姓名"
                v-model="recommenderName"
              />
            </div>
            <div class="input-group">
              <span class="input-group-addon label-text">驗證碼：</span>
              <div class="captcha-row" style="display: flex">
                <input
                  type="text"
                  class="form-control label-input"
                  placeholder="請輸入驗證碼"
                  v-model="code"
                  maxlength="6"
                />
                <button
                  class="btn btn-captcha"
                  @click="getCaptcha('registerphone')"
                  v-if="!isSended"
                >
                  取得驗證碼
                </button>
                <div class="btn btn-disable" v-if="isSended">
                  {{ counter }}S有效
                </div>
                <span class="tip" v-if="isSended">驗證碼已寄出</span>
              </div>
            </div>
            <template v-if="$root.investor === '0'">
              <div class="input-group">
                <div class="chiller_cb" style="margin: 0px auto">
                  <input
                    id="confirmTerms"
                    type="checkbox"
                    @click="isAgree = !isAgree"
                    :checked="isAgree"
                  />
                  <label for="confirmTerms" class="block"></label>
                  <span></span>
                  <div class="row">
                    我同意
                    <div class="terms" @click="getTerms('user')">
                      借款人服務條款
                    </div>
                    、
                    <div class="terms" @click="getTerms('privacy_policy')">
                      隱私權條款
                    </div>
                  </div>
                </div>
              </div>
            </template>
            <template v-else>
              <div class="input-group">
                <div class="chiller_cb" style="margin: 0px auto">
                  <input
                    id="confirmTerms"
                    type="checkbox"
                    @click="isAgree = !isAgree"
                    :checked="isAgree"
                  />
                  <label for="confirmTerms" class="block"></label>
                  <span></span>
                  <div class="row">
                    我同意
                    <div class="terms" @click="getTerms('investor')">
                      貸款人服務條款
                    </div>
                    、
                    <div class="terms" @click="getTerms('privacy_policy')">
                      隱私權條款
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
          <div class="message" v-if="message">{{ message }}</div>
          <div class="dialog-footer">
            <div
              v-if="
                phone && password && confirmPassword && code && isAgree
                  ? false
                  : true
              "
              class="btn btn-disable"
              disable
            >
              送出
            </div>
            <button
              type="button"
              v-else
              class="btn btn-submit"
              @click="doRegister"
            >
              送出
            </button>
          </div>
        </template>
      </div>
    </div>
    <div
      class="modal fade"
      ref="termsForm"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <div class="terms-title">{{ termsTitle }}</div>
            <button type="button" class="close" data-dismiss="modal">✕</button>
          </div>
          <div class="modal-body terms-content">
            <div v-html="termsContent"></div>
          </div>
          <div class="modal-footer"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    isAgree: false,
    isSended: false,
    isRegisterSuccess: false,
    phone: "",
    password: "",
    confirmPassword: "",
    code: "",
    termsTitle: "",
    termsContent: "",
    message: "",
    timer: null,
    counter: 180,
    recommenderNickName: '',
    recommenderName: '',
  }),
  created () {
    this.isRegisterSuccess = false;
    $("title").text(`註冊帳號 - inFlux普匯金融科技`);
  },
  mounted () {
    this.$nextTick(() => { });
  },
  watch: {
    phone (newdata) {
      this.phone = newdata.replace(/[^\d]/g, "");
    },
    code (newdata) {
      this.code = newdata.replace(/[^\d]/g, "");
    },
  },
  methods: {
    getCaptcha (type) {
      let phone = this.phone;

      if (!phone) {
        $this.message = "請輸入手機";
        return;
      }

      this.counter = 180;

      axios
        .post(`${location.origin}/getCaptcha`, { phone, type })
        .then((res) => {
          this.isSended = true;
          this.message = "";
          this.timer = setInterval(() => {
            this.reciprocal();
          }, 1000);
        })
        .catch((error) => {
          let errorsData = error.response.data;
          this.message = `${this.$store.state.smsErrorCode[errorsData.error]}`;
        });
    },
    getTerms (termsType) {
      let $this = this;

      axios
        .post(`${location.origin}/getTerms`, { termsType })
        .then((res) => {
          let { content, name } = res.data.data;
          this.termsContent = content;
          this.termsTitle = name;

          $(this.$refs.termsForm).modal("show");
        })
        .catch((error) => {
          console.log("getTerms 發生錯誤，請稍後再試");
        });
    },
    doRegister () {
      let phone = this.phone;
      let password = this.password;
      let password_confirmation = this.confirmPassword;
      let code = this.code;
      const nick_name = this.recommenderNickName
      const name = this.recommenderName
      if (nick_name.length > 0 || name.length > 0) {
        if (nick_name.length < 1 || name.length < 1) {
          return
        }
      }
      const promote_info = { nick_name, name }
      axios
        // do Register => eventRegister
        .post(`${location.origin}/eventRegister`, {
          phone,
          password,
          password_confirmation,
          code,
          promote_info,
        })
        .then((res) => {
          this.isRegisterSuccess = true;
        })
        .catch((error) => {
          let errorsData = error.response.data;
          if (errorsData.message) {
            let messages = [];
            $.each(errorsData.errors, (key, item) => {
              item.forEach((message, k) => {
                messages.push(message);
              });
            });
            this.message = messages.join("、");
          } else {
            this.message = `${this.$store.state.registerErrorCode[errorsData.error]}`;
          }
        });
    },
    reciprocal () {
      this.counter--;
      if (this.counter === 0) {
        clearInterval(this.timer);
        this.timer = null;
        alert("驗證碼失效，請重新申請");
        location.reload();
      }
    },
  },
};
</script>

<style lang="scss">
.register-wrapper {
  position: relative;
  overflow: hidden;

  .register-dialog {
    box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
    background-color: #f5f5f5;
    width: 880px;
    margin: 45px auto;
    display: flex;

    .ic {
      background-image: url("../asset/images/4058371.png");
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      width: 50%;
      position: relative;

      h3 {
        font-size: 36px;
        font-weight: 600;
        color: #ffffff;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
      }
    }

    .re-form {
      width: 50%;
      height: 478px;
      margin: 5rem 0px;
      padding: 20px;

      %basic {
        border-radius: 50px;
        height: 30px;
        line-height: 19px;
        font-size: 18px;
        font-weight: bolder;
      }

      .dialog-header {
        width: 35%;
        margin: 0px auto;
        margin-bottom: 20px;
      }

      .dialog-success {
        margin: 20px 0px;
        text-align: center;
        color: #000000;
        font-size: 16px;
        font-weight: bolder;

        .link-box {
          width: 80%;
          margin: 30px auto;
          display: flex;
          justify-content: space-between;

          .btn-go {
            @extend %basic;
            width: 40%;
            border: solid 0.5px #083a6e;
            background: #083a6e;
            color: #ffffff;

            &:hover {
              background: #ffffff;
              color: #083a6e;
            }
          }

          .btn-home {
            @extend %basic;
            width: 40%;
            border: solid 0.5px #083a6e;
            color: #083a6e;

            &:hover {
              background: #083a6e;
              color: #ffffff;
            }
          }
        }
      }

      .dialog-content {
        margin: 20px auto;

        .input-group {
          margin: 25px 10px;
          width: auto;
        }

        .label-text {
          line-height: 30px;
          background-color: #ffffff00 !important;
          border: none !important;
          border-radius: 0px !important;
          text-align: end !important;
          width: 80px;
        }

        .label-input {
          font-size: 14px;
          height: 30px;
        }

        .captcha-row {
          display: flex;
          width: 290px;
        }

        .btn-captcha {
          background: #1e2973;
          border-radius: 50px;
          line-height: 15px;
          color: #ffffff;
          font-size: 16px;
          margin-left: 20px;
          width: 190px;

          &:hover {
            background: #0040d0;
          }
        }

        .btn-disable {
          border-radius: 50px;
          width: 190px;
          height: 30px;
          line-height: 15px;
          font-size: 18px;
          background: #d5d5d5;
          color: #969696;
          cursor: default;
          margin-left: 20px;
        }

        .terms {
          color: #0072ff;
          text-decoration: underline;
          cursor: pointer;
        }

        .tip {
          position: absolute;
          bottom: -17px;
          right: 13px;
          font-size: small;
          color: red;
        }
      }

      .dialog-footer {
        text-align: center;
        margin: 10px 0px;

        .btn-submit {
          @extend %basic;
          width: 60%;
          border: solid 0.5px #083a6e;
          color: #083a6e;

          &:hover {
            background: #083a6e;
            color: #ffffff;
          }
        }

        .btn-disable {
          @extend %basic;
          width: 60%;
          background: #d5d5d5;
          color: #969696;
          cursor: default;
        }
      }
    }
  }

  .block {
    height: 32px;
  }

  .row {
    margin: 0px;
  }

  .terms-content {
    height: 500px;
    overflow-y: scroll;
  }

  .modal-dialog {
    top: 10%;
  }

  .message {
    text-align: center;
    color: red;
    font-weight: bolder;
    margin-top: 20px;
  }
}

@media screen and (max-width: 767px) {
  .register-wrapper {
    height: 95vh;

    .register-dialog {
      width: fit-content;
      margin: 0px;
      flex-direction: column;

      .ic {
        width: initial;
        height: 200px;
      }

      .re-form {
        width: initial;
        height: initial;
        margin: 1rem;
      }

      .dialog-content {
        width: 100%;
        margin: 20px auto;

        .input-group {
          margin: 30px 10px;
          display: block;
        }
      }

      .captcha-row {
        width: 100%;
      }
    }
  }
}
</style>
