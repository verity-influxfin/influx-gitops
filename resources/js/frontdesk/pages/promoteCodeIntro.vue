<template>
  <div class="promote-code-intro">
    <div class="row no-gutters banner-row">
      <img src="../asset/images/promoteCode/banner.jpg" class="banner" />
      <router-link to="/borrowLink" target="_blank">
        <button class="btn apply-btn">立即加入</button>
      </router-link>
    </div>
    <div class="row-1">
      <div class="row-title">獎金輕鬆賺，越分享越划算</div>
      <div class="cards">
        <div class="card">
          <div class="card-title">學生貸</div>
          <div class="card-info">成交 <span class="em">$200</span> /筆</div>
        </div>
        <div class="card">
          <div class="card-title">上班族貸</div>
          <div class="card-info">成交 <span class="em">$400</span>/筆</div>
        </div>
        <div class="card">
          <div class="card-title">微企e秒貸</div>
          <div class="card-info">成交 <span class="em">$1000</span>/筆</div>
        </div>
      </div>
      <div class="row-title-2">
        只需分享 成功推薦 <span class="em">首貸獎金無上限！</span>
      </div>
      <div class="coop">
        <div class="col-auto title">
          誰能成為<br />
          合作夥伴？
        </div>
        <div class="col coop-item">
          <div class="left">
            <div class="coop-icon">
              <img
                src="../asset/images/promoteCode/personal.svg"
                alt=""
                class="mx-auto"
                style="width: 55px"
              />
            </div>
            <div class="coop-icon-info">個人</div>
          </div>
          <div class="right">
            <div class="coop-title">不限資格 不限身分</div>
            <div class="coop-info">
              下載APP→完成註冊認證→拍照上傳良民證 →線上簽約合作夥伴！
            </div>
          </div>
        </div>
        <div class="col coop-item">
          <div class="left" style="width: 70px">
            <div class="coop-icon">
              <img
                src="../asset/images/promoteCode/company.svg"
                alt=""
                class="mx-auto"
                style="width: 55px"
              />
            </div>
            <div class="coop-icon-info">公司行號</div>
          </div>
          <div class="right">
            <div class="coop-info">
              下載APP→完成公司行號註冊認證 →線上簽約合作夥伴！
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="step-row">
      <div class="step-title">三步驟・智「<span class="em">匯</span>」賺錢</div>
      <div class="step-item-row">
        <div class="col-auto step-item">
          <div class="step-item-title">簡單下載註冊</div>
          <div class="step-item-cover">
            <img
              src="../asset/images/promoteCode/1.png"
              style="width: 250px"
              alt=""
            />
          </div>
          <div class="step-item-info">
            下載登入普匯APP<br />
            點擊「我的」進入「推薦有賞」
          </div>
        </div>
        <div class="col-auto step-item">
          <div class="step-item-title">完成線上認證</div>
          <div class="step-item-cover">
            <img
              src="../asset/images/promoteCode/2.png"
              style="width: 250px"
              alt=""
            />
          </div>
          <div class="step-item-info">
            良民證、金融及實名認證<br />
            全線上智能簽約
          </div>
        </div>
        <div class="col-auto step-item">
          <div class="step-item-title">開始賺取獎金</div>
          <div class="step-item-cover">
            <img
              src="../asset/images/promoteCode/3.png"
              style="width: 250px"
              alt=""
            />
          </div>

          <div class="step-item-info">
            個性化專屬 QR code<br />
            輕鬆分享、大賺獎金
          </div>
        </div>
      </div>
    </div>
    <div class="benfit-row no-gutters">
      <div class="content">
        <div class="benfit-title">
          <span class="line">用FinTech創造被動收入</span><br />
          <span class="line2">普匯•金融科技</span>的好夥伴
        </div>
        <div class="benfits">
          <div class="benfit">全年無休 24⼩時隨時隨地皆可賺錢</div>
          <div class="benfit">AI認證審核，立即產生專屬 QR code</div>
          <div class="benfit">月底結算獎金，隔月10號自動入帳</div>
        </div>
      </div>
      <router-link to="/borrowLink" target="_blank">
        <button class="btn apply-btn apply-now">立即加入成為夥伴</button>
      </router-link>
    </div>

    <div class="slogon no-gutters">
      賺錢也要跟上時代 <span class="blue">普匯•你的手機ATM</span>
    </div>
  </div>
