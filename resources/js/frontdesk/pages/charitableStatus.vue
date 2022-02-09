<template>
  <div class="out">
    <img class="status-logo-top" src="~images/status-logo-top.png" alt="" />
    <div class="cloud-1">
      <div class="cloud-out">
        <img class="cloud" src="~images/cloud-1.svg" alt="" />
      </div>
    </div>
    <img class="status-whale" src="~images/status-whale.webp" alt="" />
    <img class="status-bird" src="~images/status-bird.webp" alt="" />
    <div class="cloud-2">
      <div class="cloud-out">
        <img class="cloud" src="~images/cloud-2.svg" alt="" />
      </div>
    </div>
    <div class="cloud-3">
      <div class="cloud-out">
        <img class="cloud" src="~images/cloud-3.svg" alt="" />
      </div>
    </div>
    <div class="main-content">
      <div class="status-rank">
        <div class="rank-item" v-for="(item, index) in ranks" :key="item.rank">
          <div class="rank-icon">{{ item.rank }}</div>
          <div :class="[`rank-${item.rank}`, 'rank-img']"></div>
          <div class="rank-value" v-html="rankArray[index]"></div>
          <div class="">元</div>
        </div>
      </div>
      <div class="status-footer">
        <div class="footer-content" :style="duration">
          <div class="footer-item" v-for="item in marqee" :key="item.name">
            感謝 {{ item.name }} 捐贈 {{ convertNum(item.value) }} 元
          </div>
        </div>
      </div>
    </div>
    <img
      class="status-logo-bottom"
      src="~images/status-logo-bottom.png"
      alt=""
    />
    <img class="status-rocket" src="~images/status-rocket.png" alt="" />
    <img class="status-left-child" src="~images/status-left-child.svg" alt="" />
    <img
      class="status-right-child"
      src="~images/status-right-child.svg"
      alt=""
    />
    <img class="dinisaurs" src="~images/dinosaurs.png" alt="" />
    <img class="cloud-left" src="~images/cloud-left.svg" alt="" />
    <img class="cloud-bg-left" src="~images/cloud-bottom-bg.svg" alt="" />
    <img class="cloud-bg-right" src="~images/cloud-bottom-bg.svg" alt="" />
    <img class="cloud-long" src="~images/cloud-long.svg" alt="" />
    <img class="cloud-right" src="~images/cloud-right.svg" alt="" />
    <img class="firework-1 firework" src="~images/firework-1.svg" alt="" />
    <img class="firework-2 firework" src="~images/firework-2.svg" alt="" />
    <img class="firework-3 firework" src="~images/firework-3.svg" alt="" />
    <img class="firework-4 firework" src="~images/firework-4.svg" alt="" />
  </div>
</template>

<script>
import anime from 'animejs';
export default {
  computed: {
    ranks() {
      // rank,value
      console.log(typeof this.rankOriginal, this.rankOriginal)
      return this.rankOriginal.map((x, i) => {
        return {
          value: x.amount,
          id: x.id,
          rank: i + 1,
        }
      })
    },
    marqee() {
      // name,value
      return this.realtimeOriginal.map(({ name, id, amount }) => {
        return {
          name,
          id,
          value: amount
        }
      })
    },
    duration() {
      const length = this.marqee.length * 1.5 > 15 ? this.marqee.length * 1.5 : 15
      return {
        animationDuration: length + 's'
      }
    },
    rankArray() {
      return this.animatedRank.trim().split(' ').map(x => this.convertNum(x))
    }
  },
  data() {
    return {
      animatedRank: '',
      rankOriginal: [],
      realtimeOriginal: [],
      test: 0,
      firework: false
    }
  },
  methods: {
    convertNum(n) {
      if (!isNaN(Number(n))) {
        return Number(n).toLocaleString()
      }
      return n
    },
    animateRank(v) {
      const test = v.map(x => x.value).toString().split(',').join(' ')
      anime({
        targets: this,
        animatedRank: test,
        round: 1,
        easing: 'easeInSine',
        duration: 2000,
      })
    },
  },
  watch: {
    ranks(v) {
      this.animateRank(v)
    }
  },
  mounted() {
    this.animateRank(this.ranks);
    const evtSource = new EventSource(location.origin + "/event/charity/donation");
    evtSource.addEventListener("ranking_data", (event) => {
      if (JSON.stringify(this.rankOriginal) !== JSON.stringify(event.data)) {
        this.rankOriginal = JSON.parse(event.data)
      }
    });
    evtSource.addEventListener("realtime_data", (event) => {
      const arrData = JSON.parse(event.data)
      if (this.realtimeOriginal.length !== arrData.length) {
        this.realtimeOriginal = arrData
        this.autoClick()
      }
    });
    // evtSource.addEventListener("pong", (event) => {
    //   evtSource.close()
    //   console.log('close')
    // });
  },
}
</script>

<style lang="scss" scoped>
.out {
  padding-top: 120px;
  position: relative;
  background-color: #cfeaf4;
  min-height: 920px;
  width: 100%;
}
.cloud-out {
  position: relative;
  .cloud {
    position: absolute;
    animation: cloud 7s linear infinite;
  }
}

