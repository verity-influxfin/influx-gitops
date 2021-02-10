<template>
  <div class="turntable-wrapper">
    <nav class="page-header navbar navbar-expand-lg">
      <div class="web-logo">
        <router-link to="index"
        ><img :src="'/images/logo_new.png'" class="img-fluid"
        /></router-link>
      </div>
    </nav>
    <div class="turntable">
      <div class="cover" @click="turn">{{flag ? '1':'2'}}普匯<br/>好好玩</div>
      <div class="arrow"></div>
      <div class="disk" style="background-image: url(/images/turntable.png)"></div>
    </div>
    <li class="nav-item" v-if="!flag || flag === 'logout'">
      <p class="nav-link l" href="#" @click="openLoginModal()"><i class="fas fa-user"></i>SIGN IN</p>
    </li>
<!--    <li v-if="Object.keys(userData).length !== 0" class="nav-item dropdown">-->
<!--      <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">您好 @{{userData.name}}</a>-->
<!--      <ul class="dropdown-menu" style="min-width: 5rem;">-->
<!--        <li v-if="isInvestor == 0">-->
<!--          <router-link class="dropdown-item loan-link" to="/loannotification">借款人</router-link>-->
<!--        </li>-->
<!--        <li v-else>-->
<!--          <router-link class="dropdown-item invest-link" to="/investnotification">投資人</router-link>-->
<!--        </li>-->
<!--        <li v-if="flag === 'login'">-->
<!--          <p class="dropdown-item" @click="logout">登出</p>-->
<!--        </li>-->
<!--      </ul>-->
<!--    </li>-->
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
  },
  methods: {
    turn(event) {
      if(!this.process){
        this.process = true;
        let data = {
          qnum: event.target.parentElement.dataset.id,
          qans: event.target.dataset.ans,
        };
        // axios
        //     .post("/getAns", data)
        //     .then((res) => {
        //       var ans = res.data.ans == 1,
        //           finish = $('.done').length;
        //       if(finish > 2){
        //         alert('恭喜全部答對，請前往抽獎');
        //         window.location.href = "/truntable"
        //       }else{
        //         if(ans){
        //           alert('恭喜您答對了!! 下一題~')
        //           $('.active:not(.done)').addClass('done');
        //           $('.active').removeClass('active');
        //           $('[data-id='+data.qnum+'] .cardAns:not([data-ans='+data.qans+'])').remove()
        //         }else{
        //           alert('答錯囉~再讓我們玩一次吧！');
        //           window.location.href = "/cardgame"
        //         }
        //       }
        //       this.process = false;
        //     })
        //     .catch((err) => {
        //       console.error(err);
        //     });
        console.log('test');
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

  .turntable {
    background-color: #ffd186;
    width: 100%;
    height: 100%;
    position: absolute;
    padding: 100px 0 0 0;

    .disk {
      background-image: url(/images/turntable.png);
      background-repeat: no-repeat;
      background-size: 100%;
      background-position: 0 -8px;
      width: 100%;
      height: 359px;
      position: absolute;
      transition: 3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
      transform-style: preserve-3d;
      transform: rotate(3600deg);
    }
    .arrow {
      background-color: #aa1933;
      z-index: 99;
      width: 5px;
      height: 24px;
      display: block;
      top: 233px;
      left: 186px;
      position: absolute;
    }
    .cover {
      background-color: #aa1933;
      z-index: 99;
      width: 50px;
      height: 24px;
      display: block;
      top: 261px;
      left: 164px;
      position: absolute;
      color: white;
      text-align: center;
      font-weight: bold;
      letter-spacing: 0.5px;
    }
  }
}
</style>
