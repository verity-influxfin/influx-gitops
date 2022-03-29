<template>
  <div class="main-content">
    <div class="banner">
      <img
        src="../asset/images/ntu_banner.jpg"
        class="banner-img d-sm-block d-none"
      />
      <img
        src="../asset/images/ntu_banner_phone.jpg"
        class="banner-img d-block d-sm-none"
      />
    </div>
    <div>
      <div class="title-dot-up">
        <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
        <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
        <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
        <div class="dots" style="opacity: 0.75; width: 6px; height: 6px"></div>
      </div>
      <div class="d-flex justify-content-center page-title-1">
        <img src="../asset/images/太陽.svg" class="img-icon" alt="" />
        <img src="../asset/images/孩子健康 看見希望.svg" class="img-text-1" />
      </div>
      <div class="title-dot-down">
        <div class="dots" style="opacity: 0.75; width: 6px; height: 6px"></div>
        <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
        <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
        <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="content-text col-auto">
        <div class="yt-top d-md-none d-flex mb-2">
          <div class="yt-top-icon"></div>
          <div class="yt-top-text">台大兒童健康基金會</div>
        </div>
        <div class="d-flex yt-top d-md-none d-flex">
          <button class="btn donate-function-button">我要捐款</button>
          <button class="btn btn-primary">捐款查詢</button>
        </div>
        <p>
          孩子愈來愈少了，<br />
          我們未來主人翁的健康好重要 <br />
          一個都不能少，<br />
          兒童健康不能等！<br />
        </p>
        <p>
          孩子生病了，<br />
          看著孩子辛苦面對病魔的眼神<br />
          我們好心疼、好焦急、好擔心，<br />
          我們知道不能再等了！
        </p>
        <p>
          我們比好多生病的孩子幸福好多，<br />
          我們擁有健康，擁有福氣
        </p>
      </div>
      <div class="col-auto">
        <div class="yt-top d-none d-md-flex">
          <div class="yt-top-icon"></div>
          <div class="yt-top-text">台大兒童健康基金會</div>
          <button class="btn donate-function-button" @click="openDonate">
            我要捐款
          </button>
          <button class="btn donate-query-button" @click="openDonateQuery">
            捐款查詢
          </button>
        </div>
        <div class="yt-iframe" v-show="showDonate === 'yt'">
          <div class="video-container">
            <iframe
              width="560"
              height="315"
              src="https://www.youtube.com/embed/5Bxx0sY9Ns0"
              title="YouTube video player"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen
            ></iframe>
          </div>
        </div>
        <div class="donate-function-page" v-show="showDonate === 'donate'">
          <div v-show="step === 'form'">
            <div class="donate-title">我要捐款</div>
            <p class="donate-info">
              普匯金融科技網頁僅提供非實名（具名，匿名）捐款功能，<br />
              若需實名捐款，請至普匯APP<br />
              因AML防制法捐款金額若大於50萬，請洽客服
            </p>
            <div class="donate-group">
              <button class="btn money-button" @click="donateForm.amount = 300">300元</button>
              <button class="btn money-button" @click="donateForm.amount = 500">500元</button>
              <button class="btn money-button" @click="donateForm.amount = 1000">1000元</button>
              <button class="btn money-button" @click="donateForm.amount = 3000">3000元</button>
            </div>
            <form class="donate-form" @submit.prevent="doDonate">
              <input
                type="text"
                class="form-control donate-input"
                placeholder="300"
                v-model.number="donateForm.amount"
                required
              />
              <div>捐款身份資訊（選填）</div>
              <input
                type="text"
                class="form-control donate-input"
                v-model="donateForm.name"
                placeholder="姓名/公司抬頭"
              />
              <input
                type="text"
                class="form-control donate-input"
                v-model="donateForm.number"
                placeholder="身分證字號/統一編號"
              />
              <input
                type="text"
                class="form-control donate-input"
                v-model="donateForm.phone"
                placeholder="聯絡手機"
              />
              <input
                type="email"
                class="form-control donate-input"
                v-model="donateForm.email"
                placeholder="Email"
              />
              <label>
                <input type="checkbox" v-model="donateForm.upload" />
                捐款收據代上傳國稅局
              </label>
              <label>
                <input type="checkbox" v-model="donateForm.receipt" />
                索取紙本收據
              </label>
              <input
                type="text"
                class="form-control donate-input"
                v-model="donateForm.address"
                placeholder="收據寄送地址"
                :disabled="!donateForm.receipt"
                :required="donateForm.receipt"
              />
              <button type="submit" class="btn submit-donate">
                取得匯款帳戶
              </button>
            </form>
          </div>
          <div v-show="step === 'account'">
            <div class="donate-title">捐款帳號</div>
            <p class="donate-info">您可於48小時內匯款至以下代收代付帳戶</p>
            <p class="donate-info info-blue">
              戶名： {{ bankData.charity_title }}
            </p>
            <p class="donate-info info-blue">
              銀行代碼： {{ bankData.bank_code }}
            </p>
            <p class="donate-info info-blue">
              捐款帳號： {{ bankData.bank_account }}
            </p>
            <p class="donate-info">
              感謝您的愛心 <br />
              待您匯款完成後<br />
              可回來普匯金融科技<br />
              查詢捐款狀態
            </p>
            <button type="button" class="btn submit-donate" @click="reset">
              返回
            </button>
          </div>
          <div v-show="step === 'donate-error'">
            <div class="donate-title mb-4">我要捐款</div>
            <div class="donate-info mx-auto">
              {{ donateErrorMsg }}
            </div>
            <a href="https://line.me/R/ti/p/%40kvd1654s" target="_blank">
              <button type="button" class="btn submit-donate">聯繫客服</button>
            </a>
          </div>
        </div>
        <div class="donate-function-page" v-show="showDonate === 'query'">
          <div class="donate-title mb-4">捐款查詢</div>
          <div v-if="haveQueryResult">
            <certificate-appreciation class="mx-auto" id="cert" />
            <div class="text-center mt-2">
              <button class="btn share-btn" @click="doShare">分享</button>
            </div>
            <div class="donate-info mx-auto">感謝您的愛心捐款</div>
            <div class="donate-info mx-auto">亦歡迎使用更多普匯服務</div>
          </div>
          <div v-else>
            <div class="donate-info mx-auto">感謝您的熱心參與</div>
            <div class="donate-info mx-auto">
              款項可能因銀行端作業時間而尚未入帳
            </div>
            <div class="donate-info mx-auto">請稍候嘗試</div>
            <div class="donate-info mx-auto">有任何捐款問題請洽客服</div>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center align-items-end">
      <div class="col-auto">
        <img
          src="../asset/images/dinosaurs.png"
          class="dinosaurs-img"
          alt="dinosaurs"
        />
      </div>
      <div class="col-auto content-text dinosaurs-text">
        <p>
          只有靠著您<br />
          與大家一起透過分享一點小小的幫忙<br />
          支持更好的兒童醫療與健康照顧<br />
        </p>
        <p>
          可以--<br />
          讓孩子的健康，在面對疾病受苦時，有翻盤的機會<br />
          讓孩子有機會得到最好的醫療照顧<br />
          讓孩子有機會發揮最佳潛力，健康快樂成長<br />
        </p>
        <p>
          這一切需要我們更多關注與支持，<br />
          因為隨時都有孩子生重病，<br />
          孩子的生命沒有時間再等了！<br />
        </p>

        <p>
          現在就需要您的愛心參與<br />
          讓生病的孩子，能有更好的健康未來<br />
          孩子健康，才能看見希望<br />
        </p>
      </div>
    </div>
    <div class="row justify-content-center organizer">
      <div class="col-auto">
        <div class="title-dot-up">
          <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
          <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
          <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
          <div
            class="dots"
            style="opacity: 0.75; width: 6px; height: 6px"
          ></div>
        </div>
        <div class="d-flex justify-content-center how-title">
          <img src="../asset/images/月亮.svg" class="img-icon" />
          <img
            src="../asset/images/如何捐款.svg"
            class="img-text-3"
            alt="如何捐款"
          />
          <img src="../asset/images/NTUCHwhale.png" class="img-whale" />
        </div>
        <div class="title-dot-down">
          <div
            class="dots"
            style="opacity: 0.75; width: 6px; height: 6px"
          ></div>
          <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
          <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
          <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center donate-row">
      <div class="donate-step">
        <div class="step-item">
          <div>步驟1：</div>
          <div>下載並註冊</div>
          <div>「普匯投資APP」</div>
        </div>
        <div class="step-item">
          <div>步驟2：</div>
          <div>選取</div>
          <div style="margin: 0 -30px">「台大兒童健康基金會」</div>
          <div>愛心勸募捐款</div>
        </div>
        <div class="step-item">
          <div>步驟3：</div>
          <div>選擇捐贈金額</div>
          <div>確認送出</div>
        </div>
        <div class="step-item">
          <div>步驟4：</div>
          <div>金額同步入帳</div>
          <div>基金會</div>
        </div>
        <div class="step-item">
          <div>步驟5：</div>
          <div>獲得</div>
          <div>聯合勸募感謝函</div>
        </div>
        <div class="step-item">
          <div>步驟6：</div>
          <div>將收到</div>
          <div>基金會開立之</div>
          <div>捐款收據</div>
        </div>
      </div>
      <div class="col-auto">
        <div class="donate-info">
          <div>所有捐款全數入帳基金會幫助兒童健康</div>
          <div class="hint">普匯免費提供平台服務</div>
        </div>
        <div class="go-donate-out">
          <div class="go-donate">
            <div class="go-donate-title">具名捐款</div>
            <div class="divider"></div>
            <img class="go-donate-text" src="../asset/images/愛心.svg" />
            <img class="go-donate-bird" src="../asset/images/NTUCHbird.png" />
            <div class="go-donate-text"></div>
            <router-link to="/investLink" target="_blank">
              <div class="go-donate-button">捐款去</div>
            </router-link>
          </div>
        </div>
      </div>
    </div>
    <div class="row no-gutters envoy-content">
      <div class="col-auto envoy">
        <div class="title-dot-up">
          <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
          <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
          <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
          <div
            class="dots"
            style="opacity: 0.75; width: 6px; height: 6px"
          ></div>
        </div>
        <div class="d-flex justify-content-center envoy-title">
          <img class="img-icon" src="~images/candy.png" alt="" />
          <div class="active-title">
            <div>台大兒醫普匯希望</div>
            <div>公益愛心大使</div>
          </div>
        </div>
        <div class="title-dot-down">
          <div
            class="dots"
            style="opacity: 0.75; width: 6px; height: 6px"
          ></div>
          <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
          <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
          <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
        </div>
        <div class="envoy-intro">
          <div class="envoy-intro-title">公益愛心大使</div>
          <div class="envoy-intro-name">張鈞甯</div>
          <div class="envoy-intro-text">
            <div>#氣質女神</div>
            <div>#知名藝人</div>
            <div class="mb-3">#熱心公益</div>
            <div class="mb-3">
              集陽光、知性、美於一身，出道以來 擁有許多膾炙人口的代表作，收穫廣
              大粉絲的喜愛。而私下的她，長期關注社會議題，對於公益活動，
              都是大力響應。
            </div>
            <div>
              此次擔任「台大兒醫普匯希望聯合募公益活動」愛心大使，
              想透過自己向社會傳遞溫暖與正能量，
              同時也號召更多善心人士，一同為孩子出一份力！
            </div>
          </div>
          <img
            class="envoy-portrait"
            src="~images/envoy-portrait.webp"
            alt="公益愛心大使-張鈞甯"
          />
        </div>
      </div>
      <div class="col-auto envoy">
        <div class="title-dot-up">
          <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
          <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
          <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
          <div
            class="dots"
            style="opacity: 0.75; width: 6px; height: 6px"
          ></div>
        </div>
        <div class="d-flex justify-content-center envoy-title">
          <img class="img-icon" src="~images/法杖.png" alt="" />
          <div class="active-title">
            <div>台大兒醫普匯希望</div>
            <div>活動主持人</div>
          </div>
        </div>
        <div class="title-dot-down">
          <div
            class="dots"
            style="opacity: 0.75; width: 6px; height: 6px"
          ></div>
          <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
          <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
          <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
        </div>
        <div class="envoy-intro">
          <div class="envoy-intro-title">公益活動主持人</div>
          <div class="envoy-intro-name">鍾欣凌</div>
          <div class="envoy-intro-text">
            <div>#金鐘視后</div>
            <div>#二寶媽</div>
            <div class="mb-3">#熱心公益</div>
            <div class="mb-3">
              早期以旅遊節目嶄露頭角，而後主持兒童少年節目，近期在戲劇影視方面
              榮獲多項金鐘獎！私下的她，有著幽默風趣的個性也經常關注孩子議題，
              如今身為二寶媽，更是在乎每個孩子的成長。
            </div>
            <div>
              此次擔任「台大兒醫普匯希望聯合勸募公益活動」主持人，
              也想盡自己的最大力量，幫助更多孩童重拾希望！
            </div>
          </div>
          <img
            class="host-portrait"
            src="~images/host-portrait.webp"
            alt="公益活動主持人-鍾欣凌"
          />
        </div>
      </div>
    </div>
    <div class="footer">
      <div>
        <img
          src="../asset/images/charitable-footer.png"
          class="footer-img"
          alt=""
        />
      </div>
    </div>
    <div class="modal" id="modal-cert" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">下載您的感謝狀</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div id="cert-img" class="mx-auto my-2"></div>
        </div>
      </div>
    </div>
    <div class="modal" id="modal-query" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="active-title">捐款查詢</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="m-3 query-form" @submit.prevent="querySubmit">
            <div class="row no-gutters mb-2">
              <div class="col-5 text-right form-info">捐款金額：</div>
              <input
                type="text"
                class="form-control col-7"
                placeholder="輸入捐款金額"
              />
            </div>
            <div class="row no-gutters mb-2">
              <div class="col-5 text-right form-info">署名/抬頭：</div>
              <input
                type="text"
                class="form-control col-7"
                placeholder="輸入署名/抬頭"
              />
            </div>
            <div class="row no-gutters mb-2">
              <div class="col-5 text-right form-info">
                身分證字號/統一編號：
              </div>
              <input
                type="text"
                class="form-control col-7"
                placeholder="輸入身分證字號/統一編號"
              />
            </div>
            <div class="row no-gutters">
              <div class="col-5 text-right form-info form-require">
                匯款帳戶末五碼：
              </div>
              <input
                type="text"
                class="form-control col-7"
                required
                placeholder="輸入匯款帳戶末五碼"
              />
            </div>
            <div class="text-center mt-2">
              <button type="submit" class="query-donate-btn btn">
                捐款查詢
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import certificateAppreciation from '@/component/certificateAppreciation'
import html2canvas from 'html2canvas'
import axios from 'axios'
export default {
  components: {
    certificateAppreciation
  },
  data() {
    return {
      showDonate: 'yt',
      step: 'form',
      haveQueryResult: false,
      genCert: false,
      donateForm: {
        amount: null,
        name: '',
        number: '',
        phone: '',
        email: '',
        upload: 0,
        receipt: 0,
        address: ''
      },
      bankData: {
        bank_code: '',
        bank_account: '',
        charity_title: ''
      },
      donateErrorMsg: '',
      paperTicket: false
    }
  },
  methods: {
    openDonate() {
      this.reset()
      this.showDonate = 'donate'
      //   this.step = 'form'
    },
    openDonateQuery() {
      $('#modal-query').modal('show')
    },
    reset() {
      this.showDonate = 'yt'
      this.step = 'form'
      this.bankData = {
        bank_code: '',
        bank_account: '',
        charity_title: ''
      }
      this.donateForm = {
        amount: null,
        name: '',
        number: '',
        phone: '',
        email: '',
        upload: 0,
        receipt: 0,
        address: ''
      }
    },
    doDonate() {
      axios.post('charity/donate/anonymous', {
        ...this.donateForm
      }).then(({ data }) => {
        this.bankData = data.data
        this.step = 'account'
        this.donateForm = {
          amount: null,
          name: '',
          number: '',
          phone: '',
          email: '',
          upload: 0,
          receipt: 0,
          address: ''
        }
      }).catch((err) => {
        this.step = 'donate-error'
        this.donateErrorMsg = err.msg || 'test'
      })
    },
    querySubmit() { },
    doShare() {
      if (this.genCert) {
        $('#modal-cert').modal('show')
        return
      }
      html2canvas(document.querySelector('#cert')).then(canvas => {
        const img = document.createElement('img')
        img.src = canvas.toDataURL()
        document.querySelector('#cert-img').appendChild(img)
        $('#modal-cert').modal('show')
        this.genCert = true
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.form-require::before {
  content: '*';
  color: red;
  display: inline-block;
  font-size: 16px;
  margin: 0 2px;
}
.main-content {
  background-image: url('../asset/images/ntu-bg.jpg');
  padding-bottom: 50px;
}
.banner {
  margin-bottom: 60px;
  .banner-img {
    width: 100%;
  }
}
.dots {
  background: #3670d3;
  border-radius: 100rem;
  margin-bottom: 12px;
  margin-left: auto;
  margin-right: auto;
}
.page-title-1 {
  margin-right: 110px;
}
.dashed {
  width: 100%;
  height: 2px;
  background-image: linear-gradient(
    to right,
    #7d7d7d 0%,
    #7d7d7d 50%,
    transparent 50%
  );
  background-size: 30px 2px;
  background-repeat: repeat-x;
}
.title-text {
  color: rgba($color: #fff, $alpha: 0);
  background: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
  background-clip: text;
  font-size: 32px;
  font-weight: bolder;
  width: 100%;
  text-align: center;
  margin: 2px auto 14px;
}
.content-text {
  padding: 0 15px;
  margin: 62.5px 90px 65px 0;
  font-size: 30px;
  font-weight: 500;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.5;
  letter-spacing: 1.2px;
  text-align: left;
  color: #7d7d7d;
}
.donate-function-button {
  margin-right: 12px;
  background-color: #036eb7;
  color: #fff;
  border-radius: 30px;
  padding: 8px 24px;
  font-size: 24px;
}
.donate-query-button {
  background: transparent;
  color: #036eb7;
  border-radius: 30px;
  padding: 8px 24px;
  font-size: 24px;
  border: 3px solid #036eb7;
  &:hover {
    color: #fff;
    background-color: #036eb7;
  }
}
.donate-function-page {
  width: fit-content;
  margin: 15px auto;
  padding: 15px;
  .donate-title {
    color: #0085c4;
    font-size: 28px;
    line-height: 1.4;
    text-align: center;
  }
  .donate-info {
    color: #7d7d7d;
    font-size: 20px;
    line-height: 1.4;
    width: fit-content;
    text-align: initial;
    &.info-blue {
      color: #0085c4;
    }
  }
  .donate-group {
    margin: 15px 0;
    display: flex;
    justify-content: center;
    gap: 10px;
    .money-button {
      flex: 1 0 20%;
      background-color: #036eb7;
      color: #fff;
      border-radius: 12px;
      // padding: 8px 24px;
      font-size: 20px;
    }
  }
  .donate-form {
    display: flex;
    flex-direction: column;
  }
  .share-btn {
    font-size: 24px;
    line-height: 1.5;
    border: 3px #0085c4 solid;
    border-radius: 24px;
    padding: 8px 50px;
    &:hover {
      color: #fff;
      background-color: #0085c4;
    }
  }
  .donate-input {
    margin-bottom: 5px;
    border-radius: 8px;
    font-size: 20px;
    padding: 8px 12px;
  }
  .submit-donate {
    background-color: #036eb7;
    color: #fff;
    border-radius: 10px;
    padding: 8px 16px;
    font-size: 20px;
    margin: 10px auto;
  }
}
.yt-top {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 40px;
  .yt-top-icon {
    width: 76px;
    height: 76px;
    background-image: url('../asset/images/NTUCH02F.png');
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center center;
  }
  .yt-top-text {
    margin: 0 24px;
    font-size: 30px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.33;
    letter-spacing: 1.5px;
    text-align: left;
    color: #0085c4;
  }
  .yt-top-donate {
    font-size: 34px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.18;
    letter-spacing: 1.7px;
    text-align: left;
    color: #0085c4;
    border-radius: 33px;
    padding: 8px 34px;
    border: solid 2px #0085c4;
  }
}
.yt-iframe {
  max-width: 670px;
  padding: 0 15px;
  margin-bottom: 62.5px;
  .video-container {
    position: relative;
    padding-bottom: 56.25%;
    padding-top: 30px;
    height: 0;
    overflow: hidden;
  }

  .video-container iframe,
  .video-container object,
  .video-container embed {
    position: absolute;
    top: 0;
    width: 100%;
    height: 100%;
  }
}
.dinosaurs-img {
  margin-bottom: 65px;
}
.dinosaurs-text {
  margin: 0 0 0 40px;
}
.active-title {
  margin-left: 20px;
  font-size: 34px;
  font-weight: 500;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.24;
  letter-spacing: 1.7px;
  text-align: center;
  color: #0666b2;
}
.donate-row {
  gap: 100px;
  margin-bottom: 60px;
}
.organizer-title {
  margin-right: 84px;
  margin-bottom: 10px;
}
.fund {
  display: flex;
  justify-content: center;
}
.fund-text {
  position: relative;
  margin: 0 auto 25px;
  padding: 0 15px;
  max-width: 565px;
  font-size: 20px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.8;
  letter-spacing: 1px;
  text-align: left;
  color: #000;
  .img-dec-1 {
    position: absolute;
    top: -150px;
    right: -235px;
  }
}
.physical {
  .physical-title-text {
    margin-bottom: 25px;
    font-size: 26.2px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.6;
    letter-spacing: 1.31px;
    text-align: center;
    color: #000;
  }
  .physical-info-text {
    font-size: 20px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.2;
    letter-spacing: 1px;
    text-align: left;
    color: #7d7d7d;
  }
  .hint {
    padding-left: 60px;
    font-size: 12.5px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.5;
    letter-spacing: 0.63px;
    text-align: left;
    color: #7d7d7d;
  }
  .go-apply {
    position: relative;
    background-image: url('~images/donate-apply-bg.webp');
    background-size: cover;
    background-repeat: no-repeat;
    width: 261px;
    height: 170px;
    padding: 20px 15px;
    display: flex;
    flex-direction: column;
    align-items: center;
    .go-donate-title {
      margin: 8px 0;
      transform: rotate(-8deg);
      font-size: 22px;
      font-weight: 500;
      font-stretch: normal;
      font-style: normal;
      line-height: 0.94;
      letter-spacing: 0.85px;
      text-align: center;
      color: #036eb7;
    }
    .divider {
      width: 80%;
      margin-top: 4px;
      border-bottom: 1.5px solid #d4d4d4;
      transform: rotate(-8deg);
    }
    .go-donate-text {
      transform: rotate(-8deg);
    }
    .img-heart {
      margin-top: 8px;
      width: 80px;
      text-align: center;
    }
    .img-rocket {
      position: absolute;
      width: 70px;
      left: 10px;
      top: 50%;
    }
    .go-donate-button {
      margin: 8px 0 0 0;
      transform: rotate(-8deg);
      border: #585858 1px solid;
      border-radius: 25px;
      padding: 6px 10px;
      font-size: 17px;
      font-weight: 500;
      font-stretch: normal;
      font-style: normal;
      line-height: 0.94;
      letter-spacing: 0.85px;
      text-align: left;
      color: #585858;
    }
  }
}
.organizer {
  margin: 60px 0;
}
.donate-step {
  display: grid;
  grid-template-columns: repeat(3, 172px);
  grid-template-rows: 1fr 1fr;
  gap: 14px;
  .step-item {
    background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='20' ry='20' stroke='rgb(64,64,64)' stroke-width='3' stroke-dasharray='4%2c10' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
    border-radius: 18px;
    padding: 15px;
    font-size: 15px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.73;
    letter-spacing: 0.75px;
    text-align: center;
    color: #000;
  }
}
.donate-info {
  margin: 15px 0;
  font-size: 20px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.8;
  letter-spacing: 1px;
  text-align: center;
  color: #000;
  .hint {
    color: #0666b2;
  }
}
.how-title {
  margin-right: 90px;
}
.go-donate-out {
  position: relative;
  margin: 0 auto;
  max-width: 1100px;
  .img-dec-4 {
    position: absolute;
    right: 140px;
  }
  .img-dec-5 {
    position: absolute;
    left: 240px;
    bottom: 0px;
  }
  .img-dec-6 {
    position: absolute;
    left: 140px;
    bottom: 40px;
  }
}
.go-donate {
  position: relative;
  background-image: url('~images/go-donate-bg.webp');
  background-size: cover;
  background-repeat: no-repeat;
  margin: 20px auto;
  width: 327px;
  height: 216px;
  padding: 20px 15px;
  display: flex;
  flex-direction: column;
  align-items: center;
  .go-donate-title {
    margin: 10px 0;
    transform: rotate(-8deg);
    font-size: 22px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: 0.94;
    letter-spacing: 0.85px;
    text-align: center;
    color: #036eb7;
  }
  .divider {
    width: 80%;
    margin-top: 4px;
    border-bottom: 1.5px solid #d4d4d4;
    transform: rotate(-8deg);
  }
  .go-donate-text {
    transform: rotate(-3deg);
    margin-top: 10px;
    width: 80px;
  }
  .go-donate-bird {
    position: absolute;
    height: 90px;
    left: -15px;
    top: 90px;
  }
  .go-donate-button {
    margin: 10px 0 0 0;
    transform: rotate(-8deg);
    border: #585858 1px solid;
    border-radius: 25px;
    padding: 6px 10px;
    font-size: 17px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: 0.94;
    letter-spacing: 0.85px;
    text-align: left;
    color: #585858;
  }
}
.img-text-1 {
  width: 400px;
}
.img-text-2 {
  width: 200px;
}
.img-text-3 {
  width: 200px;
}
.img-whale {
  width: 230px;
  position: absolute;
  right: -60px;
  top: -70px;
}
.img-icon {
  margin-right: 16px;
  width: 90px;
}
.envoy-content {
  gap: 25px 100px;
  justify-content: center;
}
.envoy {
  width: 575px;
  margin-bottom: 30px;
  .envoy-title {
    margin-right: 84px;
    margin-bottom: 10px;
  }
  .envoy-intro {
    position: relative;
    background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='18' ry='18' stroke='%23BCBCBCFF' stroke-width='5' stroke-dasharray='8' stroke-dashoffset='4' stroke-linecap='square'/%3e%3c/svg%3e");
    border-radius: 18px;
    width: 425px;
    padding: 30px;
    .envoy-intro-title {
      font-size: 20px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.8;
      letter-spacing: 1px;
      text-align: left;
      color: #000;
    }
    .envoy-intro-name {
      font-size: 26.2px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.6;
      letter-spacing: 1.31px;
      text-align: left;
      color: #0666b2;
    }
    .envoy-intro-text {
      max-width: 275px;
      font-size: 16px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.5;
      letter-spacing: 0.8px;
      text-align: left;
      color: #7d7d7d;
    }
    .envoy-portrait {
      position: absolute;
      top: -80px;
      right: -230px;
    }
    .host-portrait {
      position: absolute;
      top: -60px;
      right: -180px;
    }
  }
}
.footer {
  margin: auto;
  max-width: 1300px;
  text-align: center;
}
.footer-img {
  max-width: 100%;
}
.query-donate-btn {
  font-size: 20px;
  line-height: 1.5;
  border: 3px #0085c4 solid;
  border-radius: 24px;
  padding: 4px 24px;
  color: #036eb7;
  &:hover {
    color: #fff;
    background-color: #0085c4;
  }
}
.query-form {
  .form-info {
    padding: 6px;
  }
}
@media screen and (max-width: 1300px) {
  .img-whale {
    width: 200px;
    right: -30px;
    top: -10px;
  }
}
@media screen and (max-width: 944px) {
  .banner {
    margin-top: 40px;
  }
}
@media screen and (max-width: 767px) {
  .img-icon {
    margin-right: 8px;
    width: 60px;
  }
  .img-text-1 {
    width: 245px;
  }
  .img-text-2 {
    width: 125px;
  }
  .img-text-3 {
    width: 125px;
  }
  .banner {
    margin-top: 0;
    margin-bottom: 30px;
  }
  .page-title-1 {
    margin-right: 0;
  }
  .yt-iframe {
    width: 95vw;
  }
  .yt-top {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 30px;
    .yt-top-icon {
      width: 46px;
      height: 46px;
      background-image: url('../asset/images/NTUCH02F.png');
      background-repeat: no-repeat;
      background-size: contain;
      background-position: center center;
    }
    .yt-top-text {
      margin: 0 6px;
      font-size: 18px;
    }
    .yt-top-donate {
      font-size: 20px;
      padding: 2px 12px;
    }
  }
  .dinosaurs-img {
    max-width: 85%;
    margin-bottom: 20px;
    display: block;
    margin-left: auto;
  }
  .dinosaurs-text {
    padding: 0 35px;
  }
  .content-text {
    margin: 30px auto;
    font-size: 16px;
  }
  .organizer {
    margin: 30px 0;
    padding: 0 10px;
  }
  .organizer-title {
    margin-right: 0;
  }
  .active-title {
    font-size: 24px;
  }
  .physical {
    .physical-title-text {
      margin-bottom: 25px;
      font-size: 18px;
    }
    .physical-info-text {
      margin-bottom: 10px;
      margin-left: 20px;
      font-size: 16px;
    }
    .hint {
      padding-left: 50px;
    }
    .go-apply {
      .go-donate-title {
        font-size: 16px;
      }
      .img-heart {
        width: 60px;
      }
      .img-rocket {
        position: absolute;
        width: 70px;
        left: 10px;
        top: 50%;
      }
      .go-donate-button {
        font-size: 15px;
      }
    }
  }
  .fund-img {
    width: 210px;
  }
  .fund-text {
    width: 300px;
    margin: 0 auto 20px;
    font-size: 16px;
    .img-dec-1 {
      width: 150px;
      top: 50px;
      right: -50px;
    }
  }
  .donate-step {
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: 1fr 1fr 1fr;
    .step-item {
      padding: 10px;
      font-size: 12px;
      line-height: 1.5;
    }
  }
  .donate-info {
    margin: 15px 0;
    font-size: 20px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.8;
    letter-spacing: 1px;
    text-align: center;
    color: #000;
    .hint {
      color: #0666b2;
    }
  }

  .donate-text {
    margin: 0 auto;
    width: 300px;
    font-size: 14px;
    .img-dec-2 {
      width: 80px;
      top: -70px;
      left: 200px;
    }
    .img-dec-3 {
      height: 177px;
      right: -55px;
      bottom: 7px;
    }
  }
  .how-title {
    margin-right: 0;
  }
  .go-donate-out {
    .img-dec-4 {
      width: 95px;
      right: 0;
      bottom: 0;
    }
    .img-dec-5 {
      width: 55px;
      left: 20px;
      bottom: 0;
    }
    .img-dec-6 {
      width: 55px;
      left: 0;
      bottom: 40px;
    }
  }
  .img-whale {
    width: 130px;
    right: 10px;
    top: -40px;
  }
  .go-donate {
    position: relative;
    margin: 30px auto;
    height: 210px;
    width: 300px;
    .go-donate-title {
      width: 145px;
    }
    .go-donate-bird {
      height: 80px;
      left: -10px;
      top: 90px;
    }
    .go-donate-button {
      margin: 5px 0 0 5px;
    }
  }
  .envoy {
    max-width: 95%;
    margin: 0 12px;
    .envoy-title {
      .img-icon {
        width: 65px;
      }
      margin-right: 0;
      margin-bottom: 10px;
    }
    .envoy-intro {
      width: 75%;
      padding: 15px;
      .envoy-intro-title {
        font-size: 20px;
        line-height: 1.5;
      }
      .envoy-intro-name {
        font-size: 22px;
        line-height: 1.6;
      }
      .envoy-intro-text {
        max-width: 175px;
        font-size: 14px;
      }
      .envoy-portrait {
        top: 20px;
        right: -165px;
        height: 420px;
      }
      .host-portrait {
        position: absolute;
        top: 30px;
        right: -115px;
        height: 380px;
        width: 200px;
      }
    }
  }
}
</style>
