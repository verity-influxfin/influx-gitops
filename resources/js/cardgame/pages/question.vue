<template>
  <div class="cardgame-wrapper">
    <nav class="page-header navbar navbar-expand-lg">
      <div class="web-logo">
        <router-link to="index"
        ><img :src="'/images/logo_new.png'" class="img-fluid"
        /></router-link>
      </div>
    </nav>
    <div class="cards">
      <template>
        <span class="cardA" v-for="d in randkeys">
          <span class="cardNum">{{imgs[d].num }}</span>
          <img class="cardFace"  :src="'/images/cardGame'+d+'.svg'">
          <!--        <span class="cardQuestion">普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險</span>-->
        </span>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      randkeys: [1,2,3,4],
      cardAB: "A",
      imgs: {
        1: {
          num: "A",
          question: '普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險'
        },
        2: {
          num: "B",
          question: '普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險'
        },
        3: {
          num: "C",
          question: '普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險'
        },
        4: {
          num: "D",
          question: '普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險'
        }
      }
    }
  },
  onLoad: function(options) {
    console.log(1223);
    this.randomList()
  },
  methods: {
    randomList:function () {
      console.log(1223);
      this.randkeys = [4,4,4,4];
    }
  }
};
</script>

<style lang="scss">
.cardgame-wrapper {
  .page-header {
    z-index: 11;
    background-image: linear-gradient(to right, #1e2973 0%, #319acf 36%, #1e2973 73%);

    .web-logo {
      width: 140px;
      margin: 0px;
    }
  }

  .cards {
    background-color:#ffd186;
    padding: 30px 0;

    .cardA,.cardB{
      width: 110px;
      height: 150px;
      display: inline-table;
      border-radius: 20px;
      border: 8px solid;
      padding: 8px 5px;
      font-weight: bold;
      color: white;
      margin: 15px 0 0 10px;
      position: relative;

      .cardFace {
        position: absolute;
        width: 76%;
        margin: 24px 0 0;
      }
      .cardNum {
        position: absolute;
        font-size: 30px;
        margin: -8px 0 0 6px;
      }
      .cardQuestion {
        position: absolute;
        font-size: 12px;
      }
    }

    .cardA {
      background-color: #012060;
      border-color: #b40b12;
    }

    .cardB {
      background-color: #b40b12;
      border-color: #012060;
    }
  }
}
</style>
