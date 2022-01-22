<template>
  <div class="main-content">
    <div class="status-header">
      <div class="header-title">捐贈總金額</div>
      <div class="header-info" ref="totalCount">$123,456,789</div>
    </div>
    <div class="status-rank">
      <div class="rank-item" v-for="item in ranks" :key="item.rank">
        <div :class="[`rank-${item.rank}`, 'rank-icon']">{{ item.rank }}</div>
        <div class="rank-value">{{ convertNum(item.value) }}</div>
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
</template>

<script>
export default {
  computed: {
    ranks() {
      return [
        {
          rank: 1,
          value: 1234545
        },
        {
          rank: 2,
          value: 123455
        },
        {
          rank: 3,
          value: 14545
        }
      ]
    },
    marqee() {
      let a = []
      for (let index = 0; index < 30; index++) {
        a.push({
          name: 'test-' + index,
          value: Math.floor(Math.random() * 10000)
        })
      }
      return a
    },
    duration() {
      const length = this.marqee.length * 1.5 > 10 ? this.marqee.length * 1.5 : 10
      return {
        animationDuration: length + 's'
      }
    }
  },
  methods: {
    convertNum(n) {
      return n.toLocaleString()
    }
  },
}
</script>

<style lang="scss" scoped>
.main-content {
  margin: 30px auto;
  width: 900px;
  border: 1px solid #5a6a7c;
  .status-header {
    display: flex;
    flex-direction: column;
    padding: 12px 0;
    margin: 0 20px;
    border-bottom: 1px solid #5a6a7c;
    .header-title {
      font-size: 30px;
      font-style: normal;
      text-align: center;
    }
    .header-info {
      color: #f7c352;
      font-size: 36px;
      text-align: center;
    }
  }
  .status-rank {
    padding: 20px 0;
    margin: 0 12px;
    border-bottom: 1px solid #5a6a7c;
    .rank-item {
      display: flex;
      padding: 10px;
      font-size: 30px;
      text-align: center;
      line-height: 50px;
      margin-left: 315px;
      .rank-icon {
        border-radius: 50%;
        height: 50px;
        width: 50px;
        color: #fff;
        &.rank-1 {
          background-color: #fedf05;
        }
        &.rank-2 {
          background-color: #d9d9d9;
        }
        &.rank-3,
        &.rank-4,
        &.rank-5 {
          background-color: #c65916;
        }
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
    .footer-content {
      display: flex;
      width: max-content;
      position: absolute;
      transform: translate3d(var(--move-initial), 0, 0);
      animation: marquee 20s linear infinite;
      animation-play-state: running;
    }
    .footer-item {
      margin: 0 12px;
      font-size: 30px;
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