</template>

<script>
export default {
}
</script>

<style lang="scss" scoped>
.promote-code-intro {
  line-height: 1.25;
  .banner-row {
    position: relative;
    .banner {
      width: 100%;
    }
    .apply-btn {
      position: absolute;
      bottom: 18%;
      right: 18%;
    }
  }
  .row-1 {
    margin: 36px auto;
    max-width: 900px;
    .row-title {
      text-align: center;
      color: #153a71;
      font-size: 32px;
      margin: 20px 0;
    }
    .cards {
      display: flex;
      justify-content: center;
      gap: 30px;
      margin: 20px 0;
      .card {
        border-radius: 12px;
        border: 1px solid #e7e7e7;
        padding: 16px 38px;
        line-height: 1.2;
        .card-title {
          text-align: center;
          color: #404040;
          font-size: 20px;
        }
        .card-info {
          font-size: 20px;
          color: #868686;
        }
        .em {
          font-size: 32px;
          margin: 0 6px;
        }
      }
    }
  }
  .row-title-2 {
    text-align: center;
    color: #153a71;
    font-size: 24px;
    line-height: 1.2;
    margin: 20px 0;
  }
  .coop {
    display: flex;
    background-color: #f9f9f9;
    border-radius: 12px;
    padding: 30px 15px;
    margin: 20px 0;
    .title {
      color: #153a71;
      font-size: 24px;
      line-height: 1.5;
    }
    .coop-item {
      display: flex;
      .coop-icon {
        margin-bottom: 5px;
      }
      .coop-icon-info {
        text-align: center;
        width: inherit;
      }
      .right {
        margin-left: 10px;
        flex-direction: column;
        display: flex;
        justify-content: center;
        .coop-title {
          color: #404040;
          font-size: 20px;
          margin: 10px 0 10px;
        }
        .coop-info {
          color: #868686;
          font-size: 14px;
        }
      }
    }
  }
  .step-row {
    background-color: #153a71;
    padding: 30px 0;
    color: #fff;
    .step-title {
      font-size: 32px;
      text-align: center;
    }
    .step-item-row {
      display: flex;
      justify-content: center;
      gap: 50px;
      max-width: 900px;
      margin: 20px auto;
      .step-item {
        padding: 20px 5px 0 20px;
        position: relative;
        text-align: center;
        .step-item-title {
          font-size: 20px;
          position: absolute;
          right: 0;
        }
        .step-item-cover {
          padding: 40px 0;
        }
        .step-item-info {
          font-size: 14px;
          position: absolute;
          width: max-content;
          right: 0;
          bottom: -15px;
        }
      }
    }
  }
  .benfit-row {
    background-image: url("../asset/images/promoteCode/banner2.jpg");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center bottom;
    height: 666px;
    position: relative;
    padding: 30px;
    .content {
      max-width: 1100px;
      margin: auto;
    }
    .benfit-title {
      padding-top: 20px;
      color: #153a71;
      font-size: 44px;
      line-height: 1.7;
      .line {
        padding: 4px;
        border-bottom: #153a71 solid 2px;
      }
      .line2 {
        padding: 4px;
        border-bottom: #153a71 solid 1px;
      }
    }
    .benfit {
      display: flex;
      align-items: center;
      font-size: 28px;
      margin: 50px 0;
      color: #868686;
      &::before {
        content: "";
        background-size: cover;
        background-image: url("../asset/images/promoteCode/check.svg");
        display: inline-block;
        height: 32px;
        width: 32px;
        margin-right: 12px;
      }
    }
    .apply-now {
      position: absolute;
      left: 35%;
      bottom: -30px;
    }
  }
  .slogon {
    margin: auto;
    width: 900px;
    text-align: center;
    padding: 60px 0;
    font-size: 42px;
    letter-spacing: 5px;
  }
  .apply-btn {
    font-family: Noto Sans TC;
    font-style: normal;
    font-weight: 500;
    font-size: 36px;
    line-height: 1.5;
    color: #fff;
    background: #f29600;
    padding: 5px 22px;
    border-radius: 12px;
  }
  .em {
    color: #f29600;
  }
  .blue {
    color: #153a71;
  }
}
</style>
