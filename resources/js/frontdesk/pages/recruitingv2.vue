<template>
  <main class="recruiting">
    <section class="banner">
      <button
        target="_blank"
        class="btn-interview"
        @click="goInfoPage()"
      >
        填寫面試資料表
      </button>
      <div v-if="showLoginModal" id="askLoginModal" class="modal-content">
        <div class="modal-body">
          <button type="button" class="close" @click="closeModal()">✕</button>
          <span class="em-1">提醒您</span><br/><br/>
          <span class="dot"></span>
          <span class="em-2">成為普匯會員，才能進行填寫</span><br/>
          <span class="em-3">( 點選網頁右上角，進行會員註冊 )</span>
        </div>
      </div>
    </section>
    <section class="intro">
      <h1 class="h1">挑戰自我 超越自我</h1>
      <div class="block-content">
        <div class="d-md-block d-none">
          <div>
            歡迎一起加入年輕活潑的「<span class="em-1">普匯金融科技</span
            >」家族！
          </div>
          <div class="my-4">
            加入
            <span class="em-1">普匯inFlux</span>
            ，參與<span class="em-1">國際金融科技</span>盛會，<br />
            共同創新<span class="em-2">金融產品</span>、<span class="em-2"
              >開發AI自動化風控</span
            >、 <span class="em-2">導入區塊鏈</span>，<br />
            推廣<span class="em-2">數位行銷</span>、善用<span class="em-2"
              >社群社團經營</span
            >， 享受<span class="em-2">自由創意</span>，<br />
            創造<span class="em-2">社會價值</span>！
          </div>
          <div>
            <span class="em-1">普匯inFlux</span>
            ，邀請您和我們一起燃燒熱情、<br />
            激發潛力、創造公益價值，共同成長、<br />
            共同邁向國際大舞台！
          </div>
        </div>
        <div class="d-md-none d-block mx-4">
          <div>
            歡迎一起加入年輕活潑的<br />
            「<span class="em-1">普匯金融科技</span>」家族！
          </div>
          <div class="my-4">
            加入<span class="em-1">普匯inFlux</span>，<br />
            參與<span class="em-1">國際金融科技</span>盛會，<br />
            共同創新<span class="em-2">金融產品</span>、<br />
            <span class="em-2">開發AI自動化風控</span>、 <br />
            <span class="em-2">導入區塊鏈</span>，<br />
            推廣<span class="em-2">數位行銷</span>、<br />
            善用<span class="em-2">社群社團經營</span>，<br />
            享受<span class="em-2">自由創意</span>，<br />
            創造<span class="em-2">社會價值</span>！<br />
          </div>
          <div>
            <span class="em-1">普匯inFlux，</span><br />
            邀請您和我們一起燃燒熱情、<br />
            激發潛力、<br />
            創造公益價值，共同成長、<br />
            共同邁向國際大舞台！
          </div>
        </div>
        <div class="pl-md-5 pl-0">
          <img
            src="@/asset/images/recuirt/recruit-intro.png"
            class="img-fluid"
          />
        </div>
      </div>
    </section>
    <section class="benefit">
      <h1 class="h1">福利與好康</h1>
      <div class="block-content">
        <div class="row no-gutters">
          <div class="col-md col-12">
            <img
              src="@/asset/images/recuirt/recruit-benefit.png"
              class="img-fluid"
            />
          </div>
          <div class="col-md-6 col-12 p-md-3 p-0 pt-4 benefit-list-group">
            <div class="benefit-list">•勞保、健保、勞退等基本規範</div>
            <div class="benefit-list">•舒適的工作環境</div>
            <div class="benefit-list">•完善業務獎金制度</div>
          </div>
        </div>
        <div class="benefit-group">
          <div class="benefit-item">
            <div class="benefit-item-icon">
              <img
                src="@/asset/images/recuirt/recuirt-icon-1.svg"
                class="img-fluid"
              />
            </div>
            <div class="benefit-item-text">三節皆有禮品</div>
          </div>
          <div class="benefit-item">
            <div class="benefit-item-icon">
              <img
                src="@/asset/images/recuirt/recuirt-icon-2.svg"
                class="img-fluid"
              />
            </div>
            <div class="benefit-item-text">每月壽星慶生</div>
          </div>
          <div class="benefit-item">
            <div class="benefit-item-icon">
              <img
                src="@/asset/images/recuirt/recuirt-icon-3.svg"
                class="img-fluid"
              />
            </div>
            <div class="benefit-item-text">免費咖啡提神</div>
          </div>
          <div class="benefit-item">
            <div class="benefit-item-icon">
              <img
                src="@/asset/images/recuirt/recuirt-icon-4.svg"
                class="img-fluid"
              />
            </div>
            <div class="benefit-item-text">
              <div class="d-md-block d-none">每月補充零食櫃</div>
              <div class="d-md-none d-block">
                <div>每月補充</div>
                <div>零食櫃</div>
              </div>
            </div>
          </div>
          <div class="benefit-item">
            <div class="benefit-item-icon">
              <img
                src="@/asset/images/recuirt/recuirt-icon-5.svg"
                class="img-fluid"
              />
            </div>
            <div class="benefit-item-text">
              <div class="d-md-block d-none">每月部門聚餐額度</div>
              <div class="d-md-none d-block">
                <div>每月部門</div>
                <div>聚餐額度</div>
              </div>
            </div>
          </div>
          <div class="benefit-item">
            <div class="benefit-item-icon">
              <img
                src="@/asset/images/recuirt/recuirt-icon-6.svg"
                class="img-fluid"
              />
            </div>
            <div class="benefit-item-text">年度健康檢查</div>
          </div>
          <div class="benefit-item">
            <div class="benefit-item-icon">
              <img
                src="@/asset/images/recuirt/recuirt-icon-7.svg"
                class="img-fluid"
              />
            </div>
            <div class="benefit-item-text">
              <div class="d-md-block d-none">金融科技教育訓練</div>
              <div class="d-md-none d-block">
                <div>金融科技</div>
                <div>教育訓練</div>
              </div>
            </div>
          </div>
          <div class="benefit-item">
            <div class="benefit-item-icon">
              <img
                src="@/asset/images/recuirt/recuirt-icon-8.svg"
                class="img-fluid"
              />
            </div>
            <div class="benefit-item-text">
              <div class="d-md-block d-none">多家企業/銀行合作機會</div>
              <div class="d-md-none d-block">
                <div>多家企業</div>
                <div>銀行合作機會</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="link-section">
      <a
        href="https://www.104.com.tw/company/1a2x6bkhzg"
        target="_blank"
        class="btn-link"
      >
        搜尋所有職缺
      </a>
    </section>
  </main>
