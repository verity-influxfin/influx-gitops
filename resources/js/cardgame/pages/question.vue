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
      <template >
        <span :class="'card'+(d%2===0?'B':'A')" v-for="d in randkeys">
          <span class="cardFlip card-front">
            <span class="cardNum">{{faceNum[d-1] }}</span>
            <img class="cardFace" :style="'margin-top:'+picTop[d-1]+'px;margin-left:'+picLeft[d-1]+'px'" :src="'/images/cardGame'+d+'.svg'">
          </span>
          <span class="cardFlip card-back">
            <span class="cardQuestion" :data-id="d">
              {{imgs[d].question}}<br /><br />
              <span class="cardAns" @click="ans" data-ans="A">(A){{imgs[d].selection[0]}}</span>
              <span class="cardAns" @click="ans" data-ans="B">(B){{imgs[d].selection[1]}}</span>
            </span>
          </span>
        </span>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      process: false,
      randkeys: [],
      faceNum: ['H','A','P','P','Y','N','E','W','Y','E','A','R'],
      picTop: ['40','46','50','36','48','37','43','47','51','43','47','45'],
      picLeft: ['4','4','3','2','3','1','-1','5','4','0','4','0','5'],
      imgs: {
        1: {
          num: "A",
          question: '普匯目前主要業務項目為何?',
          selection: ['借貸/投資','保險'],
          ans: 'A'
        },
        2: {
          num: "B",
          question: 'p2p借貸的英文縮寫?',
          selection: ['peer to peer','pika to pika'],
          ans: 'A'
        },
        3: {
          num: "C",
          question: '普匯今年舉辦什麼活動?',
          selection: ['AI金融科技創新創意競賽?','國外旅遊'],
          ans: 'A'
        },
        4: {
          num: "D",
          question: '普匯為下列何種類型公司?',
          selection: ['金融科技','科技金融'],
          ans: 'A'
        },
        5: {
          num: "E",
          question: '普匯是媒合誰與誰的平台?',
          selection: ['借款人與投資人','你和我'],
          ans: 'A'
        },
        6: {
          num: "F",
          question: '普匯的吉祥物叫?',
          selection: ['小普','來福'],
          ans: 'A'
        },
        7: {
          num: "G",
          question: '普匯的代表色為何?',
          selection: ['黑色','藍色'],
          ans: 'B'
        },
        8: {
          num: "H",
          question: '普匯主打的服務是?',
          selection: ['AI線上無人化','見面對保狂call你'],
          ans: 'A'
        },
        9: {
          num: "I",
          question: '普匯第一個上線的產品是?',
          selection: ['學生貸','房貸'],
          ans: 'A'
        },
        10: {
          num: "J",
          question: '普匯投資是多少元起投?',
          selection: ['1000元','1000萬'],
          ans: 'A'
        },
        11: {
          num: "K",
          question: '普匯創立於哪一年份??',
          selection: ['2017年','2025年'],
          ans: 'A'
        },
        12: {
          num: "L",
          question: '普匯APP於哪一年上線?',
          selection: ['2019年','尚未上線'],
          ans: 'A'
        }
      }
    }
  },
  mounted:function () {
    //todo random
    this.randkeys = [1,2,3,4,5,6,7,8,9,10,11,12];
    $(document).off("click",".cardA,.cardB").on("click",".cardA,.cardB" ,  function(e,t){
      $(this).addClass('active');
    });
  },
  methods: {
    ans(event) {
      if(!this.process){
        this.process = true;
        let data = {
          qnum: event.target.parentElement.dataset.id,
          qans: event.target.dataset.ans,
        };
        axios
            .post("/getAns", data)
            .then((res) => {
              if(res.data.ans == 1){
                $('.active:not(.done)').addClass('done');
                if($('.done').length > 2){
                  alert('恭喜全部答對，請前往抽獎');
                  window.location.href = "/cardgame/turntable"
                }else {
                  alert('恭喜您答對了!! 下一題~')
                  $('.active').removeClass('active');
                  $('[data-id='+data.qnum+'] .cardAns:not([data-ans='+data.qans+'])').remove()
                }
              }else{
                alert('答錯囉~再讓我們玩一次吧！');
                window.location.href = "/cardgame"
              }
              this.process = false;
            })
            .catch((err) => {
              console.error(err);
            });
      }else{
        console.log('duplicate!!');
      }
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
      border: 6px solid;
      padding: 8px 5px;
      font-weight: bold;
      color: white;
      margin: 15px 0 0 11px;
      position: relative;
      transition: transform 1s;
      transform-style: preserve-3d;
      &.active {
        transform: rotateY(180deg);
        position: absolute;
        width: 94%;
        height: 80%;
        z-index: 999;
        left: 0;
        top: 86px;
        .card-front {
          display: none;
        }
        .cardQuestion {
          font-size: 34px;
        }
      }
      &.done {
        transform: rotateY(180deg);
        .card-front {
          display: none;
        }
        .cardQuestion {
          font-size: 12px;
        }
      }

      .cardFace {
        width: 145%;
        position: relative;
      }
      .cardNum {
        font-size: 36px;
        margin: -8px 0 0 4px;
        position: absolute;
      }
      .cardQuestion {
        font-size: 12px;
        padding: 0 5px;
        display: block;
        &.active {

        }
      }
      .cardAns {
        display: block;
      }

      .cardFlip {
        position: absolute;
        backface-visibility: hidden;

        &.card-front {
        }

        &.card-back {
          transform: rotateY(180deg);
          width: 96%;
        }
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
