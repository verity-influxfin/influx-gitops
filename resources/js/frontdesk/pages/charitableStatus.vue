<template>
  <div class="out">
    <div class="main-content">
      <div class="test-img">
        <img class="img-1" src="~images/月亮.svg" alt="" />
      </div>
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
      const length = this.marqee.length * 1.5 > 10 ? this.marqee.length * 1.5 : 10
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
        duration: 4000,
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
  background-color: #cfeaf4;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 820px;
  width: 100%;
}
.main-content {
  width: 900px;
  height: fit-content;
  min-height: 600px;
  background-color: #fff;
  border-radius: 36px;
  .status-header {
    display: flex;
    flex-direction: column;
    padding: 12px 0;
    margin: 0 20px;
    border-bottom: 1px solid #fff;
    .header-title {
      font-size: 42px;
      font-style: normal;
      text-align: center;
    }
    .header-info {
      color: #f7c352;
      font-size: 48px;
      text-align: center;
    }
  }
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
      font-size: 35px;
      text-align: center;
      line-height: 70px;
      .rank-icon {
        border-radius: 50%;
        height: 70px;
        width: 70px;
        color: #fff;
        background-color: #e1effa;

        // &.rank-2 {
        //   background-color: #d9d9d9;
        // }
        // &.rank-3,
        // &.rank-4,
        // &.rank-5 {
        //   background-color: #c65916;
        // }
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
    margin: 0 75px;
    height: 75px;
    padding: 10px;
    position: relative;
    overflow: hidden;
    border: solid 6px #e1effa;
    border-radius: 18px;
    .footer-content {
      display: flex;
      width: max-content;
      position: absolute;
      transform: translate3d(var(--move-initial), 0, 0);
      animation: marquee 30s linear infinite;
      animation-play-state: running;
    }
    .footer-item {
      margin: 0 12px;
      font-size: 30px;
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
