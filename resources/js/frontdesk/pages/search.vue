<template>
  <div class="search">
    <div class="pwd">
      <img class="home" src="../asset/images/home.svg" alt="" />
      <span class="right">&#10217;</span>
      <div class="path">普匯金融科技-首頁</div>
      <span class="right">&#10217;</span>
      <div class="path">全站搜尋</div>
    </div>
    <div class="title">
      <img class="title-img" src="../asset/images/all-search.svg" alt="" />
    </div>
    <div class="search-block">
      <div class="text">關鍵字</div>
      <span class="loupe">
        <input
          class="search-input"
          placeholder="搜尋..."
          type="text"
          v-model="searchInput"
        />
      </span>

      <div class="text">
        共有
        <span class="num">36</span>
        筆搜尋結果
      </div>
    </div>
    <div class="sub-pages-header">
      <div class="item" :class="{ active: item === 1 }" @click="item = 1">
        頁面內容
        <div class="rwd" :class="{ active: item === 1 }">-</div>
      </div>
      <!-- rwd  content-->
      <div class="rwd content" :class="{ active: item === 1 }">
        <div class="no-found" v-show="!isFind">
          <img class="no-found-img" src="../asset/images/no-found.png" alt="" />
          <div class="no-found-text">
            找不到符合搜尋字詞「<span class="text-red">AAA</span>」
          </div>
          <div class="no-found-text">
            麻煩您在輸入一次
          </div>
        </div>
        <div v-show="isFind">
          <div class="content-item" v-for="i in 8" :key="i">
            <div class="item-title">我是文字我是文字我是文字</div>
            <div class="item-text">
              {{ i }} 我是文字我是文字我是文字
              <span class="text-red">AAA</span>
              我是文字我是文字我是文字
            </div>
          </div>
        </div>

        <ul class="pagination">
          <li class="page-item"><a class="page-link" href="#">前一頁</a></li>
          <li class="page-item" v-for="i in 5" :key="i">
            <a class="page-link" href="#">{{ i }}</a>
          </li>
          <li class="page-item"><a class="page-link" href="#">後一頁</a></li>
        </ul>
      </div>
      <div class="item" :class="{ active: item === 2 }" @click="item = 2">
        小學堂
        <div class="rwd" :class="{ active: item === 2 }">-</div>
      </div>
      <div class="item" :class="{ active: item === 3 }" @click="item = 3">
        常見問題
        <div class="rwd" :class="{ active: item === 3 }">-</div>
      </div>
    </div>
    <div class="content m-0">
      <div class="no-found" v-show="!isFind">
        <img class="no-found-img" src="../asset/images/no-found.png" alt="" />
        <div class="no-found-text">
          找不到符合搜尋字詞「<span class="text-red">AAA</span>」
        </div>
        <div class="no-found-text">
          麻煩您在輸入一次
        </div>
      </div>
      <div v-show="isFind">
        <div class="content-item" v-for="i in 8" :key="i">
          <div class="item-title">我是文字我是文字我是文字</div>
          <div class="item-text">
            {{ i }} 我是文字我是文字我是文字
            <span class="text-red">AAA</span>
            我是文字我是文字我是文字
          </div>
        </div>
      </div>

      <ul class="pagination">
        <li class="page-item"><a class="page-link" href="#">前一頁</a></li>
        <li class="page-item" v-for="i in 5" :key="i">
          <a class="page-link" href="#">{{ i }}</a>
        </li>
        <li class="page-item"><a class="page-link" href="#">後一頁</a></li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      item: 1,
      isFind: true,
      searchInput: '',
    }
  },
  created () {
    console.log(this.$route)
    this.searchInput = this.$route.query.searchText ?? ''

  }
}
</script>

