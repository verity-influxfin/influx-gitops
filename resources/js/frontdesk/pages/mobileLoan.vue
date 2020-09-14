<template>
  <div class="mobile-wrapper">
    <banner :data="this.bannerData"></banner>
    <div class="hr"></div>
    <div class="search-line">
      <div class="filter-i float-left">
        <div class="scroll left" @click="scroll('left')">
          <i class="fas fa-chevron-left"></i>
        </div>
        <div class="option-row" ref="option_row">
          <button
            :class="['btn',{'btn-secondary' : now === index},{'btn-outline-secondary' : now !== index} ]"
            v-for="(make,index) in makes"
            @click="filter.make = make;now = index"
            :key="index"
          >{{make ? make : 'ALL'}}</button>
        </div>
        <div class="scroll right" @click="scroll('right')">
          <i class="fas fa-chevron-right"></i>
        </div>
      </div>
      <div class="input-custom float-right">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" placeholder="手機型號" v-model="filter.mobileName" />
        <i class="fas fa-times" v-if="filter.mobileName" @click="filter.mobileName = ''"></i>
      </div>
    </div>
    <div class="hr"></div>
    <div id="list">
      <div class="goods-card" id="goods-card">
        <template v-if="filterMobileData.length === 0">
          <div class="empty">
            <div class="empty-img">
              <img src="../asset/images/empty.svg" class="img-fluid" />
            </div>
            <h3>沒有結果</h3>
            <p>根據您的搜索，我們似乎找不到結果</p>
          </div>
        </template>
        <template v-else>
          <ul class="mobile-content" ref="content"></ul>
          <div class="pagination" ref="pagination"></div>
        </template>
      </div>
    </div>
    <apply
      title="選擇喜歡的手機，無卡也能分期支付，輕鬆購買"
      :requiredDocuments="applyData.requiredDocuments"
      :step="applyData.step"
    />
    <div class="recommend-card">
      <div class="banner-text">優良店家推薦</div>
      <div class="mobile-footer">
        <img :src="'./images/mobile_banner_web.jpg'" class="img-fluid desktop" />
        <img :src="'./images/mobile_banner_mobile.jpg'" class="img-fluid mobile" />
      </div>
    </div>
  </div>
</template>

<script>
let productRow = Vue.extend({
  props: ["item", "index"],
  template: `
     <li class="item">
      <div class="text">
        <h5>{{item.name}}</h5>
        <span>空機價 {{format(item.price)}}$</span>
      </div>
      <div class="img">
        <img :src="item.image" class="img-fluid" />
      </div>
      <div style="overflow: auto;margin-top: 15px;">
        <a
          class="btn btn-outline-warning btn-sm float-right"
          href="https://event.influxfin.com/R/url?p=webbanner"
          target="_blank"
        >立即申請分期</a>
      </div>
    </li>
  `,
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
  },
});

import banner from "../component/bannerComponent";
import apply from "../component/applyComponent";

