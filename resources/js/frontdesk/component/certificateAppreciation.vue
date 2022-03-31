<template>
  <div class="cert">
    <div class="cert-title">感謝狀</div>
    <div class="pictures">
      <img class="bird" src="~images/NTUCHbird.png" alt="" />
      <img class="ballon" src="~images/NTUCHballon.png" alt="" />
    </div>
    <div class="cert-solgan">台大兒醫 普匯希望</div>
    <div class="cert-info">
      感謝捐款人 {{ donateData.donator_name }} {{ donateData.donator_sex }}
    </div>
    <div class="cert-info">捐助新台幣 {{ donateData.amount }} 元</div>
    <div class="cert-info mb-2">給台大兒童健康基金會</div>
    <div class="pictures">
      <img class="rocket" src="~images/NTUCH02F.png" alt="" />
    </div>
    <div class="cert-info">感謝您的贊助，因為有您，</div>
    <div class="cert-info">孩子們擁有不一樣的精彩</div>
    <div class="pictures mt-3">
      <img class="logo" src="~images/status-logo-bottom.png" alt="" />
      <img class="whale" src="~images/NTUCHwhale.png" alt="" />
      <img class="dinosaurs" src="~images/dinosaurs.png" alt="" />
      <img class="animals" src="~images/charitable-footer.png" alt="" />
    </div>
    <div class="cert-info">
      {{ renderDate }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    donateData: {
      type: Object,
      default: {
        tx_datetime: '',
        amout: 0,
        donator_name: '',
        donator_sex: ''
      }
    },
  },
  computed: {
    renderDate() {
      const dateArr = this.donateData.tx_datetime.split('-')
      return `中華民國 ${dateArr[0]} 年 ${dateArr[1]} 月 ${dateArr[2]} 日`
    }
  },
}
</script>

<style lang="scss" scoped>
.cert {
  padding: 20px;
  max-width: 425px;
  display: flex;
  flex-direction: column;
  align-items: center;
  background-image: url('../asset/images/ntu-bg.jpg');
  background-repeat: no-repeat;
  background-position: center center;
  box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2);
  .cert-title {
    margin-bottom: 5px;
    color: #000;
    font-size: 40px;
    line-height: 1.2;
  }
  .cert-solgan {
    color: #3670d3;
    font-size: 24px;
    line-height: 2;
    margin-bottom: 15px;
  }
  .cert-info {
    font-size: 20px;
    color: rgb(59, 49, 49);
    line-height: 1.6;
  }
  .pictures {
    width: 100%;
  }
  .bird {
    position: relative;
    width: 80px;
    left: 30px;
  }
  .ballon {
    margin-left: 60px;
    width: 80px;
  }
  .rocket {
    position: relative;
    width: 40px;
    left: 80%;
    top: -20px;
  }
  .logo {
    position: relative;
    width: 225px;
    left: 100px;
  }
  .whale {
    position: relative;
    top: 55px;
    left: -80px;
    width: 100px;
  }
  .animals {
    position: relative;
    top: 5px;
    left: 40px;
    width: 200px;
  }
  .dinosaurs {
    position: relative;
    width: 150px;
    left: -10px;
    top: -40px;
  }
}
</style>
