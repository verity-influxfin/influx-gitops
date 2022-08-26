<template>
  <div class="require-datas">
    <div class="require-tabs">
      <div class="item active">負責人資料</div>
      <div class="item">公司資料</div>
    </div>
    <div class="tab-content d-sm-block d-none">
      <div class="content-item">
        <div class="content-row">
          <div class="content-row-title">實名認證</div>
          <div class="content-row-info">請填寫並提供證件資料，以完成實名制</div>
          <div class="content-row-img">
            <img src="@/asset/images/bussinessLoan/smeg-require-1.png" />
          </div>
        </div>
        <div class="content-row">
          <div class="content-row-title">常用電子信箱</div>
          <div class="content-row-info">請提供我們方便聯絡的電子信箱</div>
          <div class="content-row-img">
            <img src="@/asset/images/index/invest-step-1.png" />
          </div>
        </div>
        <div class="content-row">
          <div class="content-row-title">個人基本資料</div>
          <div class="content-row-info">請提供審核所需個資，以便徵審</div>
          <div class="content-row-img">
            <img src="@/asset/images/index/invest-step-1.png" />
          </div>
        </div>
        <div class="content-row">
          <div class="content-row-title">個人所得資料</div>
          <div class="content-row-info">請提供所得資料文件，以供信用評級</div>
          <div class="content-row-img">
            <img src="@/asset/images/index/invest-step-1.png" />
          </div>
        </div>
        <div class="content-row">
          <div class="content-row-title">近六個月往來存摺封面及內頁</div>
          <div class="content-row-info">請提供近期個人存摺往來資料</div>
          <div class="content-row-img">
            <img src="@/asset/images/index/invest-step-1.png" />
          </div>
        </div>
      </div>
      <!-- <div class="content-item"></div> -->
    </div>
    <div class="tab-content d-sm-none d-block">
      <div class="tab-content">
        <div class="content-item swiper">
          <div class="swiper-wrapper">
            <div class="content-row swiper-slide">
              <div class="content-row-title">實名認證</div>
              <div class="content-row-info">
                請填寫並提供證件資料，以完成實名制
              </div>
              <div class="content-row-img">
                <img
                  src="@/asset/images/bussinessLoan/smeg-require-1.png"
                  class="img-fluid"
                />
              </div>
            </div>
            <div class="content-row swiper-slide">
              <div class="content-row-title">常用電子信箱</div>
              <div class="content-row-info">請提供我們方便聯絡的電子信箱</div>
              <div class="content-row-img">
                <img src="@/asset/images/index/invest-step-1.png" />
              </div>
            </div>
            <div class="content-row swiper-slide">
              <div class="content-row-title">個人基本資料</div>
              <div class="content-row-info">請提供審核所需個資，以便徵審</div>
              <div class="content-row-img">
                <img src="@/asset/images/index/invest-step-1.png" />
              </div>
            </div>
            <div class="content-row swiper-slide">
              <div class="content-row-title">個人所得資料</div>
              <div class="content-row-info">
                請提供所得資料文件，以供信用評級
              </div>
              <div class="content-row-img">
                <img src="@/asset/images/index/invest-step-1.png" />
              </div>
            </div>
            <div class="content-row swiper-slide">
              <div class="content-row-title">近六個月往來存摺封面及內頁</div>
              <div class="content-row-info">請提供近期個人存摺往來資料</div>
              <div class="content-row-img">
                <img src="@/asset/images/index/invest-step-1.png" />
              </div>
            </div>
          </div>
          <div class="swiper-pagination"></div>
        </div>
        <!-- <div class="content-item"></div> -->
      </div>
    </div>
  </div>
</template>

<script>
import SwiperCore, { Navigation, Pagination } from 'swiper/core'
import Swiper from 'swiper/bundle'
export default {
  mounted() {
    SwiperCore.use([Navigation, Pagination])
    const swiper = new Swiper('.swiper', {
      pagination: {
        el: '.swiper-pagination',
      },
    })
  },
}
</script>

<style lang="scss" scoped>
.require-datas {
  max-width: 1100px;
  margin: 0 auto;
  .require-tabs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    background: #fff;
    border-radius: 16px;
    .item {
      &.active {
        color: #ffffff;
        background: linear-gradient(90deg, #3781c7 0%, #74bbe4 100%);
      }
      padding: 12px 0;
      border-radius: 16px;
      text-align: center;
      font-style: normal;
      font-weight: 500;
      font-size: 36px;
      line-height: 1.4;
    }
  }
  .tab-content {
    margin-top: 75px;
    height: 550px;
    .content-item {
      display: flex;
      flex-direction: column;
      position: relative;
    }
  }
  .content-row {
    padding-left: 45%;
    display: grid;
    grid-template-columns: auto 1fr;
    grid-template-rows: auto auto;
    margin-bottom: 22px;
    &:hover,
    &:focus-within {
      &::before {
        background-color: #023d64;
      }
      .content-row-title {
        color: #023d64;
      }
      .content-row-info {
        opacity: 1;
      }
      .content-row-img {
        z-index: 10;
        opacity: 1;
        transition-property: opacity;
        transition-duration: 1s;
      }
    }
    &:first-of-type {
      .content-row-img {
        opacity: 1;
        z-index: 3;
      }
    }
    &::before {
      content: '';
      width: 60px;
      height: 60px;
      background-image: url('~images/bussinessLoan/smeg-check.svg');
      background-repeat: no-repeat;
      background-position: center;
      background-color: #036fb7;
      display: inline-block;
      border-radius: 50%;
      grid-area: 1 / 1 / 3 / 2;
      margin-right: 36px;
    }
    &-title {
      font-style: normal;
      font-weight: 700;
      font-size: 36px;
      line-height: 52px;
      color: #036fb7;
      margin-bottom: 12px;
    }
    &-info {
      font-weight: 400;
      font-size: 20px;
      line-height: 23px;
      color: #023d64;
      opacity: 0.56;
    }
    &-img {
      top: -4px;
      left: 8%;
      position: absolute;
      transform: scale(0.97);
      transition-property: opacity;
      transition-duration: 1s;
      opacity: 0;
    }
  }
}
@media screen and (max-width: 767px) {
  .require-datas {
    margin: 0 15px;
    .require-tabs .item {
      padding: 6px 0;
      font-size: 12px;
    }
    .swiper {
      width: 330px;
      height: 650px;
    }
    .tab-content {
      margin-top: 22px;
      height: auto;
      .content-item {
        display: flex;
        flex-direction: column;
        position: relative;
      }
    }
    .content-row {
      padding-left: 0;
      width: 100%;
      display: flex;
      flex-direction: column;
      margin-bottom: 22px;
      &::before {
        display: none;
      }
      &-title {
        font-size: 18px;
        text-align: center;
        line-height: 1.4;
        margin-bottom: 6px;
      }
      &-info {
        text-align: center;
        font-size: 12px;
        line-height: 16px;
      }
      &-img {
        top: 0;
        left: 0;
        display: flex;
        justify-content: center;
        position: relative;
        opacity: 1;
      }
    }
  }
}
</style>
