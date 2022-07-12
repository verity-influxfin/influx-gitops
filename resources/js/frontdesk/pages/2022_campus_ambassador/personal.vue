<template>
  <div class="personal">
    <!-- top -->
    <div class="page-header">
      <div class="page-header-title">
        <div class="page-header-title-text">個人組</div>
        <div class="page-header-title-underline"></div>
      </div>
    </div>
    <!-- step-intro -->
    <div class="step-intro">
      <div class="intro-text">
        <div>準備好成為普匯金融科技校園大使了嗎？</div>
        <div>快讓普匯更了解你吧！</div>
        <div>個人報名入選後將由普匯進行分組</div>
      </div>
      <div class="rating-table-title">徵選評分項目</div>
      <div class="rating-table">
        <div class="rating-row">
          <div class="rating-item">項目</div>
          <div class="rating-content">評選內容</div>
          <div class="rating-percent">佔比</div>
        </div>
        <div class="rating-row">
          <div class="rating-item">生活照</div>
          <div class="rating-content">個人形象</div>
          <div class="rating-percent">10%</div>
        </div>
        <div class="rating-row">
          <div class="rating-item">自我介紹</div>
          <div class="rating-content">
            <div>200~500字</div>
            <div>一句話行銷自己</div>
          </div>
          <div class="rating-percent">15%</div>
        </div>
        <div class="rating-row">
          <div class="rating-item">個人社群</div>
          <div class="rating-content">
            <div>FB/IG/部落格/其它</div>
            <div>(需設定公開)</div>
          </div>
          <div class="rating-percent">15%</div>
        </div>
        <div class="rating-row">
          <div class="rating-item">普匯知多少</div>
          <div class="rating-content">普匯相關問題</div>
          <div class="rating-percent">15%</div>
        </div>
        <div class="rating-row">
          <div class="rating-item">
            <div>校園推廣</div>
            <div>企劃提案</div>
          </div>
          <div class="rating-content">
            <div>活動目的、活動內容、</div>
            <div>執行方式、人力&預算</div>
          </div>
          <div class="rating-percent">25%</div>
        </div>
        <div class="rating-row">
          <div class="rating-item">作品集</div>
          <div class="rating-content">
            <div>圖文作品/影音作品</div>
            <div>活動企劃/其他作品集</div>
          </div>
          <div class="rating-percent">20%</div>
        </div>
      </div>
      <div class="rating-check">
        <label>
          <input type="checkbox" />
          本人已閱讀並同意詳情活動辦法及說明
        </label>
      </div>
      <div class="rating-start">
        <button class="btn btn-campus-primary">開始報名</button>
      </div>
    </div>
    <!-- step-header -->
    <div class="step-header">
      <div class="num-grid">
        <div class="step-header-num active">1</div>
        <div class="step-header-arrow">
          <img src="@/asset/images/2022_campus_ambassador/Arrow 1.svg" />
        </div>
        <div class="step-header-num">2</div>
        <div class="step-header-arrow">
          <img src="@/asset/images/2022_campus_ambassador/Arrow 1.svg" />
        </div>
        <div class="step-header-num">3</div>
        <div class="step-header-arrow">
          <img src="@/asset/images/2022_campus_ambassador/Arrow 1.svg" />
        </div>
        <div class="step-header-num">4</div>
      </div>
      <div class="title-grid">
        <div class="step-header-text active">基本資料</div>
        <div class="step-header-text">推廣你自己</div>
        <div class="step-header-text">普匯知多少</div>
        <div class="step-header-text">提案&作品</div>
      </div>
    </div>

    <form @submit.prevent class="form">
      <!-- step-1 -->
      <div class="step-1">
        <div class="step-title">基本資料</div>
        <div class="row no-gutters">
          <div class="col-md-6 col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">你的姓名:</span>
              <input
                type="text"
                class="item-input"
                name="name"
                placeholder="請輸入您的姓名"
                required
                maxlength="20"
              />
            </div>
          </div>
          <div class="col-md-6 col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-1">
              <span class="item required">你的生日:</span>
              <input type="date" class="item-input" name="birthday" />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">你的電話:</span>
              <input
                type="text"
                class="item-input"
                name="phone"
                placeholder="請輸入您的電話"
                required
                maxlength="10"
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">你的 Email:</span>
              <input
                type="email"
                class="item-input"
                name="email"
                placeholder="請輸入您的Email"
                required
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">就讀學校:</span>
              <select
                class="item-input"
                name="school"
                id=""
                placeholder="請選擇您的學校"
              >
                <option selected disabled value="">-請選擇-</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-md-6 col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">科系:</span>
              <select
                class="item-input"
                name="major"
                placeholder="請選擇您的科系"
              >
                <option selected disabled value="">-請選擇-</option>
              </select>
            </div>
          </div>
          <div class="col-md-6 col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-1">
              <span class="item required">年級:</span>
              <select
                class="item-input"
                name="grade"
                id=""
                placeholder="請選擇您的年級"
              >
                <option selected disabled value="">-請選擇-</option>
                <option value="1">大一</option>
                <option value="2" class="">大二</option>
                <option value="3" class="">大三</option>
                <option value="4" class="">大四</option>
                <option value="5" class="">延畢</option>
                <option value="6" class="">研究所</option>
                <option value="7" class="">其他</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">學校所在地:</span>
              <select
                class="item-input"
                name="school_city"
                placeholder="請選擇您學校所在縣市"
              >
                <option selected disabled value="">-請選擇-</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row no-gutters justify-content-center">
          <button class="btn btn-campus-primary">下一步</button>
        </div>
      </div>
    </form>
    <form class="form">
      <!-- step-2 -->
      <div class="step-2">
        <div class="step-title">推廣你自己</div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">
                你的社群連結(instagram/ Facebook):
              </span>
              <input
                type="text"
                class="item-input"
                name="social"
                placeholder="請輸入您的社群連結"
                required
                maxlength="20"
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">你的生活照:</span>
              <input
                type="file"
                class="item-input"
                name="photo"
                accept="image/*"
                required
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">一句話形容你自己:</span>
              <input
                type="text"
                class="item-input"
                name="introduction_brief"
                placeholder="請用一句話描述自己"
                maxlength="20"
                required
              />
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-12">
            <div class="d-flex align-items-center flex-wrap mb-md-4 mb-2">
              <span class="item required">自我介紹:</span>
              <textarea
                class="item-input"
                name="introduction"
                placeholder="請進行 200-500字的自我介紹"
                style="min-height: 300px"
              >
              </textarea>
            </div>
          </div>
        </div>
        <div class="row no-gutters justify-content-center">
          <button class="btn btn-campus-primary">下一步</button>
        </div>
      </div>
    </form>
    <!-- step-2 -->
    <!-- step-3 -->
    <!-- step-4 -->
    <!-- step-finished -->
  </div>
