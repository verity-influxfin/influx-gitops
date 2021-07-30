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
      <div class="countdown">00:<span>10</span></div>
      <template >
        <span :class="'card'+(index%2===0?'B':'A')" v-for="(d, index) in randkeys" :key="index">
          <span class="cardFlip card-front" @click.once="timer()">
            <span class="cardNum">{{faceNum[index]}}</span>
            <img class="cardFace" :src="'/images/cardGame'+(index+1)+'.png'">
          </span>
          <span class="cardFlip card-back">
            <span class="cardQuestion" :data-id="d">
              <span v-html="imgs[d].question"></span><br /><br />
              <div class="cardAns" @click.once="(e) => {ans(e); stopTime()}" data-ans="A">(A){{imgs[d].selection[0]}}</div>
              <div class="cardAns" @click.once="(e) => {ans(e); stopTime()}" data-ans="B">(B){{imgs[d].selection[1]}}</div>
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
          question: '普匯目前主要<br />業務項目為何?',
          selection: ['借貸/投資','保險'],
        },
        2: {
          question: 'p2p借貸的<br />英文縮寫為何?',
          selection: ['peer to peer','pika to pika'],
        },
        3: {
          question: '普匯今年<br />舉辦什麼活動?',
          selection: ['AI金融科技創新創意競賽','國外旅遊'],
        },
        4: {
          question: '普匯為下列<br />何種類型公司?',
          selection: ['金融科技','科技金融'],
        },
        5: {
          question: '普匯是媒合<br />誰與誰的平台?',
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
          question: '普匯第一個<br />上線的產品是?',
          selection: ['學生貸','房貸'],
        },
        10: {
          question: '普匯投資是<br />多少元起投?',
          selection: ['1000元','1000萬'],
        },
        11: {
          question: '普匯創立於<br />哪一年份?',
          selection: ['2017年','2025年'],
        },
        12: {
          question: '普匯APP於<br />哪一年上線?',
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
    $('.countdown').hide();
    $(document).off("click",".cardA:not(.done):not(.active),.cardB:not(.done):not(.active)").on("click",".cardA:not(.done):not(.active),.cardB:not(.done):not(.active)" ,  function(e,t){
      $('.cardA,.cardB').hide();
      $(this).addClass('active').show();
      $('.countdown').show();
      $('.countdown span').text('10');
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
          }else{
            alert('遊戲流程：\n' +
                '◆從12張卡牌中，挑選喜愛的3個生肖回答普匯相關題目\n' +
                '◆每題限時10秒，30秒即可完成遊戲\n' +
                '◆答錯可重新遊戲直到全對');
          }
        })
        .catch((err) => {
          console.error(err);
        });
  },
  methods: {
    timer(){
      let my = this
      this.time = setInterval(function(){
        var time = $('.countdown span');
        if(time.text()>0){
          time.text('0'+(time.text()-1));
        }
        else if(time.text()==0){
          $('.countdown').hide();
          time.text('*');
          alert('時間到囉~請重新挑戰');
          location.replace('/cardgame');
        }
      },1000);
    },
    setTime() {
      this.timer()
    },
    stopTime() {
      if (this.time) {
        clearInterval(this.time)
      }
    },
    ans(event) {
      $('.countdown').hide();
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
                this.countDown = false;
                if($('.done').length > 2){
                  alert('恭喜全部答對，請前往抽獎');
                  window.location.href = "/cardgame/turntable"
                }else {
                  alert('恭喜您答對了!! 下一題~');
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

    .countdown {
      font-size: 28px;
      font-weight: bold;
      letter-spacing: 2px;
      color: red;
    }

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
        -moz-transform:rotateY(180deg);
        -webkit-transform:rotateY(180deg);
        -o-transform:rotateY(180deg);
        -ms-transform:rotateY(180deg);
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
        .cardFlip {
          -webkit-backface-visibility: visible;
          backface-visibility: visible;
        }
      }
      &.done {
        transform: rotateY(180deg);
        -moz-transform:rotateY(180deg);
        -webkit-transform:rotateY(180deg);
        -o-transform:rotateY(180deg);
        -ms-transform:rotateY(180deg);
        .card-front {
          display: none;
        }
        .card-back {
          padding: 0px!important;
        }
        .cardQuestion {
          font-size: 12px;
        }
        .cardAns {
          font-size: 12px;
        }
        .cardFlip {
          -webkit-backface-visibility: visible;
          backface-visibility: visible;
          position: absolute;
        }
      }

      .cardFace {
        margin: 36px 0px 0 11px;
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
      }
      .cardAns {
        display: block;
        font-size: 26px;
        width: 100%;
        text-align: center;
      }

      .cardFlip {
        position: absolute;
        backface-visibility: hidden;
        left: 0px;
        padding: 0 5px;
        &.card-back {
          transform: rotateY(180deg);
          -moz-transform:rotateY(180deg);
          -webkit-transform:rotateY(180deg);
          -o-transform:rotateY(180deg);
          -ms-transform:rotateY(180deg);
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
