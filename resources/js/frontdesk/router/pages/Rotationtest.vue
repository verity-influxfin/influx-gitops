<template>
  <div>
    <div id="prize-box">
      <div
        :class="['prize', {active : active === index}]"
        :style="{ transform: transformHandler(index, 'prize') }"
        v-for="(item, index) in dataCache[year]"
        :key="index"
      >
        <div
          :style="{ transform: transformHandler(index, 'content') }"
          :class=" ['prize-content', {small : dataCache[year].length > 10}]"
        >
          <div class="prize-icon">
            <i class="material-icons">{{ item.icon }}</i>
          </div>
          <div class="prize-text">{{ textOrNumber(item, 'prize') }}</div>
          <div class="prize-count">{{ item.count }}</div>
        </div>
      </div>
    </div>
    <div id="hand" @click="pressHandler()" :style="{transform: rotateHandler()}">
      <button class="press">PRESS</button>
    </div>
    <div id="well-done" v-if="active > -1">
      <div class="text">
        <p>
          WELL
          <br />DONE!
        </p>
      </div>
      <div class="get">
        <p>YOUR GET A FREE...</p>
        <p class="item">{{ textOrNumber(dataCache, 'well-done') }}!</p>
      </div>
      <div class="icon" v-for="index in 10" :key="index">
        <i class="material-icons">{{ getIcon() }}</i>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    dataCache: {},
    year: "2017",
    isPressed: false,
    degree: 0,
    active: -1
  }),
  created() {
    this.getData();
  },
  methods: {
    async getData() {
      var $this = this;
      $.ajax({
        url: "./data/rotationData.json",
        dataType: "json",
        type: "POST",
        success(res) {
          const keys = Object.keys(res);
          keys.forEach(item => {
            Vue.set($this.dataCache, item, res[item]);
          });
          const thisYear = $this.dataCache[2017];
          console.log(thisYear);
          $this.initData(thisYear);
        },
        error(err) {
          console.log(err);
        }
      });
    },
    initData(thisYear) {
      for (let i = 0; i < thisYear.length; i++) {
        const keys = Object.keys(thisYear[i]);
        if (keys.indexOf("start") !== -1 && keys.indexOf("end") !== -1) {
          const start = thisYear[i].start;
          const end = thisYear[i].end;
          const count = thisYear[i].count;
          let cache = [];
          for (let j = start; j <= end; j++) {
            cache.push({
              num: j,
              count: count
            });
          }
          thisYear.splice(i + 1, 0, ...cache);
          thisYear.splice(i, 1);
        }
      }
    },
    transformHandler(index, location) {
      let len = this.dataCache[this.year].length;
      let rotate = 360 / len;
      let rotateFrom = -rotate / 2;
      let skewY = rotate - 90;
      if (location === "prize") {
        return `rotate(${rotateFrom + index * rotate}deg) skewY(${skewY}deg)`;
      }
      if (location === "content") {
        let translate =
          len > 10 ? "translate(19px, 110px)" : "translate(70px, 45px)";
        return `skewY(${90 - rotate}deg) rotate(${rotate / 2}deg) ${translate}`;
      }
    },
    pressHandler() {
      if (this.isPressed) return;
      let data = this.dataCache[this.year];
      let index = this.getRandomNumber(data);
      if (index == undefined) return;
      this.isPressed = true;
      this.active = -1;
      let circle = 6;
      let len = data.length;
      let rotate = circle * 360 + index * (360 / len);
      this.degree += rotate - (this.degree % 360);
      setTimeout(() => {
        this.active = index;
        if (data[index].count > 0) {
          data[index].count -= 1;
        }
        console.log(
          this.year + "年，獎項：" + (index + 1),
          "剩餘：" + data[index].count + "個"
        );
        this.isPressed = false;
      }, 6000);
    },
    rotateHandler() {
      return `rotate(${this.degree}deg)`;
    },
    getIcon() {
      if (this.active > -1) {
        return this.year === "2017"
          ? this.dataCache[this.year][this.active].icon
          : "cake";
      }
    },
    textOrNumber(item, location) {
      if (location === "prize") {
        return this.year === "2017" ? item.text : item.num;
      }
      if (location === "well-done") {
        return this.year === "2017"
          ? this.dataCache[this.year][this.active].text
          : this.dataCache[this.year][this.active].num;
      }
    },
    getRandomNumber(data) {
      const index = this.generateIndex(data);
      let num = Math.floor(Math.random() * index.length);
      return index[num];
    },
    generateIndex(data) {
      let indexArray = []
      for (let i = 0; i < data.length; i++) {
        if (data[i].count !== 0) indexArray.push(i)
      }
      return indexArray
    }
  }
};
</script>

