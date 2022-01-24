<template>
  <div class="main-content">
    <div class="status-header">
      <div class="header-title">捐贈總金額</div>
      <div
        class="header-info"
        ref="totalCount"
        v-html="convertNum(animatedValue)"
      ></div>
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
    <canvas class="fireworks" style="position: absolute; top: -200px"></canvas>
    <!-- <div v-for="i in 8" :key="i">
      <svg
        width="200"
        height="200"
        viewBox="0,0,200,200"
        :class="'svg'"
        style="display: block; position: absolute;"
        :style="{
          transform: `rotate(45deg) scale(${Math.random()})`,
          right: `calc( ${Math.random() * 100}% - 100px )`,
          bottom: (15+Math.random()*180) + 'px'
        }"
      >
        <circle cx="50" cy="100" r="50" fill="#FF4949" />
        <rect x="50" y="50" fill="#FF4949" width="100" height="100" />
        <circle cx="100" cy="50" r="50" fill="#FF4949" />
      </svg>
    </div> -->
  </div>
</template>

<script>
import anime from 'animejs';
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
    },
  },
  data() {
    return {
      animatedValue: 0,
      totalCount: 1234556,
      test: 0,
      firework: false
    }
  },
  methods: {
    convertNum(n) {
      return n.toLocaleString()
    },
    animateValue(v) {
      anime({
        targets: this,
        animatedValue: v,
        round: 1,
        easing: 'easeInSine',
        duration: 2000,
      }).finished.then(() => { this.firework = false })
    },
    autoClick() {
      var centerX = window.innerWidth / 2;
      var centerY = window.innerHeight / 2;
      if (window.human) return;
      this.animateParticules(
        anime.random(centerX, centerX + 50),
        anime.random(centerY - 10, centerY + 20)
      );
      this.animateParticules(
        anime.random(centerX - 300, centerX - 100),
        anime.random(centerY - 10, centerY + 10)
      );
      this.animateParticules(
        anime.random(centerX - 300, centerX - 100),
        anime.random(centerY - 30, centerY + 10)
      );
      anime({ duration: 1090 })
    },
    animateParticules(x, y) {
      var numberOfParticules = 110;
      var pointerX = 0;
      var pointerY = 0;
      var circle = createCircle(x, y);
      var colors = ['#FF1461', '#18FF92', '#5A87FF', '#FBF38C', 'cyan', 'red'];
      var particules = [];
      var canvasEl = document.querySelector('.fireworks');
      var ctx = canvasEl.getContext('2d');

      function setParticuleDirection(p) {
        var angle = anime.random(0, 360) * Math.PI / 180;
        var value = anime.random(50, 180);
        var radius = [-1, 1][anime.random(0, 1)] * value;
        return {
          x: p.x + radius * Math.cos(angle),
          y: p.y + radius * Math.sin(angle)
        }
      }

      function createParticule(x, y) {
        var p = {};
        p.x = x;
        p.y = y;
        p.color = colors[anime.random(0, colors.length - 1)];
        p.radius = anime.random(10, 30);
        p.endPos = setParticuleDirection(p);
        p.draw = function () {
          ctx.beginPath();
          ctx.arc(p.x, p.y, p.radius, 0, 2 * Math.PI, true);
          ctx.fillStyle = p.color;
          ctx.fill();
        }
        return p;
      }

      function createCircle(x, y) {
        var p = {};
        p.x = x;
        p.y = y;
        p.color = '#FFF';
        p.radius = 0.1;
        p.alpha = .5;
        p.lineWidth = 6;
        p.draw = function () {
          ctx.globalAlpha = p.alpha;
          ctx.beginPath();
          ctx.arc(p.x, p.y, p.radius, 0, 2 * Math.PI, true);
          ctx.lineWidth = p.lineWidth;
          ctx.strokeStyle = p.color;
          ctx.stroke();
          ctx.globalAlpha = 1;
        }
        return p;
      }

      function renderParticule(anim) {
        for (var i = 0; i < anim.animatables.length; i++) {
          anim.animatables[i].target.draw();
        }
      }
      for (var i = 0; i < numberOfParticules; i++) {
        particules.push(createParticule(x, y));
      }
      anime.timeline().add({
        targets: particules,
        x: function (p) { return p.endPos.x; },
        y: function (p) { return p.endPos.y; },
        radius: 0.1,
        duration: anime.random(1200, 1800),
        easing: 'easeOutExpo',
        update: renderParticule
      })
        .add({
          targets: circle,
          radius: anime.random(80, 160),
          lineWidth: 0,
          alpha: {
            value: 0,
            easing: 'linear',
            duration: anime.random(600, 800),
          },
          duration: anime.random(1200, 1800),
          easing: 'easeOutExpo',
          update: renderParticule,
          offset: 0
        });
    }
  },
  watch: {
    totalCount(newValue) {
      this.animateValue(newValue);
    }
  },
  mounted() {
    this.animateValue(this.totalCount);
    const evtSource = new EventSource(location.origin + "/event/charity/donation");
    evtSource.addEventListener("ranking_data", (event) => {
      console.log(event)
      //   this.autoClick()
    });
    evtSource.addEventListener("realtime_data", (event) => {
      console.log(event)
      this.autoClick()
    });
    // setInterval(() => {
    //   this.firework = true
    //   this.totalCount += 1000
    //   this.autoClick()
    // }, 8000);
    window.human = false;
    var canvasEl = document.querySelector('.fireworks');
    var ctx = canvasEl.getContext('2d');

    anime({
      duration: Infinity,
      update: function () {
        ctx.clearRect(0, 0, canvasEl.width, canvasEl.height);
      }
    });
    function setCanvasSize() {
      canvasEl.width = window.innerWidth * 2;
      canvasEl.height = window.innerHeight * 2;
      canvasEl.style.width = window.innerWidth + 'px';
      canvasEl.style.height = window.innerHeight + 'px';
      canvasEl.getContext('2d').scale(2, 2);
    }

    setCanvasSize();
  },
}
</script>

<style lang="scss" scoped>
.main-content {
  position: relative;
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