</template>

<script>
export default {

}
</script>

<style lang="scss" scoped>
$color--primary: #f2b162;
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
  font-size: 36px;
  line-height: 1.4;
}
.btn-campus-primary {
  padding: 8px 39px;
  background-color: $color--primary;
  font-style: normal;
  font-weight: 500;
  font-size: 32px;
  line-height: 46px;
  text-align: center;
  color: #ffffff;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  border-radius: 26px;
}
.form {
  .item {
    font-style: normal;
    font-weight: 500;
    font-size: 20px;
    line-height: 1.4;
    margin-bottom: 8px;
    color: #676767;
    &.required {
      &::before {
        content: '*';
        color: #e41a23;
        margin-right: 2px;
      }
    }
  }
  .item-input {
    font-size: 16px;
    padding: 4px 12px;
    line-height: 1.4;
    border: 1px solid #afafaf;
    border-radius: 8px;
    margin-right: 25px;
    width: 100%;
  }
  .item-label {
    position: relative;
    font-style: normal;
    font-weight: 500;
    font-size: 20px;
    line-height: 1.4;
    color: #676767;
    padding-left: 30px;
  }
}
.step-title {
  color: $color--primary;
  font-style: normal;
  font-weight: 700;
  font-size: 36px;
  line-height: 1.4;
  margin: 36px 0;
  width: 100%;
  text-align: center;
}
.personal {
  width: 800px;
  padding: 34px 120px;
  box-shadow: 0px 4px 10px rgba(25, 92, 160, 0.4);
  border-radius: 20px;
  background: #fff;
  .page-header {
    width: 100%;
    margin-bottom: 32px;
    background: none;
    .page-header-title {
      position: relative;
      display: flex;
      justify-content: center;
      &-text {
        position: relative;
        z-index: 3;
        font-style: normal;
        font-weight: 700;
        font-size: 56px;
        line-height: 1.4;
        color: #393939;
        transform: matrix(0.99, -0.06, -0.14, 1, 0, 0);
      }
      &-underline {
        z-index: 1;
        position: absolute;
        width: 194px;
        height: 18px;
        background: $color--primary;
        transform: matrix(0.99, -0.06, -0.14, 1, 0, 0);
        bottom: 4px;
      }
    }
  }
  .step-intro {
    .intro-text {
      font-style: normal;
      font-weight: 400;
      font-size: 20px;
      line-height: 1.4;
      text-align: center;
      color: #353535;
      margin: 25px 0;
    }
    .rating-table {
      &-title {
        font-style: normal;
        font-weight: 700;
        font-size: 24px;
        line-height: 1.4;
        text-align: center;
        color: #000000;
        margin-bottom: 15px;
      }
      .rating-row {
        display: grid;
        padding: 8px 35px;
        max-width: 600px;
        margin: 0 auto;
        grid-template-columns: 1fr 3fr 1fr;
        justify-content: center;
        align-items: center;
        gap: 15px;
        border-radius: 16px;
        font-style: normal;
        font-weight: 400;
        font-size: 20px;
        line-height: 1.4;
        color: #000;
        &:nth-child(odd) {
          background-color: #efefef;
        }
        &:first-child {
          background-color: $color--primary;
          color: #fff;
        }
      }
    }
    .rating-check,
    .rating-start {
      display: flex;
      justify-content: center;
      margin: 35px 0;
    }
  }
  .step-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 36px;
    .num-grid {
      display: grid;
      gap: 16px;
      grid-template-columns: 50px 70px 50px 70px 50px 70px 50px;
      align-items: center;
      margin-bottom: 16px;
    }
    .title-grid {
      display: grid;
      grid-template-columns: repeat(4, 135px);
      gap: 16px;
      margin-bottom: 16px;
    }
    &-num {
      &.active {
        background-color: $color--primary;
      }
      font-style: normal;
      font-weight: 700;
      font-size: 32px;
      line-height: 46px;
      color: #ffffff;
      height: 46px;
      border-radius: 100px;
      text-align: center;
      background-color: #caccce;
    }
    &-text {
      &.active {
        color: $color--primary;
      }
      font-style: normal;
      font-weight: 700;
      font-size: 24px;
      line-height: 1.4;
      text-align: center;
      color: #caccce;
    }
  }
}
</style>
