<template>
  <div class="credit-card">
    <div class="credit-box">
      <h2>「額度最高{{ $props.amount }}萬，那利率呢？」</h2>
      <div class="hr"></div>
      <p>
        信用評等決定您的利率
        <br />小普提醒您，資料提供的越完整，對信評越有幫助！
      </p>
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
      <span class="license">*利率會依每位用戶個別狀況而有所不同</span>
      <template v-if="$props.license">
        <br />
        <span class="license">*{{ $props.license }}</span>
      </template>
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
  methods: {
    rotate(isChecked, key) {
      let { deg, creditLevelList } = this;
      creditLevelList.forEach((item, i) => {
        creditLevelList[i].isChecked = false;
      });

      this.index = key;
      creditLevelList[key].isChecked = true;
      this.deg = creditLevelList[key].deg;
    },
    movePointer(formula, key) {
      let { creditLevelList } = this;
      key = formula === "plus" ? parseInt(key) + 1 : parseInt(key) - 1;

      if (0 <= key && key <= creditLevelList.length - 1) {
        this.rotate(true, key);
      }
    },
  },
};
</script>

<style lang="scss">
.credit-card {
  display: flex;
  padding: 4rem 0px 0px 0px;

  .credit-box {
    width: 65%;
    padding: 0px 0px 0px 15rem;

    p {
      font-size: 16px;
      font-weight: bold;
      color: #1f232c;
      line-height: 1.5;
      margin: 20px;
    }

    .credit-level-list {
      overflow: auto;
      width: 48%;

      .level-item {
        filter: opacity(0.4);
        width: calc(33% - 40px);
        float: left;
        border-radius: 50%;
        background-color: #ffffff;
        margin: 20px;
        padding: 10px;

        &.light {
          filter: initial;
          box-shadow: 0 0 20px 0 #6ab0f2;
        }
      }
    }

    .license {
      color: #4f4f4f;
      font-size: 11px;
    }
  }

  .credit-board {
    width: 35%;
  }
}

@media screen and (max-width: 767px) {
  .credit-card {
    flex-direction: column;
    padding: 0px;

    .credit-box {
      width: 100%;
      padding: 10px;

      p {
        margin: 10px 20px;
      }

      .credit-level-list {
        width: 100%;

        .level-item {
          margin: 5px;
          width: calc(20% - 10px);
        }
      }

      .license {
        float: right;
      }
    }

    .credit-board {
      width: 100%;
    }
  }
}
</style>