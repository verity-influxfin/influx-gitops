<template>
  <div class="register-wrapper">
    <div class="register-dialog">
      <div class="dialog-header">
        <router-link to="/index" class="header-img">
          <img :src="'./image/logo.png'" class="img-fluid" />
        </router-link>
      </div>
      <div v-if="isRegisterSuccess">
        <div class="dialog-success">
          <p>註冊成功！</p>
          <p>請至APP平台完成認證開始實現你的目標吧！</p>
          <router-link class="btn btn-primary fa-pull-right" to="/index">回首頁</router-link>
          <a
            class="btn btn-success fa-pull-left"
            href="https://event.influxfin.com/R/url?p=webbanner"
            target="_blank"
          >點我前往</a>
        </div>
      </div>
      <div v-else>
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
            <span class="input-group-addon label-text">驗證碼：</span>
            <div class="captcha-row">
              <input
                type="text"
                class="form-control label-input"
                placeholder="請輸入6位數驗證碼"
                v-model="code"
                maxlength="6"
              />
              <button
                class="btn btn-captcha"
                @click="getCaptcha('registerphone')"
                v-if="!IsSended"
              >取得驗證碼</button>
              <div class="btn btn-disable" v-if="IsSended">{{counter}}S有效</div>
              <span class="tip" v-if="IsSended">驗證碼已寄出</span>
            </div>
          </div>
          <div class="input-group">
            <div class="chiller_cb" style="margin:0px auto">
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
                <div class="terms" @click="getTerms('user')">貸款人服務條款</div>、
                <div class="terms" @click="getTerms('privacy_policy')">隱私權條款</div>
              </div>
            </div>
          </div>
        </div>
        <div class="message" v-if="message">{{message}}</div>
        <div class="dialog-footer">
          <div
            v-if="(phone && password && confirmPassword && code && isAgree) ? false : true"
            class="btn btn-disable"
            disable
          >送出</div>
          <button type="button" v-else class="btn btn-submit" @click="doRegister">送出</button>
        </div>
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
            <div class="terms-title">{{this.termsTitle}}</div>
            <button type="button" class="close" data-dismiss="modal">✕</button>
          </div>
          <div class="modal-body terms-content">
            <div v-html="this.termsContent"></div>
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
    IsSended: false,
    isRegisterSuccess: false,
    phone: "",
    password: "",
    confirmPassword: "",
    code: "",
    termsTitle: "",
    termsContent: "",
    message: "",
    timer: null,
    counter: 180
  }),
  created() {
    this.isRegisterSuccess = false;
  },
  mounted() {
    $(".page-header").hide();
    $(".page-footer").hide();
    $(".back-top").hide();
    $(".afc_popup").hide();
  },
  watch: {
    phone(newdata) {
      this.phone = newdata.replace(/[^\d]/g, "");
    },
    code(newdata) {
      this.code = newdata.replace(/[^\d]/g, "");
    }
  },
  methods: {
    getCaptcha(type) {
      let $this = this;
      let phone = $this.phone;

      if (!phone) {
        $this.message = "請輸入手機";
        return;
      }

      $this.counter = 180;

      $.ajax({
        url: "getCaptcha",
        type: "POST",
        dataType: "json",
        data: { phone, type },
        success() {
          $this.IsSended = true;
          $this.timer = setInterval(() => {
            $this.reciprocal();
          }, 1000);
        },
        error(e) {
          $this.message = `${
            $this.$store.state.smsErrorCode[e.responseJSON.error]
          }`;
        }
      });
    },
    getTerms(termsType) {
      let $this = this;

      $.ajax({
        url: "getTerms",
        type: "POST",
        dataType: "json",
        data: { termsType },
        success(msg) {
          let { content, name } = msg.data;
          $this.termsContent = content;
          $this.termsTitle = name;

          $($this.$refs.termsForm).modal("show");
        },
        error(e) {
          console.log(e);
        }
      });
    },
    doRegister() {
      let $this = this;

      let phone = $this.phone;
      let password = $this.password;
      let password_confirmation = $this.confirmPassword;
      let code = $this.code;

      $.ajax({
        url: "doRegister",
        type: "POST",
        dataType: "json",
        data: { phone, password, password_confirmation, code },
        success(msg) {
          $this.isRegisterSuccess = true;
        },
        error(e) {
          if (e.responseJSON.message) {
            let messages = [];
            $.each(e.responseJSON.errors, (key, item) => {
              item.forEach((message, k) => {
                messages.push(message);
              });
            });
            $this.message = messages.join("、");
          } else {
            $this.message = `${
              $this.$store.state.pwdErrorCode[e.responseJSON.error]
            }`;
          }
        }
      });
    },
    reciprocal() {
      this.counter--;
      if (this.counter === 0) {
        clearInterval(this.timer);
        this.timer = null;
        alert("驗證碼失效，請重新申請");
        location.reload();
      }
    }
  }
};
</script>

<style lang="scss">
.register-wrapper {
  position: relative;
  height: 100vh;
  background: linear-gradient(180deg, #619eff, #adcdff);
  font-family: Arial, "微軟正黑體", "Helvetica Neue", Helvetica, sans-serif;

  .register-dialog {
    position: absolute;
    width: 35%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #ffffff;
    padding: 10px;
    border-radius: 50px;
    box-shadow: 0 0 9px black;

    .dialog-header {
      width: 35%;
      margin: 0px auto;
      margin-bottom: 20px;
    }

    .dialog-success {
      width: 80%;
      margin: 20px auto;
      text-align: center;
      color: #6d6d6d;
      font-size: 18px;
      font-weight: bolder;
    }

    .dialog-content {
      width: 80%;
      margin: 20px auto;

      .input-group {
        margin: 10px;
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
        border-radius: 39px;
        font-size: 14px;
        height: 30px;
      }

      .btn-captcha {
        background: #00adff;
        border-radius: 50px;
        line-height: 15px;
        color: #ffffff;
        font-size: 16px;
        margin-left: 20px;
        width: 175px;

        &:hover {
          background: #0040d0;
        }
      }

      .btn-disable {
        border-radius: 50px;
        width: 175px;
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
      
      %basic {
        border-radius: 50px;
        width: 60%;
        height: 30px;
        line-height: 19px;
        font-size: 18px;
        font-weight: bolder;
      }

      .btn-submit {
        @extend %basic;
        background: #005aff;
        color: #ffffff;

        &:hover {
          background: #003cab;
        }
      }

      .btn-disable {
        @extend %basic;
        background: #d5d5d5;
        color: #969696;
        cursor: default;
      }
    }

    .captcha-row {
      position: relative;
      display: flex;
      width: 79%;
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

  @media screen and (max-width: 767px) {
    .register-dialog {
      width: 98%;

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