export default {
  components: {
    banner,
    apply,
  },
  data: () => ({
    now: 0,
    filterMobileData: [],
    mobileData: [],
    makes: [""],
    bannerData: {},
    applyData: {},
    filter: {
      mobileName: "",
      make: "",
    },
  }),
  created() {
    this.getMobileData();
    this.getBannerData();
    this.getApplydata();
    $("title").text(`手機分期 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      AOS.init();
      particlesJS.load("list", "data/mobile.json");
    });
  },
  watch: {
    filterMobileData() {
      this.$nextTick(() => {
        window.dispatchEvent(new Event("resize"));
        this.pagination();
      });
    },
    "filter.mobileName"(newVal) {
      this.doFilter(newVal, this.filter.make);
    },
    "filter.make"(newVal) {
      this.doFilter(this.filter.mobileName, newVal);
    },
  },
  methods: {
    doFilter(name, make) {
      this.filterMobileData = [];
      this.mobileData.forEach((row, index) => {
        if (
          row.brand.toLowerCase().indexOf(make.toLowerCase()) !== -1 &&
          row.name.toLowerCase().indexOf(name.toLowerCase()) !== -1
        ) {
          this.filterMobileData.push(row);
        }
      });
    },
    getBannerData() {
      axios.post("getBannerData", { filter: "mobile" }).then((res) => {
        this.bannerData = res.data;
      });
    },
    async getMobileData() {
      let res = await axios.post("getMobileData");

      this.mobileData = res.data.data.list.reverse();
      this.filterMobileData = res.data.data.list.reverse();

      this.mobileData.forEach((item) => {
        if (!this.makes.includes(item.brand)) {
          this.makes.push(item.brand);
        }
      });
      this.makes.sort();
    },
    async getApplydata() {
      let res = await axios.post("getApplydata", { filter: "mobile" });
      this.applyData = res.data;
    },
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          pageSize: 12,
          dataSource: $this.filterMobileData,
          callback(data, pagination) {
            $($this.$refs.content).html("");
            data.forEach((item, index) => {
              let component = new productRow({
                propsData: {
                  item,
                  index,
                },
              }).$mount();

              $($this.$refs.content).append(component.$el);
            });
          },
        });

        window.dispatchEvent(new Event("resize"));
      });
    },
    scroll(direction) {
      let scrollLeft = $(this.$refs.option_row).scrollLeft();
      if (direction === "left") {
        $(this.$refs.option_row).scrollLeft(scrollLeft - 280);
      } else {
        $(this.$refs.option_row).scrollLeft(scrollLeft + 280);
      }
    },
  },
};
</script>

<style lang="scss">
.mobile-wrapper {
  width: 100%;
  overflow: auto;

  h2 {
    font-weight: bolder;
    text-align: center;
    color: #083a6e;
  }

  .hr {
    border-top: 1px solid #eaeaea;
    margin: 0px auto;
    width: 100%;
  }

  .search-line {
    width: 80%;
    margin: 20px auto;
    overflow: hidden;

    .filter-i {
      overflow: hidden;
      width: 100%;
      position: relative;
      margin-bottom: 10px;

      .option-row {
        display: flex;
        overflow: auto;
        margin: 0px 15px;
        scroll-behavior: smooth;

        button {
          margin-right: 10px;
          word-break: keep-all;
        }
      }

      .scroll {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        height: 44px;
        width: 30px;
        line-height: 44px;
        text-align: center;
        box-shadow: 0px 0px 11px 0px #ffffff99;
        cursor: pointer;

        &.left {
          left: 0px;
          background-image: linear-gradient(to right, #ffffff, #ffffff40);
        }

        &.right {
          right: 0px;
          background-image: linear-gradient(to left, #ffffff, #ffffff40);
        }
      }

      ::-webkit-scrollbar {
        display: none;
      }
    }

    .input-custom {
      width: 300px;
      position: relative;

      .form-control {
        padding: 5px 35px;
      }

      %iStyle {
        position: absolute;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 20px;
        color: #083a6e;
        text-shadow: 0 0 4px #083a6e;
      }

      .fa-search {
        @extend %iStyle;
        left: 10px;
      }

      .fa-times {
        @extend %iStyle;
        right: 10px;
        cursor: pointer;
      }
    }
  }

  #list {
    position: relative;
    overflow: auto;

    .particles-js-canvas-el {
      position: absolute;
      top: 0;
      z-index: -1;
    }

    .goods-card {
      width: 80%;
      margin: 20px auto;
      overflow: hidden;

      .item {
        width: calc(33.3% - 20px);
        float: left;
        border: 1px solid #083a6e;
        padding: 10px;
        margin: 10px;
        transition-duration: 0.5s;
        background: #ffffff;

        &:hover {
          color: #000000;
          text-decoration: none;
          box-shadow: inset 0 0 5px 2px #083a6e;
        }

        .img {
          width: 250px;
          height: 250px;
          margin: 5px auto;
          line-height: 250px;
          overflow: hidden;
        }

        .text {
          h5 {
            font-weight: bold;
          }

          span {
            color: #087d01;
            font-weight: bold;
          }
        }
      }

      .mobile-content {
        overflow: auto;
        margin: 0px;
        padding: 0px;
      }

      .pagination {
        margin: 20px auto;
        width: fit-content;
      }
    }
  }

  .empty {
    text-align: center;
    margin: 30px auto;

    .empty-img {
      width: 200px;
      margin: 20px auto;
    }

    h3 {
      font-weight: bold;
    }
  }

  .recommend-card {
    .banner-text {
      font-size: 25px;
      font-weight: bolder;
      text-align: center;
      padding: 30px 0px;
      background: #492f78;
      color: #ffffff;
    }

    .mobile-footer {
      img {
        min-width: 100%;
      }
    }
  }

  @media (max-width: 767px) {
    h2 {
      font-size: 25px;
      margin-bottom: 20px;
    }

    .search-line {
      width: 100%;
      margin: 10px auto;

      .input-custom,
      .filter-i {
        width: 95%;
        margin: 10px auto;

        button {
          margin: 7px;
        }
      }

      .float-right,
      .float-left {
        float: initial !important;
      }
    }

    #list {
      .goods-card {
        width: 100%;

        .item {
          width: calc(100% - 20px);
          margin: 10px auto;
          float: none;
          display: block;
        }
      }
    }

    .banner-text {
      font-size: 16px;
    }

    .desktop {
      display: none;
    }
  }

  @media (min-width: 767px) {
    .mobile {
      display: none;
    }
  }
}
</style>
