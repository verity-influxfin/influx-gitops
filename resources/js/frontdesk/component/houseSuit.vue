<template>
  <div class="smeg-suit">
    <div class="tabs">
      <div class="tab" @click="tab = 0" :class="{ active: tab === 0 }">
        購屋資金
      </div>
      <div class="tab" @click="tab = 1" :class="{ active: tab === 1 }">
        裝修資金
      </div>
      <div class="tab" @click="tab = 2" :class="{ active: tab === 2 }">
        添購傢俱家電資金
      </div>
    </div>
    <div class="main-content">
      <div v-show="tab === 0">
        <div class="content-title">買房頭期款不足，找普匯！</div>
        <div class="content-sub-divider"></div>
        <div class="content-sub-title-2">
          <div>房子越來越貴，想趁早買房</div>
          <div>自備款卻不足...？</div>
        </div>
        <div class="article-content">
          <div>
            <div class="article-title">
              <div class="em">一支手機搞定貸款：</div>
            </div>
              <div class="article-info-content">
                <div>•提供添購傢俱家電合約與發票</div>
                <div>•不動產所有權狀</div>
                <div>•即可申貸合約或發票金額</div>
                <div>8成資金，最高100萬</div>
              </div>
              <div class="mt-4 article-info-btn">
                <a href="/borrowLink" target="_blank">
                  <button class="btn btn-outline-primary">立即申辦</button>
                </a>
              </div>
            </div>
          </div>
          <div>
            <img
              src="@/asset/images/houseloan/housesuit-content-1.jpg"
              class="img-fluid"
            />
          </div>
        </div>
      </div>
      <div v-show="tab === 1">
        <div class="content-title">房屋裝修(潢)，找普匯！</div>
        <div class="content-sub-divider"></div>
        <div class="content-sub-title-2">
          <div>好不容易有了自己的房子</div>
          <div>裝潢整修又是一筆龐大費用…</div>
        </div>
        <div class="article-content">
          <div>
            <div class="article-title">
              <div class="em">一支手機搞定貸款：</div>
            </div>
            <div class="article-info">
              <div class="article-info-content">
                <div>•提供房產裝修合約與發票</div>
                <div>•不動產所有權狀</div>
                <div>•即可申貸合約或發票金額</div>
                <div>8成資金，最高100萬</div>
              </div>
              <div class="mt-4 article-info-btn">
                <a href="/borrowLink" target="_blank">
                  <button class="btn btn-outline-primary">立即申辦</button>
                </a>
              </div>
            </div>
          </div>
          <div>
            <img
              src="@/asset/images/houseloan/housesuit-content-2.jpg"
              class="img-fluid"
            />
          </div>
        </div>
      </div>
      <div v-show="tab === 2">
        <div class="content-title">添購傢俱家電，找普匯！</div>
        <div class="content-sub-divider"></div>
        <div class="content-sub-title-2">
          <div>好不容易有了自己的房子</div>
          <div>添購傢俱家電又是一筆龐大費用…</div>
        </div>
        <div class="article-content">
          <div>
            <div class="article-title">
              <div class="em">一支手機搞定貸款：</div>
            </div>
            <div class="article-info">
              <div class="mt-4 article-info-btn">
                <a href="/borrowLink" target="_blank">
                  <button class="btn btn-outline-primary">立即申辦</button>
                </a>
              </div>
            </div>
          </div>
          <div>
            <img
              src="@/asset/images/houseloan/housesuit-content-3.jpg"
              class="img-fluid"
            />
          </div>
        </div>
      </div>
      <div class="mt-4 text-center"></div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tab: 0,
    }
  },
}
</script>

