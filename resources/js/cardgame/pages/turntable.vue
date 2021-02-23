<template>
  <div class="turntable-wrapper" @click.once="turn()">
    <nav class="page-header navbar navbar-expand-lg">
      <div class="web-logo">
        <router-link to="index"
        ><img :src="'/images/logo_new.png'" class="img-fluid"
        /></router-link>
      </div>
    </nav>
    <div class="block">
      <ul>抽獎辦法：
        <li>每用戶只可抽獎一次</li>
        <li>活動截止後由公司寄發中獎簡訊通知</li>
        <li>本公司保有隨時修改本活動之權利，如有任何變更內容或詳細注意事項將公布於官網</li>
      </ul>
      <div class="turntable">
        <div class="arrow"></div>
        <div class="disk"></div>
      </div>
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
      picTop: ['40','50','50','41','49','41','43','50','56','43','49','49'],
      picLeft: ['4','8','4','5','5','3','1','7','6','2','6','2','6',],
    }
  },
  mounted:function () {
    //todo random
    this.randkeys = [1,2,3,4,5,6,7,8,9,10,11,12];
    $(document).off("click",".cardA,.cardB").on("click",".cardA,.cardB" ,  function(e,t){
      $(this).addClass('active');
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
            var email = prompt('請輸入您的email以利中獎通知：');
            localStorage.setItem('email', email);
          }
        })
        .catch((err) => {
          console.error(err);
        });
  },
  methods: {
    turn(event) {
      if(!this.process){
        this.process = true;
        let data = {
          user_id: localStorage.getItem("userData") ? JSON.parse(localStorage.getItem("userData"))["id"]: {},
          email: localStorage.getItem("email"),
        };
        axios
            .post("/setGamePrize", data)
            .then((res) => {
              if(res.data.prize != undefined){
                var prize = res.data.prize;
                var rotate = res.data.rotate * 1 + 3000*1;
                this.process = false;
                $('.turntable .disk').css('transform','rotate('+ rotate +'deg)');
                setTimeout(function() {
                  alert("恭喜抽中" + prize + "！\n\n※活動截止後由公司寄發中獎簡訊通知");
                  location.replace('/');
                }, 11000);
              }else {
                alert("已經玩過遊戲囉!" + (res.data!=''?'抽中的是->'+ res.data:''));
                location.replace('/');
              }
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
.turntable-wrapper {
  .page-header {
    z-index: 11;
    background-image: linear-gradient(to right, #1e2973 0%, #319acf 36%, #1e2973 73%);

    .web-logo {
      width: 140px;
      margin: 0px;
    }
  }

  .block {
    background-color: #ffd186;
    width: 100%;
    height: 100%;
    padding: 10px 0 300px;
    text-align: center;

    ul {
      font-weight: bold;
      font-size: 18px;
      margin: 10px 0 15px ;
      text-align: left;
    }

    li {
      font-weight: 400;
      font-size: 14px;
    }

    .turntable {
      background-color: #ffd186;
      display: inline-block;

      .disk {
        background-image: url(/images/turntable.png);
        background-repeat: no-repeat;
        background-size: 100%;
        background-position: 0 0px;
        background-color: transparent;
        width: 350px;
        height: 350px;
        transition: 10s cubic-bezier(0.1, 0.46, 0, 0.94);
        transform-style: preserve-3d;
        transform: rotate(0deg);
        display: inline-block;
      }

      .arrow {
        background-image: url(/images/turntableArrow.png);
        background-repeat: no-repeat;
        background-size: 100%;
        z-index: 99;
        width: 70px;
        height: 105px;
        display: inline-block;
        position: absolute;
        margin: 120px 0 0 142px;
      }
    }
  }
}
</style>
