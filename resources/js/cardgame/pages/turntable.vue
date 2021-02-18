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
  },
  methods: {
    turn(event) {
      if(!this.process){
        this.process = true;
        let data = {
          user_id: localStorage.getItem("userData") ? JSON.parse(localStorage.getItem("userData"))["id"]: {},
          // qans: event.target.dataset.ans,
        };
        axios
            .post("/setGamePrize", data)
            .then((res) => {
              var prize = res.data.prize;

              this.process = false;
            })
            .catch((err) => {
              console.error(err);
            });
        console.log(data.user_id);
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
    padding: 160px 0 300px;
    text-align: center;

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
        transition: 3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        transform-style: preserve-3d;
        transform: rotate(3600deg);
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