<style lang="scss" scoped>
.tabs {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 6px;
  .tab {
    align-self: end;
    font-style: normal;
    font-weight: 500;
    font-size: 28px;
    line-height: 1.4;
    text-align: center;
    color: #ffffff;
    background: linear-gradient(90deg, #3781c7 0%, #74bbe4 100%);
    border-radius: 16px 16px 0 0;
    padding: 10px;
    cursor: pointer;

    display: inline-flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 7px;
    position: relative;
    &.active {
      padding: 12px;
      background: #ffffff;
      font-size: 40px;
      color: #036eb7;
      font-weight: 700;

      @media screen and (min-width: 767px) {
        &.active::after {
          content: '';
          position: relative;
          bottom: 0;
          left: 0;
          width: 50%;
          height: 2px;
          background: linear-gradient(
            90deg,
            #4086c9,
            #7ac1ea
          );
          border-radius: 2px;
          box-shadow: 0 4px 4px rgba(0, 0, 0, 0.4);
        }
      }
    }
  }
}
.main-content {
  padding: 36px 64px;
  border-radius: 0px 0px 16px 16px;
  background-color: #fff;
  .content {
    &-title {
      margin-bottom: 12px;
      font-style: normal;
      font-weight: 700;
      font-size: 36px;
      line-height: 1.4;
      text-align: center;
      color: #023d64;
    }
    &-sub-title {
      font-style: normal;
      font-weight: 500;
      font-size: 20px;
      line-height: 1.4;
      text-align: center;
      color: #036eb7;
    }
    &-sub-divider {
      margin: 8px auto;
      width: 40px;
      border-bottom: 3px solid #036eb7;
    }
    &-sub-title-2 {
      font-style: normal;
      font-weight: 500;
      font-size: 32px;
      line-height: 1.4;
      text-align: center;
      color: #f29500;
    }
  }
  .article-content {
    margin-top: 32px;
    padding: 0 100px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;

    .article-title {
      font-style: normal;
      font-weight: 400;
      font-size: 28px;
      line-height: 1.4;
      color: #036eb7;
      .em {
        font-weight: 700;
        padding-left: 25px;
      }
    }
    .article-info {
      margin-top: 24px;
      padding-left: 15px;
      font-style: normal;
      font-weight: 400;
      font-size: 20px;
      line-height: 29px;
      color: #707070;
      .article-info-content {
        display: inline-flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        gap: 8px;

        :last-child::before {
          content: ' ';
          display: inline-block;
          width: 1em;
        }
      }
    }
  }
}
.btn-outline-primary {
  &::before {
    content: '';
    position: absolute;
    top: -2.4px;
    left: -2.4px;
    bottom: -2.4px;
    right: -2px;
    background: linear-gradient(90deg, #3781c7 0%, #74bbe4 100%);
    border-radius: 12px;
    z-index: -1;
  }
  position: relative;
  padding: 10px 120px;
  color: #036eb7;
  background-color: #fff;
  font-style: normal;
  font-weight: 500;
  font-size: 24px;
  line-height: 1.4;
  text-align: center;
  border-radius: 12px;
}
@media screen and (max-width: 767px) {
  .smeg-suit {
    display: grid;
    grid-template-columns: 32px 1fr;
  }
  .tabs {
    display: flex;
    flex-direction: column;
    gap: 6px;
    .tab {
      font-size: 14px;
      border-radius: 16px 0 0 16px;
      padding: 42px 10px;
      cursor: pointer;
      &.active {
        padding: 42px 10px;
        background: #ffffff;
        font-size: 16px;
        color: #036eb7;
      }
    }
  }
  .main-content {
    padding: 24px 12px;
    border-radius: 0px 16px 16px 16px;
    .content {
      &-title {
        margin-bottom: 4px;
        font-size: 16px;
      }
      &-sub-title {
        font-size: 12px;
      }
      &-sub-divider {
        margin: 4px auto;
        width: 36px;
        border-bottom: 1px solid #036eb7;
      }
      &-sub-title-2 {
        font-size: 14px;
        line-height: 1.4;
      }
    }
    .article-content {
      margin-top: 32px;
      padding: 43px;
      display: flex;
      flex-direction: column-reverse;
      gap: 30px;

      .article-title {
        font-size: 14px;
        padding-left: 10px;
        .em {
          font-weight: 700;
          padding-left: 12px;
        }
      }
      .article-info {
        margin-top: 8px;
        padding-left: 10px;
        font-weight: 400;
        font-size: 12px;
        line-height: 20px;
        .article-info-btn {
          padding-left: 34px;
        }
      }
    }
  }
  .btn-outline-primary {
    &::before {
      content: '';
    }
    position: relative;
    padding: 10px 30px;
    font-size: 14px;
    line-height: 1.4;
    border-radius: 12px;
  }
}
</style>
