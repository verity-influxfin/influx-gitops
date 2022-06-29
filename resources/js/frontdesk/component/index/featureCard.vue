<template>
  <div class="card" data-aos="fade-up">
    <div class="card-body">
      <div class="card-cover">
        <img class="img-fluid mx-auto" :src="icon" />
      </div>
      <div class="card-title">{{ title }}</div>
      <div class="card-divider"></div>
      <slot>
        <div class="card-text">
            {{ info }}
        </div>
      </slot>

    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: ''
    },
    icon: {
      type: String,
      default: ''
    },
    info: {
      type: String,
      default: ''
    }
  },
}
</script>

<style lang="scss" scoped>
.card {
  width: 330px;
  border: none;
  box-shadow: 0px 4px 12px rgba(21, 91, 153, 0.16);
  border-radius: 16px;
  .card-body {
    padding: 24px;
  }
  .card-cover {
    display: flex;
    width: 84px;
    margin: 0 auto 16px;
    padding: 12px;
    background: #cdeafe;
    border-radius: 6px;
  }
  .card-title {
    font-style: normal;
    font-weight: 700;
    font-size: 28px;
    text-align: center;
    color: #023d64;
  }
  .card-divider {
    width: 39px;
    margin: 8px auto;
    border-bottom: 3px solid #036fb7;
    border-radius: 100px;
  }
  .card-text {
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 1.2;
    color: #023d64;
    opacity: 0.56;
  }
}
@media screen and (max-width: 768px) {
  .card {
    width: 100%;
    border: none;
    .card-body {
      padding: 22px;
    }
    .card-cover {
      width: 68px;
      margin: 0 auto 12px;
      padding: 8px;
    }
    .card-title {
      font-size: 18px;
    }
    .card-text {
      font-size: 14px;
    }
  }
}
</style>
