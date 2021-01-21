<template>
  <div class="event-wrapper" @mousemove="moving($event)" @mouseleave="reset()">
    <div class="header">
      <div class="title-box">
        <div class="title">
          <div class="text">大</div>
          <div class="text">學</div>
          <div class="text">生</div>
        </div>
        <div class="title">
          <div class="text">最</div>
          <div class="text">愛</div>
          <div class="text">用</div>
          <div class="text">的</div>
        </div>
        <div class="title">
          <div class="text">金</div>
          <div class="text">融</div>
          <div class="text">服</div>
          <div class="text">務</div>
          <div class="text">平</div>
          <div class="text">台</div>
        </div>
      </div>

      <div class="sub-title">每<span>20</span>位大學生就有1人用過普匯</div>
      <div class="sub-title">每天持續有<span>15</span>筆案件媒合成功</div>

      <div class="pu-img"><img class="img-fluid" src="/images/pu.png" /></div>
    </div>
    <div class="worry"><img src="../asset/images/e_student.svg" class="img-fluid" /></div>
    <div class="form-top">
      <h3>為什麼大學生愛用<span>普匯</span>？</h3>
      <div class="cnt">
        <div class="img"><img src="../asset/images/god_pu.svg" class="img-fluid" /></div>
        <div class="list">
          <ul>
            <li>
              首創<span>無人化AI審核風控系統</span><br />獨創社交認證，信評制度更趨活化
            </li>
            <li>申貸過程無人接觸<br />AI風控系統<span>60分鐘</span>即完成審核</li>
            <li>資料繳交方便<br />只需一天，<span>貸款極速到手</span></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="form">
      <div class="event-form">
        <div class="logo"><img src="/images/logo.png" class="img-fluid" /></div>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">邀請碼</span>
          </div>
          <div class="form-control">{{ promo }}</div>
        </div>

        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">註冊帳號</span>
          </div>
          <input
            type="text"
            class="form-control"
            maxlength="10"
            placeholder="手機"
            v-model="phone"
          />
        </div>

        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">驗證碼</span>
          </div>
          <input
            type="text"
            class="form-control"
            style="margin-right: 10px"
            placeholder="驗證碼"
            maxlength="6"
            v-model="code"
          />
          <button class="btn btn-primary" @click="getCaptcha" v-if="!isSended">
            獲取驗證碼
          </button>
          <template v-if="isSended">
            <div class="tip">驗證碼已寄出</div>
            <div class="hide">{{ counter }}S有效</div>
          </template>
        </div>

        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">註冊密碼</span>
          </div>
          <input
            type="password"
            class="form-control"
            placeholder="請設置註冊密碼"
            v-model="password"
          />
        </div>

        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">密碼確認</span>
          </div>
          <input
            type="password"
            class="form-control"
            placeholder="再次輸入註冊密碼"
            v-model="confirmPassword"
          />
        </div>

        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">信箱</span>
          </div>
          <input
            type="text"
            class="form-control"
            placeholder="請輸入信箱"
            v-model="email"
          />
        </div>
        <div class="terms-box">
          <label class="terms-row">
            <input type="checkbox" v-model="isChecked" />
            我已閱讀並同意
            <a class="terms" target="_blank" href="/userTerms">會員服務條款</a>
            、
            <a class="terms" target="_blank" href="/privacyTerms">隱私權條款</a>
          </label>
        </div>
        <div class="message" v-if="message">{{ message }}</div>
        <button class="btn btn-success" v-if="isChecked" @click="doRegister">
          同意並送出
        </button>
        <button class="btn btn-disable" v-else>同意並送出</button>
        <h2 class="desc">最挺年輕人的金融科技團隊<br />校園贊助立即出現</h2>
        <div class="num-row">
          <div class="item">
            <p>目前累積下載人數</p>
            <p><img src="../asset/images/flag.svg" class="img-fluid" /></p>
            <p>{{ downloadNum }}人</p>
          </div>
          <div class="item">
            <p>目前累積註冊人數</p>
            <p><img src="../asset/images/flag.svg" class="img-fluid" /></p>
            <p>{{ userNum }}人</p>
          </div>
        </div>
      </div>
    </div>
    <div
      class="success-modal modal fade"
      ref="successModal"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
      data-backdrop="static"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">✕</button>
            <div class="login-logo">
              <img src="/images/logo_puhey.svg" class="img-fluid" />
            </div>
          </div>
          <div class="modal-body">
            <a
              class="link"
              target="_blank"
              :href="`https://dev-app-borrow.influxfin.com/?ofl=https://play.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dcom.influxfin.borrow&link=https://dev-app-borrow.influxfin.com%3Fpromote_code%3D${promo}&apn=com.influxfin.borrow&isi=1463581445&ibi=com.influxfin.borrow&utm_source=partner&utm_medium=promoter&utm_campaign=${promo}&ct=${promo}&pt=119664586&mt=8`"
              >恭喜註冊完成！<br />馬上來去下載APP 普匯inFlux！</a
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    isSended: false,
    isChecked: false,
    pageY: 0,
    pageX: 0,
    promo: "",
    phone: "",
    password: "",
    confirmPassword: "",
    code: "",
    message: "",
    email: "",
    downloadNum: 0,
    userNum: 0,
    counter: 180,
  }),
  created() {
    const urlParams = new URLSearchParams(window.location.search);
    let search = urlParams.get("promo");
    this.promo = search ? search.toLowerCase() : "";
    $("title").text(`普匯金融科技校園贊助活動 - inFlux普匯金融科技`);

    this.getNum();
  },
  watch: {
    phone(newdata) {
      this.phone = newdata.replace(/[^\d]/g, "");
    },
    code(newdata) {
      this.code = newdata.replace(/[^\d]/g, "");
    },
  },
  methods: {
    moving($event) {
      let yOffset = 0;
      let xOffset = 0;
      if (this.pageY > $event.pageY) {
        yOffset = 5;
      } else {
        yOffset = -5;
      }

      if (this.pageX > $event.pageX) {
        xOffset = 5;
      } else {
        xOffset = -5;
      }

      $(".pu-img").css("transform", `translate(${xOffset}px,${yOffset}px)`);

      this.pageY = $event.pageY;
      this.pageX = $event.pageX;
    },
    reset() {
      $(".pu-img").css("transform", `translate(0px,0px)`);
    },
    async getNum() {
      let promo = this.promo;
      let res = await axios.post("eventGetNum", { promo });

      gsap.to(this.$data, { duration: 1, downloadNum: res.data.downloadNum });
      gsap.to(this.$data, { duration: 1, userNum: res.data.userNum });
    },
    getCaptcha() {
      if (this.promo) {
        let phone = this.phone;

        if (!phone) {
          this.message = "請輸入手機";
          return;
        }

        this.counter = 180;

        axios
          .post(`${location.origin}/getCaptcha`, { phone, type: "registerphone" })
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
      }
    },
    doRegister() {
      if (this.promo) {
        let phone = this.phone;
        let password = this.password;
        let password_confirmation = this.confirmPassword;
        let code = this.code;
        let promo = this.promo;
        let email = this.email;

        axios
          .post(`/eventRegister`, {
            phone,
            password,
            password_confirmation,
            promo,
            email,
            code,
          })
          .then((res) => {
            clearInterval(this.timer);
            $(this.$refs.successModal).modal("show");
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
      }
    },
    reciprocal() {
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
.event-wrapper {
  .header {
    width: 1100px;
    background-color: #153a71;
    padding: 2rem 8rem;
    position: relative;

    .pu-img {
      width: 300px;
      position: absolute;
      top: 50px;
      right: -150px;
      transition-duration: 1s;
      filter: drop-shadow(0px 2px 4px black);
    }

    .title-box {
      .title {
        overflow: auto;
        width: fit-content;

        &:nth-of-type(1) {
          .text {
            @for $i from 1 through 3 {
              &:nth-of-type(#{$i}) {
                animation-delay: 1 + (0.2s * $i);
              }
            }
          }
        }

        &:nth-of-type(2) {
          .text {
            @for $i from 1 through 4 {
              &:nth-of-type(#{$i}) {
                animation-delay: 1 + (0.2s * ($i + 3));
              }
            }
          }
        }

        &:nth-of-type(3) {
          .text {
            @for $i from 1 through 6 {
              &:nth-of-type(#{$i}) {
                animation-delay: 1 + (0.2s * ($i + 7));
              }
            }
          }
        }

        .text {
          font-size: 75px;
          font-weight: bolder;
          color: #ffffff;
          float: left;
          animation: d 4s infinite;
        }
      }
    }

    .sub-title {
      padding: 15px 0px;
      border-radius: 44px;
      background-color: #ffffff;
      font-size: 36px;
      font-weight: bolder;
      line-height: 1.5;
      letter-spacing: 1.8px;
      text-align: center;
      color: #153a71;
      margin: 20px 0px;
      width: 600px;

      span {
        color: #fea500;
      }
    }
  }

  .worry {
    width: 1100px;
    margin: 2rem auto 0px auto;
  }

  .form-top {
    padding: 3rem 3rem 0rem 3rem;
    background-color: #ecedf1;
    color: #153a71;

    span {
      color: #fea500;
    }

    h3 {
      font-size: 75px;
      font-weight: bold;
      line-height: 1.19;
      text-align: center;
    }

    .cnt {
      display: flex;
      width: 1100px;
      margin: 0px auto;

      .img {
        width: 40%;
        padding: 3rem 1rem;
        animation: god 4s infinite;
      }

      .list {
        width: 60%;
        font-size: 27px;
        font-weight: 700;
        line-height: 1.5;
        letter-spacing: 1.8px;
        text-align: left;
        color: #153a71;
        padding: 2rem 3rem;

        ul {
          list-style: none;

          li {
            background-image: url("../asset/images/e_like.svg");
            background-repeat: no-repeat;
            background-position: 0px 0px;
            padding-left: 90px;
            margin: 2rem 0px;
          }
        }
      }
    }
  }

  .form {
    background-image: linear-gradient(180deg, #ecedf1 30%, #153a71 30%);
    position: relative;
    padding: 1rem 0px 4rem 0px;

    .event-form {
      background-image: url("../asset/images/event_from.png");
      max-width: 700px;
      min-height: 1412px;
      margin: 0rem auto;
      overflow: hidden;
      padding: 0rem 4rem;
      z-index: 4;
      position: relative;
      background-size: cover;

      .logo {
        width: 300px;
        margin: 4rem auto;
      }

      .terms-box {
        display: flex;
        flex-wrap: wrap;
        margin: 0.5rem auto 1.5rem auto;

        .terms-row {
          margin: 0.5rem auto;
          font-weight: 800;
          font-size: 27px;

          a {
            color: #007bff;
            text-decoration: underline;
          }
        }
      }

      .input-group {
        width: 450px;
        margin: 0px auto 2.5rem auto;
        background: #eaeaea;
        padding: 5px 10px;
        border-radius: 8px;
        box-shadow: 4px 4px #969696;
      }

      .input-group-text {
        width: 125px;
        text-align: end;
        display: block;
        border: 0px;
        background: #ffffff00;
        font-weight: 800;
        font-size: 27px;
        padding: 0rem 0.5rem;
        line-height: 38px;
      }

      .form-control {
        border: 0px;
      }

      .desc {
        margin: 4rem auto;
        font-size: 35px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.48;
        letter-spacing: 2.7px;
        text-align: center;
        color: #153a71;
      }

      .num-row {
        overflow: auto;

        .item {
          display: flex;
          width: fit-content;
          margin: 0px auto 2.5rem auto;
          background: #eaeaea;
          padding: 0.5rem 5rem;
          box-shadow: 4px 4px #969696;
          border-radius: 20px;
          color: #495057;

          %font {
            line-height: 40px;
            font-weight: bold;
            font-size: 27px;
          }

          p {
            margin-bottom: 0px;
            &:nth-of-type(1) {
              @extend %font;
              margin-right: 10px;
            }

            &:nth-of-type(2) {
              width: 35px;
            }

            &:nth-of-type(3) {
              @extend %font;
              margin-left: 10px;
            }
          }
        }
      }

      .tip {
        line-height: 38px;
        padding: 0px 10px;
        color: #9c9c9c;
        background: aliceblue;
      }

      .hide {
        display: block;
        width: 75px;
        text-align: start;
        margin: 5px 0px 0px 97px;
        color: #ff7171;
      }

      .message {
        background: antiquewhite;
        padding: 10px;
        margin: 1.5rem auto;
        border-radius: 5px;
        font-weight: bold;
        color: red;
      }

      .btn-success {
        font-size: 27px;
        display: block;
        margin: 0px auto;
        font-weight: 800;
        width: 300px;
      }

      .btn-disable {
        width: 300px;
        font-size: 27px;
        background: #9c9c9c;
        cursor: default;
        display: block;
        margin: 0px auto;
        font-weight: 800;
        color: #495057;
      }
    }
  }

  .success-modal {
    .modal-dialog {
      top: 35%;
    }
    
    .login-logo {
      margin: 0px auto;
    }

    .close {
      position: absolute;
      top: 1rem;
      right: 1rem;
    }

    .link {
      font-weight: 700;
      color: #1000ff;
      text-align: center;
      display: block;
      text-decoration: underline;
    }
  }

  @media screen and (max-width: 767px) {
    .header {
      width: 100%;
      padding: 10px;

      .pu-img {
        display: none;
      }

      .title-box {
        .title {
          .text {
            font-size: 50px;
            padding: 7px 4px;
          }
        }
      }

      .sub-title {
        width: 100%;
        font-size: 19px;
        padding: 8px 0px;
      }
    }

    .worry {
      width: 100%;
    }

    .form-top {
      padding: 10px;

      h3 {
        font-size: 27px;
      }

      .cnt {
        width: 100%;

        .img {
          display: none;
        }

        .list {
          width: 100%;
          padding: 0px;
          font-size: 17px;

          ul {
            padding: 0px;

            li {
              margin: 1rem 0px;
              padding-left: 55px;
            }
          }
        }
      }
    }

    .form {
      padding: 10px;

      .event-form {
        max-width: 100%;
        min-height: fit-content;
        background: #ffffff;
        border-radius: 10px;
        padding: 0px 10px;

        .logo {
          margin: 2rem auto;
          width: 200px;
        }

        .input-group {
          width: 100%;
          margin: 0px auto 1rem auto;
        }

        .input-group-text {
          font-size: 20px;
          width: 95px;
        }

        .terms-box {
          margin: 0.5rem auto;

          .terms-row {
            font-size: 15px;
          }
        }

        .btn-success,
        .btn-disable {
          font-size: 20px;
          width: fit-content;
        }

        .desc {
          margin: 1rem auto;
          font-size: 24px;
        }

        .num-row {
          .item {
            padding: 0.5rem 2rem;
            margin: 1rem 5px;

            %font {
              font-size: 20px;
            }

            p {
              &:nth-of-type(1) {
                @extend %font;
              }

              &:nth-of-type(3) {
                @extend %font;
              }
            }
          }
        }
      }
    }
  }
}

@keyframes god {
  0%,
  100% {
    transform: translateY(5px);
  }

  50% {
    transform: translateY(-5px);
  }
}

@keyframes d {
  0%,
  30%,
  100% {
    transform: translateY(0px);
  }

  15% {
    transform: translateY(-20px);
  }
}
</style>
