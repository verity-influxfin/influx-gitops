<template>
  <div class="credit-card">
    <div class="credit-box">
      <p class="t-t">
        只要有一支手機，「5分鐘」完成申貸手續、「10分鐘」核准、「60分鐘」到帳，<br />
        全程AI自動審核、無人照會打擾，<br />
        完全符合現代年輕人希望簡單、快速、尊重隱私的生活需求！
      </p>
      <div class="t-c"><h2>年化%與月息試算</h2></div>
      <div class="hr"></div>
      <p class="t-x">
        速貸{{ amount }}萬元、分
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
        期，每月需付<b>{{ format(tweenedPmt) }}</b>
      </p>
      <p class="t-x">可點選您的評級進行月付金試算</p>
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
    </div>
    <div class="credit-board">
      <creditBoard :credit="$props.creditList" :deg="deg" :index="index" />
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
      { href: "icon_credit_level01.svg", deg: "-30", isChecked: true },
      { href: "icon_credit_level02.svg", deg: "-15", isChecked: false },
      { href: "icon_credit_level03.svg", deg: "0", isChecked: false },
      { href: "icon_credit_level04.svg", deg: "15", isChecked: false },
      { href: "icon_credit_level05.svg", deg: "30", isChecked: false },
      { href: "icon_credit_level06.svg", deg: "45", isChecked: false },
      { href: "icon_credit_level07.svg", deg: "60", isChecked: false },
      { href: "icon_credit_level08.svg", deg: "75", isChecked: false },
      { href: "icon_credit_level09.svg", deg: "90", isChecked: false },
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
  overflow: auto;
  padding: 4rem 8rem;
  position: relative;
  background: #ecedf1;

  .credit-box {
    .t-c {
      margin: 0px;
    }

    .hr {
      margin: 1rem 0px;
    }

    .t-t {
      font-size: 22px;
      line-height: 2.27;
      letter-spacing: 1.1px;
      text-align: left;
      color: #143970;
      font-weight: bold;
      margin-bottom: 5rem;
    }

    .credit-level-list {
      overflow: auto;
      width: 350px;

      .level-item {
        width: calc(33% - 20px);
        float: left;
        border-radius: 50%;
        margin: 10px 10px;
        padding: 10px;

        &.light {
          filter: drop-shadow(0px 0px 3px black);
        }
      }
    }

    .t-x {
      font-size: 16px;
      font-weight: 700;
      line-height: 0.5;
      letter-spacing: 1px;
      color: #143970;
    }

    .license {
      color: #4f4f4f;
      font-size: 11px;
      margin: 0px;
    }
  }

  .credit-board {
    width: 550px;
    position: absolute;
    bottom: 0;
    right: 0;
    z-index: 1;
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