<style lang="scss">
#wheel {
  width: 549px;
  height: 549px;
  display: flex;
  justify-content: center;
  align-items: center;
}
#wheel::after {
  content: "";
  width: 549px;
  height: 549px;
  background: transparent #000000 50% 50% no-repeat
    padding-box;
  position: absolute;
  z-index: 10;
}
#hand {
  position: absolute;
  width: 200px;
  height: 289px;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: all 6s cubic-bezier(0.38, 0.22, 0.1, 0.99) 0s;
  z-index: 15;
  transform-origin: 100px 145px;
}
#hand::after {
  content: "";
  width: 200px;
  height: 289px;
  background: transparent #ff00ff 50% 0% no-repeat padding-box;
  position: absolute;
  z-index: 20;
  pointer-events: none;
}
.press {
  height: 105px;
  width: 105px;
  background: #1f1172;
  outline: none;
  border: none;
  border-radius: 50%;
  padding: 0;
  color: #ff00ba;
  font-size: 32px;
  font-family: "Roboto Condensed", sans-serif;
  z-index: 15;
}
.years {
  height: 35px;
  width: 80px;
  background: #1f1172;
  outline: none;
  border: none;
  border-radius: 10px;
  padding: 0;
  color: #ff00ba;
  font-size: 18px;
  font-family: "Roboto Condensed", sans-serif;
  z-index: 15;
  margin-left: 10px;
}
.refresh {
  background: #f0beff;
  color: #343caa;
  padding-top: 2px;
}
#change-year {
  position: absolute;
  top: 20px;
  left: 20px;
  display: flex;
  align-items: center;
}
/** PRIZE **/
#prize-box {
  width: 497px;
  height: 497px;
  border-radius: 50%;
  background-color: #343caa;
  z-index: 4;
  position: absolute;
  overflow: hidden;
}
.prize {
  width: 50%;
  height: 50%;
  background-color: #f0beff;
  color: #343caa;
  border: 1px solid #1f1172;
  position: absolute;
  top: 0;
  right: 0;
  transform-origin: 0 100%;
  box-sizing: border-box;
}
.prize.active {
  background-color: #ff00ba;
  color: white;
}
.prize.active:nth-of-type(2n) {
  background-color: #ff00ba;
  color: white;
}
.prize:nth-of-type(2n) {
  background-color: #343caa;
  color: #f0beff;
}
.prize-content {
  width: 100px;
  height: 100px;
  display: flex;
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
.small {
  width: 60px;
  height: 60px;
}
.prize-icon i {
  font-family: "Material Icons";
  font-weight: normal;
  font-style: normal;
  font-size: 4rem;
}
.prize-text {
  font-size: 2rem;
}
.prize-count {
  height: 45px;
  width: 30px;
  background-color: #343caa;
  color: #f0beff;
  text-align: center;
  border-radius: 10px;
  margin-top: 5px;
}
.prize:nth-of-type(2n) .prize-count {
  background-color: #f0beff;
  color: #343caa;
}
/** Well Done! **/
#well-done {
  width: 100%;
  height: 237px;
  position: absolute;
  background: #343baa;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: white;
  overflow: hidden;
}
#well-done .text {
  font-size: 72px;
  margin-left: 8%;
  line-height: 72px;
  z-index: 5;
}
#well-done .get {
  font-size: 32px;
  margin-right: 6%;
  z-index: 3;
}
#well-done .get .item {
  font-size: 72px;
  color: #ff00ba;
  text-decoration: underline;
}
#well-done .icon {
  position: absolute;
  top: 150px;
  left: -1%;
  z-index: 2;
}
#well-done .icon:nth-of-type(3) {
  top: 25px;
  left: 2%;
  transform: rotate(20deg);
}
#well-done .icon:nth-of-type(4) {
  top: 210px;
  left: 5%;
  transform: rotate(-30deg);
}
#well-done .icon:nth-of-type(5) {
  top: -10px;
  left: 20%;
  transform: rotate(-30deg);
}
#well-done .icon:nth-of-type(6) {
  top: 150px;
  left: 28%;
}
#well-done .icon:nth-of-type(7) {
  top: -5px;
  left: 70%;
}
#well-done .icon:nth-of-type(8) {
  top: 210px;
  left: 78%;
  transform: rotate(20deg);
}
#well-done .icon:nth-of-type(9) {
  top: 150px;
  left: 92%;
  transform: rotate(-20deg);
}
#well-done .icon:nth-child(10) {
  top: 20px;
  left: 98%;
  transform: rotate(20deg);
}
#well-done .icon i {
  font-size: 3rem;
  color: #22299b;
}
</style>