<style lang="scss" scoped>
.search {
  width: 900px;
  margin: auto;
  padding: 0 10px;
  .pwd {
    display: flex;
    align-items: center;
    margin-left: 34px;
    margin-top: 80px;
    .home {
      height: 24px;
      margin-right: 6px;
    }
    .right {
      margin: 0 8px;
      font-family: NotoSansTC;
      font-size: 16px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 2;
      letter-spacing: 0.8px;
      text-align: center;
      color: #4d4d4d;
    }
    .path {
      font-family: NotoSansTC;
      font-size: 16px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 2;
      letter-spacing: 0.8px;
      text-align: center;
      color: #4d4d4d;
    }
  }
  .title {
    margin: 33px 34px;
  }
  .search-block {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 34px;
    border-radius: 6px;
    background-color: #efefef;
    .text {
      font-family: NotoSansTC;
      font-size: 24px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.67;
      letter-spacing: 1.2px;
      text-align: left;
      color: #5d5555;
      .num {
        font-family: NotoSansTC;
        font-size: 28px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.71;
        letter-spacing: 1.4px;
        text-align: center;
        color: #4873a2;
      }
    }
    .search-input {
      position: relative;
      width: 450px;
      padding: 7px 34px;
      border-radius: 6px;
      border: solid 1px #036eb7;
      background-color: #fff;
      font-size: 16px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.95;
      letter-spacing: 0.8px;
      text-align: left;
      color: #4d4d4d;
    }
    .loupe {
      position: relative;
      &::after {
        position: absolute;
        top: 11.5px;
        right: 34px;
        content: "";
        width: 22px;
        height: 22px;
        display: block;
        background-image: url("../asset/images/loupe.svg");
      }
    }
  }
  .sub-pages-header {
    margin: 12px 0;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: 1fr;
    gap: 0px 0px;
    grid-template-areas: ". . .";
    .item {
      font-family: NotoSansTC;
      font-size: 24px;
      font-weight: 500;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.3;
      letter-spacing: 1.2px;
      color: #aaa;
      text-align: center;
      cursor: pointer;
      padding: 24px 0;
      border-bottom: 1px solid #aaa;
      &.active {
        color: #326398;
        position: relative;
        &::after {
          content: "";
          border-bottom: 1px solid #326398;
          position: absolute;
          width: 100%;
          left: 0;
          bottom: 0;
        }
      }
      .rwd {
        display: none;
      }
    }
  }
  .content {
    &.rwd {
      display: none;
    }
    display: flex;
    flex-direction: column;
    .content-item {
      padding-bottom: 40px;
      .item-title {
        font-family: NotoSansTC;
        font-size: 24px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.67;
        letter-spacing: 1.2px;
        text-align: left;
        color: #5d5555;
      }
      .item-text {
        font-family: NotoSansTC;
        font-size: 16px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.67;
        letter-spacing: 0.8px;
        text-align: left;
        color: #4d4d4d;
      }
      .text-red {
        color: #f00;
      }
    }
    .no-found {
      margin: 90px 0;
      .no-found-img {
        display: block;
        margin: auto;
      }
      .no-found-text {
        font-family: NotoSansTC;
        font-size: 24px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.67;
        letter-spacing: 1.2px;
        text-align: center;
        color: #5d5555;
        .text-red {
          color: #f00;
        }
      }
    }
  }
  .pagination {
    justify-content: center;
    padding-bottom: 80px;
  }
}
@media screen and (max-width: 767px) {
  .search {
    max-width: 100%;
    padding: 10px;
    .pwd {
      display: none;
    }
    .title {
      margin: 40px 0 20px;
    }
    .search-block {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      padding: 6px 12px;
      .text {
        font-family: NotoSansTC;
        font-size: 18px;
        .num {
          font-family: NotoSansTC;
          font-size: 22px;
        }
      }
      .search-input {
        position: relative;
        width: 250px;
        padding: 4px 12px;
      }
      .loupe {
        position: relative;
        margin-bottom: 8px;
        &::after {
          position: absolute;
          top: 9px;
          right: 12px;
          content: "";
          width: 22px;
          height: 22px;
          display: block;
          background-image: url("../asset/images/loupe.svg");
        }
      }
    }
    .sub-pages-header {
      margin: 12px 0;
      display: flex;
      flex-direction: column;
      .item {
        display: flex;
        justify-content: space-between;
        text-align: left;
        font-size: 20px;
        padding: 14px 12px;
        &::after {
          content: "+";
        }
        .rwd.active {
          display: block;
        }
      }
    }
    .content {
      display: none;
    }
    .content.active {
      display: flex;
      .no-found {
        margin: 60px 0;
        .no-found-img {
          width: 150px;
        }
        .no-found-text {
          font-size: 16px;
          .text-red {
            color: #f00;
          }
        }
      }
      .content-item {
        padding: 0 12px 25px;
        .item-title {
          font-size: 20px;
        }
        .item-text {
          font-size: 14px;
        }
        .text-red {
          color: #f00;
        }
      }
    }
    .pagination {
      justify-content: center;
      padding-bottom: 40px;
      border-radius: 0;
      border-bottom: 1px solid #aaa;
    }
  }
}
</style>