<template>
  <div>
    <!-- phone -->
    <div class="d-sm-none d-flex no-gutters flex-wrap">
      <div class="col-12">
        <div class="text-bg">
          <img
            src="@/asset/images/index/header/collegeloan-header-text-phone.png"
            class="mx-auto d-block img-fluid"
            alt="學生貸"
          />
        </div>
      </div>
      <div class="col-12 position-releative">
        <img
          src="@/asset/images/index/header/collegeloan-header-people-phone.png"
          class="people-bg"
        />
      </div>
    </div>
    <!-- web -->
    <div class="d-sm-flex d-none no-gutters justify-content-lg-between">
      <div class="col-auto pl-2">
        <div class="text-bg">
          <img
            src="@/asset/images/index/header/collegeloan-header-text.png"
            alt="學生貸"
            style="width: 530px"
          />
        </div>
        <div class="links">
          <a href="/collegeLoan">
            <button class="btn btn-join">
              了解更多 <i class="fa fa-chevron-right"></i>
            </button>
          </a>
        </div>
      </div>
      <div class="col-auto position-relative">
        <img
          src="@/asset/images/index/header/collegeloan-header-people.png"
          class="people-bg"
        />
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.text-bg {
  height: 325px;
  padding-top: 50px;
}
.btn-join {
  border-radius: 12px;
  padding: 10px;
  color: #fff;
  background: #083a6e;
  box-shadow: 4px 4px 2px rgba(0, 0, 0, 0.1);
  width: 250px;
  font-size: 28px;
}
.people-bg {
  top: -35px;
  position: relative;
  padding-right: 75px;
}
@media screen and (max-width: 576px) {
  .text-bg {
    padding-top: 25px;
    height: 100px;
  }
  .people-bg {
    padding-right: 0;
    display: block;
    margin: 65px auto 0;
  }
}
</style>