.cloud-1 {
  position: absolute;
  right: 30px;
  width: 200px;
  top: 53px;
  z-index: 3;
}
.cloud-2 {
  position: absolute;
  right: 190px;
  width: 100px;
  top: 193px;
}
.cloud-3 {
  position: absolute;
  left: 30px;
  top: 170px;
}
.cloud-shoadow {
  box-shadow: 0 3px 6px rgba($color: #000000, $alpha: 0.2);
}
.status-logo-top {
  position: absolute;
  top: -10px;
  transform: scale(0.8);
  left: -10px;
}
.status-whale {
  position: absolute;
  top: 83px;
  right: 200px;
  width: 185px;
}
.status-bird {
  position: absolute;
  top: 180px;
  left: 270px;
}
.status-logo-bottom {
  position: absolute;
  left: 760px;
  bottom: 130px;
}
.status-rocket {
  position: absolute;
  bottom: 180px;
  left: 120px;
  transform: scale(0.75);
}
.status-left-child {
  position: absolute;
  left: 420px;
  bottom: 80px;
  z-index: 3;
}
.status-right-child {
  position: absolute;
  right: 330px;
  bottom: 80px;
  z-index: 3;
}
.dinisaurs {
  position: absolute;
  right: 30px;
  bottom: 100px;
  width: 204px;
  z-index: 3;
}
.cloud-left {
  position: absolute;
  left: -220px;
  bottom: 0;
  z-index: 1;
}
.cloud-long {
  position: absolute;
  bottom: 0;
  width: 100%;
  z-index: 2;
}
.cloud-right {
  position: absolute;
  right: -250px;
  bottom: 0;
  z-index: 1;
}
.cloud-bg-left {
  position: absolute;
  bottom: 0;
  left: -80px;
  z-index: 0;
}
.cloud-bg-right {
  position: absolute;
  bottom: -50px;
  right: 0;
  z-index: 0;
}
.firework-1 {
  position: absolute;
  right: 210px;
  top: 240px;
}
.firework-2 {
  position: absolute;
  right: 26px;
  top: 418px;
}
.firework-3 {
  position: absolute;
  left: 244px;
  top: 550px;
}
.firework-4 {
  position: absolute;
  left: 48px;
  top: 340px;
}
.firework {
  animation-name: firework;
  animation-duration: 3s;
  animation-delay: 0;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
  animation-play-state: running;
}

@keyframes firework {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.main-content {
  margin: 0 auto;
  width: 900px;
  height: fit-content;
  min-height: 500px;
  background-color: #fff;
  border-radius: 36px;
  .status-rank {
    padding: 20px 0;
    margin: 0 12px;
    border-bottom: 1px solid #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    .rank-item {
      display: flex;
      padding: 10px;
      font-size: 30px;
      text-align: center;
      line-height: 55px;
      .rank-icon {
        border-radius: 50%;
        height: 55px;
        width: 55px;
        color: #fff;
        background-color: #e1effa;
      }
      .rank-img.rank-1::before {
        content: '';
        background-image: url('~images/rank-1.svg');
        width: 78px;
        height: 40px;
        display: inline-block;
        margin: 0 12px 0 24px;
      }
      .rank-img.rank-2::before {
        content: '';
        background-image: url('~images/rank-2.svg');
        width: 78px;
        height: 40px;
        display: inline-block;
        margin: 0 12px 0 24px;
      }
      .rank-img.rank-3::before {
        content: '';
        background-image: url('~images/rank-3.svg');
        width: 78px;
        height: 40px;
        display: inline-block;
        margin: 0 12px 0 24px;
      }
      .rank-img.rank-4::before {
        content: '';
        margin: 0 59px;
      }
      .rank-img.rank-5::before {
        content: '';
        margin: 0 59px;
      }
      .rank-value {
        margin-left: 12px;
        width: 150px;
      }
    }
  }

  .status-footer {
    margin: 0 55px;
    height: 55px;
    padding: 10px;
    position: relative;
    overflow: hidden;
    border: solid 6px #e1effa;
    border-radius: 18px;
    .footer-content {
      top: 5px;
      display: flex;
      width: max-content;
      position: absolute;
      transform: translate3d(var(--move-initial), 0, 0);
      animation: marquee 30s linear infinite;
      animation-play-state: running;
    }
    .footer-item {
      margin: 0 12px;
      font-size: 26px;
    }
  }
  .test-img {
    position: relative;
    .img-1 {
      position: absolute;
      animation: cloud 2s linear infinite;
      animation-play-state: running;
    }
  }
  @keyframes cloud {
    0% {
      left: 0px;
    }
    25% {
      left: 15px;
    }
    50% {
      left: 0;
    }
    75% {
      left: -15px;
    }
    100% {
      left: 0;
    }
  }
  @keyframes marquee {
    0% {
      left: 100%;
      transform: translateX(0%);
    }

    100% {
      left: 0;
      transform: translateX(-100%);
    }
  }
}
</style>
