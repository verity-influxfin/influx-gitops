<template>
  <div class="work-loan">
    <div class="banner">
      <img
        src="@/asset/images/collegeLoan/college-cover.png"
        alt="普匯上班族貸banner"
        class="img-fluid d-sm-block d-none"
      />
    </div>
    <div class="features">
      <div class="block-content">
        <h1 class="h1">隨意貸 輕鬆還</h1>
        <div class="row justify-content-center no-gutters">
          <div class="feature-grid">
            <div class="card" data-aos="fade-up">
              <div class="card-body">
                <div class="card-cover">
                  <img
                    src="@/asset/images/workloan/feature-icon-1.png"
                    class="img-fluid mx-auto"
                  />
                </div>
                <div class="card-title">利率最低5%起</div>
                <div class="card-divider"></div>
              </div>
            </div>
            <div class="card" data-aos="fade-up">
              <div class="card-body">
                <div class="card-cover">
                  <img
                    src="@/asset/images/workloan/feature-icon-2.png"
                    class="img-fluid mx-auto"
                  />
                </div>
                <div class="card-title">絕無隱藏費用</div>
                <div class="card-divider"></div>
              </div>
            </div>
            <div class="card" data-aos="fade-up">
              <div class="card-body">
                <div class="card-cover">
                  <img
                    src="@/asset/images/workloan/feature-icon-3.png"
                    class="img-fluid mx-auto"
                  />
                </div>
                <div class="card-title">借款最長24期</div>
                <div class="card-divider"></div>
              </div>
            </div>
            <div class="card" data-aos="fade-up">
              <div class="card-body">
                <div class="card-cover">
                  <img
                    src="@/asset/images/workloan/feature-icon-4.png"
                    class="img-fluid mx-auto"
                  />
                </div>
                <div class="card-title">每期償還本息</div>
                <div class="card-divider"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row no-gutters justify-content-center">
          <div class="feature-suit">
            <div class="d-flex align-items-center suit-title-container">
              <div class="suit-title">適用對象</div>
            </div>
            <div class="suit-item">•20~45歲</div>
            <div class="suit-item">•持雙證件自拍上傳</div>
            <div class="suit-item">•提供基本收入資料</div>
            <div class="suit-item">•信用良好（無催收/呆帳紀錄）</div>
          </div>
        </div>
        <h1 class="h1">超過10萬用戶使用</h1>
        <h2 class="h2">體驗超簡便快速、安全又隱私的金融科技貸款服務</h2>
        <div class="feature-user">
          <div class="card" data-aos="fade-up" data-aos-duration="800">
            <div class="bg-text">
              <div>ENTERPRISE</div>
              <div>EMPLOYEES</div>
            </div>
            <div class="card-body">
              <div class="portrait">
                <img
                  src="@/asset/images/workloan/feature-user-1.png"
                  class="img-fluid"
                />
              </div>
              <div class="portrait-info">一般企業員工</div>
              <div class="card-text">
                <div>• 不論公司規模，收入穩定皆適用！</div>
                <div>• 不管生活急用、資金周轉</div>
                <div>• 添購家居、車輛或小孩教養金</div>
              </div>
            </div>
          </div>
          <div class="card" data-aos="fade-up" data-aos-duration="800">
            <div class="bg-text">
              <div>FINANCIAL</div>
              <div>INSTITUTION</div>
            </div>
            <div class="card-body">
              <div class="portrait">
                <img
                  src="@/asset/images/workloan/feature-user-2.png"
                  class="img-fluid"
                />
              </div>
              <div class="portrait-info">金融機構員工</div>
              <div class="card-text">
                <div>• 證券、銀行、保險業皆適用</div>
                <div>• 投資理財，股票交割…</div>
                <div>• 快速取得資金，獲利加倍！</div>
              </div>
            </div>
          </div>
          <div class="card" data-aos="fade-up" data-aos-duration="800">
            <div class="bg-text">
              <div>PUBLIC</div>
              <div>INSTITUTION</div>
            </div>
            <div class="card-body">
              <div class="portrait">
                <img
                  src="@/asset/images/workloan/feature-user-3.png"
                  class="img-fluid"
                />
              </div>
              <div class="portrait-info">公家機關優質貸</div>
              <div class="card-text">
                <div>• 學校、醫院從業人員皆適用</div>
                <div>• 借款記錄不上聯徵，不影響信用</div>
                <div>• 全程免照會，無人化安全又隱私！</div>
              </div>
            </div>
          </div>
          <div class="card" data-aos="fade-up" data-aos-duration="800">
            <div class="bg-text">
              <div>LISTED</div>
              <div>COMPANIES</div>
            </div>
            <div class="card-body">
              <div class="portrait">
                <img
                  src="@/asset/images/workloan/feature-user-4.png"
                  class="img-fluid"
                />
              </div>
              <div class="portrait-info">上市櫃企業菁英貸</div>
              <div class="card-text">
                <div>• 快速審核、持證自拍</div>
                <div>• 免跑銀行、免受干擾</div>
                <div>• 台積電、日月光等大企業</div>
                <div>• 都選擇使用普匯APP！</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import featureCard from '../component/index/featureCard'
