<template>
  <div class="credit-card">
    <div class="credit-box">
      <h2>速貸{{ $props.amount }}萬，每月需付多少呢？</h2>
      <div class="hr"></div>
      <p>
        我們使用信用評等決定您的月付金
        <br />貸
        <select v-model="periods">
          <option value="3">3</option>
          <option value="6">6</option>
          <option value="9">9</option>
          <option value="12">12</option>
          <option value="15">15</option>
          <option value="18">18</option>
          <option value="21">21</option>
          <option value="24">24</option>
        </select>
        期，信評{{ index + 1 }}級，每月需付<b>{{ format(tweenedPmt) }}</b>
        <br />驗證資料提供的越完整，對信評越有幫助，月付金越少！
      </p>
      <p>可點選您的評級進行月付金試算</p>
      <div class="credit-level-list">
        <div
          v-for="(item, index) in creditLevelList"
          :key="index"
          :class="['level-item', { light: item.isChecked }]"
          @click="rotate(item.isChecked, index)"
        >
          <img :src="`/images/${item.href}`" class="img-fluid" />
        </div>
      </div>
      <template v-if="$props.license">
        <br />
        <p class="license">*{{ $props.license }}</p>
      </template>
      <a
        href="https://event.influxfin.com/R/url?p=webbanner"
        target="_blank"
        class="btn btn-go"
        >了解更多</a
      >
    </div>
    <div class="credit-board">
      <creditBoard
        :credit="$props.creditList"
        :deg="deg"
        :index="index"
        @move="movePointer"
      />
    </div>
  </div>
</template>

<script>
import creditBoard from "./svg/creditBoardComponent";

export default {
  components: { creditBoard },
  props: ["creditList", "amount", "license"],
  data: () => ({
    deg: "",
    index: "0",
    Pmt: 0,
    periods: 24,
    tweenedPmt: 0,
    creditLevelList: [
      { href: "icon_credit_level01.svg", deg: "-150", isChecked: true },
      { href: "icon_credit_level02.svg", deg: "-135", isChecked: false },
      { href: "icon_credit_level03.svg", deg: "-120", isChecked: false },
      { href: "icon_credit_level04.svg", deg: "-105", isChecked: false },
      { href: "icon_credit_level05.svg", deg: "-90", isChecked: false },
      { href: "icon_credit_level06.svg", deg: "-75", isChecked: false },
      { href: "icon_credit_level07.svg", deg: "-60", isChecked: false },
      { href: "icon_credit_level08.svg", deg: "-45", isChecked: false },
      { href: "icon_credit_level09.svg", deg: "-30", isChecked: false },
    ],
  }),
  mounted() {
    this.$nextTick(() => {
      this.rotate(true, 0);
    });
  },
  watch: {
    Pmt(newVal) {
      gsap.to(this.$data, { duration: 0.5, tweenedPmt: newVal });
    },
    periods() {
      this.pmt();
    },
  },
  methods: {
    format(data) {
      data = parseInt(data);
      if (!isNaN(data)) {
        let l10nEN = new Intl.NumberFormat("en-US");
        return l10nEN.format(data.toFixed(0));
      }
      return 0;
    },
    rotate(isChecked, key) {
      let { deg, creditLevelList } = this;
      creditLevelList.forEach((item, i) => {
        creditLevelList[i].isChecked = false;
      });

      this.index = key;
      creditLevelList[key].isChecked = true;
      this.deg = creditLevelList[key].deg;
      this.pmt();
    },
    movePointer(formula, key) {
      let { creditLevelList } = this;
      key = formula === "plus" ? parseInt(key) + 1 : parseInt(key) - 1;

      if (0 <= key && key <= creditLevelList.length - 1) {
        this.rotate(true, key);
      }
    },
    pmt() {
      let m_rate =
        this.$props.creditList[
          Object.keys(this.$props.creditList)[this.index]
        ] / 1200;
      let nper = Math.pow(m_rate + 1, -parseInt(this.periods));
      this.Pmt = Math.ceil((this.amount * m_rate * 10000) / (1 - nper));
    },
  },
};
</script>

<style lang="scss">
.credit-card {
  display: flex;
  overflow: auto;
  padding: 4rem 0px 0px 0px;
  position: relative;
  background: #ecedf1;

  .credit-box {
    width: 65%;
    padding: 0px 15rem 0px 15rem;

    p {
      font-size: 16px;
      font-weight: bold;
      color: #1f232c;
      line-height: 1.5;
      margin: 20px;

      b {
        color: #ff7600;
      }
    }

    .credit-level-list {
      overflow: auto;
      padding: 0px 6rem;
      width: fit-content;

      .level-item {
        filter: opacity(0.4);
        width: calc(33% - 40px);
        float: left;
        border-radius: 50%;
        background-color: #ffffff;
        margin: 10px 20px;
        padding: 10px;

        &.light {
          filter: initial;
          box-shadow: 0 0 10px 0 #6ab0f2;
        }
      }
    }

    .license {
      color: #4f4f4f;
      font-size: 11px;
      margin: 0px;
    }

    .btn-go {
      display: block;
      padding: 5px 20px;
      margin: 10px auto;
      font-weight: bold;
      color: #ffffff;
      text-align: center;
      border-radius: 25px;
      box-shadow: 1px 2px 3px black;
      background: #104073;
      width: fit-content;
    }
  }

  .credit-board {
    width: 35%;
  }
}

@media screen and (max-width: 767px) {
  .credit-card {
    display: block;
    padding: 4rem 0px;

    .credit-box {
      width: 100%;
      padding: 10px;

      p {
        margin: 10px 20px;
      }

      .credit-level-list {
        width: 100%;
        padding: 0px;

        .level-item {
          margin: 5px;
          width: calc(20% - 10px);
        }
      }

      .license {
        text-align: end;
      }

      .btn-go {
        position: absolute;
        bottom: 4rem;
        left: 50%;
        transform: translate(-50%, 0px);
      }
    }

    .credit-board {
      width: 80%;
      float: right;
      margin-bottom: 55px;
    }
  }
}
</style>