</template>

<script>
export default {
  data() {
    return {
      showLoginModal: false
    }
  },
  created() {
    $('title').text(`徵才服務 - inFlux普匯金融科技`);
  },
  methods: {
    goInfoPage() {
      if (sessionStorage.length === 0 || sessionStorage.flag === 'logout') {
        this.showLoginModal = true;
      } else {
        this.$router.push('/recruiting/info-form');
      }
    },
    closeModal() {
      this.showLoginModal = false;
    }
  }
}
</script>

<style lang="scss" scoped>
$color--primary: #036eb7;
$color__text--primary: #023d64;
$color__background--primary: #f3f9fc;

.btn-interview {
  // position: absolute;
  position: relative;
  top: 78%;
  left: 20%;
  padding: 9px 30px;
  font-style: normal;
  font-weight: 500;
  font-size: 26px;
  line-height: 1.4;
  color: #ffffff;
  background: #F29500;
  border-color: #F29500;
  border-radius: 15px;
}

#askLoginModal {
  position: fixed;
  width: 320px;
  height: 180px;
  margin: 15% 40%;
  padding: 0px 0.5rem;
  
  .modal-body {
    text-align: center;

    .dot {
      margin-right: 5px;
      height: 10px;
      width: 10px;
      background-color: #F29600;
      border-radius: 50%;
      display: inline-block;
    }
    .em-1 {
      color: #036EB7;
      font-weight: 500;
      font-size: 24px;
      line-height: 35px;
    }
    .em-2 {
      font-weight: 500;
      font-size: 18px;
      line-height: 26px;
    }
    .em-3 {
      font-weight: 500;
      font-size: 16px;
      line-height: 23px;
      color: #707070;
    }
  }
}