export default {
  components: {
    featureCard,
  },
}
</script>
<style lang="scss">
.radio-custom-work {
  display: contents;
  label {
    display: inline-block;
    position: relative;
    padding-left: 23px;
    cursor: pointer;
    width: auto;
    margin-right: 20px;
    line-height: 29px;
  }

  input {
    height: 1px;
    width: 1px;
    opacity: 0;
  }

  .work-input-outside {
    display: inline-block;
    position: absolute;
    left: 0%;
    top: 30%;
    margin-top: 0;
    width: 22px;
    height: 22px;
    border: 2px solid #036eb7;
    border-radius: 12px;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    background: #fff;
  }

  .work-input-inside {
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    display: inline-block;
    border-radius: 12px;
    width: 12px;
    height: 12px;
    background: #036eb7;
    left: 3px;
    top: 3px;
    -webkit-transform: scale(0, 0);
    transform: scale(0, 0);
  }
  .no-transforms .work-input-inside {
    left: auto;
    top: auto;
    width: 0;
    height: 0;
  }

  input:checked + .work-input-outside .work-input-inside {
    -webkit-animation: radio-select 0.1s linear;
    animation: radio-select 0.1s linear;
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
  }
  .no-transforms input:checked + .work-input-outside .work-input-inside {
    width: 10px;
    height: 10px;
  }
}
@media screen and (max-width: 767px) {
  .radio-custom-work label {
    margin-right: 10px;
  }
  .radio-custom-work .work-input-outside {
    margin-top: -6px;
    width: 22px;
    height: 22px;
  }
  .radio-custom-work .work-input-inside {
    width: 16px;
    height: 16px;
    top: 1px;
    left: 1px;
  }
}
</style>
<style lang="scss" scoped>
$color--primary: #036eb7;
$color__text--primary: #023d64;
$color__background--primary: #f3f9fc;
$color--primary: #036eb7;
$color__text--primary: #023d64;
$color__background--primary: #f3f9fc;
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
.features {
  padding: 100px;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
  .feature-grid {
    display: grid;
    margin: 84px auto 0;
    grid-template-columns: repeat(4, 260px);
    gap: 32px;
    .card {
      width: 100%;
      border: none;
      box-shadow: 0px 4px 12px rgba(21, 91, 153, 0.16);
      border-radius: 16px;
      .card-body {
        padding: 50px 32px;
      }
      .card-cover {
        display: flex;
        width: 84px;
        margin: 0 auto 16px;
        padding: 14px;
        background: #cdeafe;
        border-radius: 6px;
      }
      .card-title {
        font-style: normal;
        font-weight: 700;
        font-size: 28px;
        text-align: center;
        color: #023d64;
      }
      .card-divider {
        width: 39px;
        margin: 8px auto;
        border-bottom: 3px solid #036fb7;
        border-radius: 100px;
      }
    }
  }
  .feature-suit {
    margin: 32px 0 120px;
    width: 100%;
    max-width: 1136px;
    padding: 34px 31px;
    background: linear-gradient(90deg, #3781c7 0%, #74bbe4 100%);
    border-radius: 16px;
    display: grid;
    grid-template-columns: auto 1fr auto;
    grid-template-rows: 1fr 1fr;
    gap: 16px;
    .suit-title-container {
      grid-area: 1 / 1 / 3 / 2;
    }
    .suit-title {
      font-style: normal;
      font-weight: 700;
      font-size: 48px;
      line-height: 1.4;
      height: 70px;
      color: #ffffff;
      border-right: 3px solid $color--primary;
      padding-right: 48px;
    }
    .suit-item {
      font-style: normal;
      font-weight: 500;
      font-size: 32px;
      line-height: 1.4;
      color: #ffffff;
    }
  }
  .feature-user {
    display: grid;
    gap: 46.5px;
    margin-top: 150px;
    grid-template-columns: repeat(4, 328px);
    .card {
      width: 100%;
      border: none;
      background: linear-gradient(90deg, #3781c7 0%, #74bbe4 100%);
      border-radius: 16px;
      position: relative;
      .bg-text {
        position: absolute;
        z-index: 1;
        font-family: 'Arial';
        font-style: normal;
        font-weight: 700;
        font-size: 50px;
        line-height: 1.4;
        letter-spacing: 0.1em;
        color: #60a2d4;
        opacity: 0.64;
        top: 120px;
        left: -120px;
        transform: rotate(90deg);
      }
      .card-body {
        z-index: 2;
        padding-top: 150px;
        height: 400px;
        .portrait {
          position: absolute;
          top: -95px;
          right: 0;
          z-index: -1;
        }
        .portrait-info {
          font-style: normal;
          font-weight: 700;
          font-size: 24px;
          line-height: 1.4;
          color: $color--primary;
          padding: 4px 16px;
          background: #ffffff;
          width: fit-content;
        }
        .card-text {
          margin-top: 18px;
          font-style: normal;
          font-weight: 500;
          font-size: 16px;
          line-height: 2;
          color: #ffffff;
        }
      }
    }
  }
}
</style>
