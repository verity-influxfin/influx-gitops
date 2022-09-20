<template>
  <main class="recruiting">
    <section class="banner"></section>
    <section class="intro">
      <h1 class="h1">挑戰自我 超越自我</h1>
      <div class="block-content">
        <div>
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
        <div class="pl-5">
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
          <div class="col">
            <img
              src="@/asset/images/recuirt/recruit-benefit.png"
              class="img-fluid"
            />
          </div>
          <div class="col-6 p-3">
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
            <div class="benefit-item-text">每月補充零食櫃</div>
          </div>
          <div class="benefit-item">
            <div class="benefit-item-icon">
              <img
                src="@/asset/images/recuirt/recuirt-icon-5.svg"
                class="img-fluid"
              />
            </div>
            <div class="benefit-item-text">每月部門聚餐額度</div>
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
            <div class="benefit-item-text">金融科技教育訓練</div>
          </div>
          <div class="benefit-item">
            <div class="benefit-item-icon">
              <img
                src="@/asset/images/recuirt/recuirt-icon-8.svg"
                class="img-fluid"
              />
            </div>
            <div class="benefit-item-text">多家企業/銀行合作機會</div>
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
  created() {
    this.getBannerData();
    $('title').text(`徵才服務 - inFlux普匯金融科技`);
  },
}
</script>

<style lang="scss" scoped>
$color--primary: #036eb7;
$color__text--primary: #023d64;
$color__background--primary: #f3f9fc;
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
    margin-top: 35px;
    padding-bottom: 35px;
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
</style>
