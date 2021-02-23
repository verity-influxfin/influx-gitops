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
        <span :class="'card'+(index%2===0?'B':'A')" v-for="(d, index) in randkeys">
          <span class="cardFlip card-front">
            <span class="cardNum">{{faceNum[index]}}</span>
            <img class="cardFace" :src="'/images/cardGame'+(index+1)+'.png'">
          </span>
          <span class="cardFlip card-back">
            <span class="cardQuestion" :data-id="d">
              {{imgs[d].question}}<br /><br />
              <div class="cardAns" @click.once="ans" data-ans="A">(A){{imgs[d].selection[0]}}</div>
              <div class="cardAns" @click.once="ans" data-ans="B">(B){{imgs[d].selection[1]}}</div>
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
      imgs: {
        1: {
          question: '普匯目前主要業務項目為何?',
          selection: ['借貸/投資','保險'],
        },
        2: {
          question: 'p2p借貸的英文縮寫?',
          selection: ['peer to peer','pika to pika'],
        },
        3: {
          question: '普匯今年舉辦什麼活動?',
          selection: ['AI金融科技創新創意競賽','國外旅遊'],
        },
        4: {
          question: '普匯為下列何種類型公司?',
          selection: ['金融科技','科技金融'],
        },
        5: {
          question: '普匯是媒合誰與誰的平台?',
          selection: ['借款人與投資人','你和我'],
        },
        6: {
          question: '普匯的吉祥物叫?',
          selection: ['小普','來福'],
        },
        7: {
          question: '普匯的代表色為何?',
          selection: ['黑色','藍色'],
        },
        8: {
          question: '普匯主打的服務是?',
          selection: ['AI線上無人化','見面對保狂call你'],
        },
        9: {
          question: '普匯第一個上線的產品是?',
          selection: ['學生貸','房貸'],
        },
        10: {
          question: '普匯投資是多少元起投?',
          selection: ['1000元','1000萬'],
        },
        11: {
          question: '普匯創立於哪一年份??',
          selection: ['2017年','2025年'],
        },
        12: {
          question: '普匯APP於哪一年上線?',
          selection: ['2019年','尚未上線'],
        }
      }
    }
  },
  mounted:function () {
    this.randkeys = [1,2,3,4,5,6,7,8,9,10,11,12];
    for (let i = this.randkeys.length - 1; i > 0; i--) {
      let j = Math.floor(Math.random() * (i + 1));
      [this.randkeys[i], this.randkeys[j]] = [this.randkeys[j], this.randkeys[i]];
    }
    $(document).off("click",".cardA:not(.done),.cardB:not(.done)").on("click",".cardA:not(.done),.cardB:not(.done)" ,  function(e,t){
      $('.cardA,.cardB').hide();
      $(this).addClass('active').show();
    });
    let data = {
      user_id: localStorage.getItem("userData") ? JSON.parse(localStorage.getItem("userData"))["id"] : {},
    };
    axios
        .post("/getData", data)
        .then((res) => {
          if (res.data) {
            alert('您已參加過遊戲囉!!');
            location.replace('/');
          }
        })
        .catch((err) => {
          console.error(err);
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
                $('.cardA,.cardB').show();
                $('.active').removeClass('active');
                $('[data-id='+data.qnum+'] .cardAns:not([data-ans='+data.qans+'])').remove()
                if($('.done').length > 2){
                  alert('恭喜全部答對，請前往抽獎');
                  window.location.href = "/cardgame/turntable"
                }else {
                  alert('恭喜您答對了!! 下一題~')
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
    text-align: center;
    height: 1040px;

    .cardA,.cardB{
      width: 110px;
      height: 150px;
      display: inline-table;
      border-radius: 22px;
      border-width: 6px;
      border-style: solid;
      padding: 8px 5px;
      font-weight: bold;
      color: white;
      margin: 15px 5px 0;
      position: relative;
      transition: transform 1s;
      transform-style: preserve-3d;
      background-repeat: no-repeat;
      &.active {
        transform: rotateY(180deg);
        position: absolute;
        border-radius: 22px;
        width: 100%;
        height: 100%;
        margin: 0 0 0 0;
        border: 0;
        z-index: 999;
        left: 0;
        top: 86px;
        background-color: transparent;
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
        .card-back {
          padding: 0px 5px!important;
        }
        .cardQuestion {
          font-size: 12px;
        }
        .cardAns {
          font-size: 12px;
        }
      }

      .cardFace {
        margin: 45px 0px 0 8px;
        width: 75px;
        position: relative;
      }
      .cardNum {
        font-size: 24px;
        margin: -8px 0 0 4px;
        position: absolute;
      }
      .cardQuestion {
        font-size: 12px;
        padding: 0 5px;
        display: block;
        text-align: left;
      }
      .cardAns {
        display: block;
        font-size: 18px;
        height: 36px;;
      }

      .cardFlip {
        position: absolute;
        backface-visibility: hidden;
        left: 0px;
        &.card-back {
          transform: rotateY(180deg);
          width: 100%;
          height: 100%;
          background-repeat: no-repeat;
          padding: 76px 32px;
        }
      }
    }

    .cardA {
      background-color: #012060;
      border-color: #b40b12;
      &.active .card-back {
        background-image: url(/images/cardGameRedLine.svg);
      }
    }

    .cardB {
      background-color: #b40b12;
      border-color: #012060;
      &.active .card-back {
        background-image: url(/images/cardGameBlueLine.svg);
      }
    }
  }
}
</style>
