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
      <div class="cover"></div>
      <img :src="'/images/turntable.png'" />
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
              var ans = res.data.ans == 1,
                  finish = $('.done').length;
              if(finish > 2){
                alert('恭喜全部答對，請前往抽獎');
                window.location.href = "/truntable"
              }else{
                if(ans){
                  alert('恭喜您答對了!! 下一題~')
                  $('.active:not(.done)').addClass('done');
                  $('.active').removeClass('active');
                  $('[data-id='+data.qnum+'] .cardAns:not([data-ans='+data.qans+'])').remove()
                }else{
                  alert('答錯囉~再讓我們玩一次吧！');
                  window.location.href = "/cardgame"
                }
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
    background-color:#ffd186;
    img{
      width: 80%;
    }
    .cover {
      background-color: #aa1933;
      z-index: 99;
      width: 50px;
      height: 50px;
      position: absolute;
    }
  }
}
</style>