.btn-link {
  padding: 9px 40px;
  font-style: normal;
  font-weight: 700;
  font-size: 28px;
  line-height: 1.4;
  color: #ffffff;
  background: #083a6e;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25),
    2px 4px 11px rgba(2, 71, 118, 0.16);
  border-radius: 12px;
}
.h1 {
  text-align: center;
  font-style: normal;
  font-weight: 700;
  font-size: 56px;
  line-height: 1.4;
}
.h2 {
  text-align: center;
  font-style: normal;
  font-weight: 500;
  font-size: 40px;
  line-height: 1.4;
}
.block-content {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
}
.banner {
  height: 40vw;
  background-image: url('~images/recuirt/recuirt-banner.png');
  background-size: cover;
  background-position: center;
}
.intro {
  margin: 76px 0 50px;
  font-style: normal;
  font-weight: 700;
  font-size: 28px;
  line-height: 1.4;
  color: rgba(107, 107, 107, 0.702);
  .h1 {
    color: $color--primary;
  }
  .block-content {
    display: grid;
    grid-template-columns: 680px 1fr;
    margin-top: 75px;
  }
  .em-1 {
    color: #144386;
  }
  .em-2 {
    color: #f2b162;
  }
}
.benefit {
  padding: 26px 0;
  background-color: #f3f9fc;
  .h1 {
    color: $color--primary;
  }
  .block-content {
    padding: 35px 0;
    background-image: url('~images/recuirt/recruit-benefit-bg.svg');
    background-repeat: no-repeat;
    background-position: right -15px top;
  }
  .benefit-list {
    margin-bottom: 22px;
    font-style: normal;
    font-weight: 700;
    font-size: 36px;
    text-align: left;
    color: $color--primary;
  }
  .benefit-group {
    display: grid;
    margin-top: 80px;
    grid-template-columns: repeat(4, 334px);
    gap: 70px 25px;
    .benefit-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      &-icon {
        background: rgba(174, 222, 248, 0.5686);
        border-radius: 20px;
        width: 200px;
        height: 200px;
        margin-bottom: 24px;
        display: grid;
        place-items: center;
      }
      &-text {
        font-style: normal;
        font-weight: 700;
        font-size: 32px;
        line-height: 115%;
        color: #036fb7;
      }
    }
  }
}
.link-section {
  display: grid;
  place-items: center;
  padding: 100px;
}
@media screen and (max-width: 767px) {
  .h1 {
    font-size: 36px;
    line-height: 1.4;
  }
  .block-content {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 15px;
  }
  .banner {
    height: 140vw;
    background-image: url('~images/recuirt/recuirt-banner-phone.png');
  }
  .intro {
    margin: 24px 0;
    font-size: 24px;
    .block-content {
      display: flex;
      flex-direction: column-reverse;
      margin-top: 25px;
      gap: 25px;
    }
  }
  .benefit {
    padding: 26px 0;
    .block-content {
      background-image: none;
      padding: 30px 15px 50px;
    }
    .benefit-list-group {
      background-image: url('~images/recuirt/recruit-benefit-bg-phone.svg');
      background-size: cover;
      background-position: top center;
      background-repeat: no-repeat;
    }
    .benefit-list {
      margin-bottom: 6px;
      font-size: 24px;
    }
    .benefit-group {
      display: grid;
      margin-top: 80px;
      grid-template-columns: repeat(2, 1fr);
      gap: 25px 0;
      .benefit-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        &-icon {
          width: 140px;
          height: 140px;
          padding: 20px;
          margin-bottom: 16px;
        }
        &-text {
          font-size: 24px;
          text-align: center;
        }
      }
    }
  }
  .link-section {
    padding: 80px 0;
  }
}
</style>
