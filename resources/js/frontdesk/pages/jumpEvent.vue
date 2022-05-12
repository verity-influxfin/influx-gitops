<template>
  <div class="jump-event">
    <nav id="top">
      <div class="row no-gutters">
        <div class="col-auto p-1 p-sm-3">
          <img src="/images/logo.svg" alt="influx-logo" />
        </div>
        <div class="col"></div>
        <div class="top-method col-auto">
          <a href="#method" class="top-a">活動辦法</a>
        </div>
        <div class="top-bouns col-auto mr-2">
          <a href="#bouns" class="top-a">活動獎金</a>
        </div>
      </div>
    </nav>
    <div class="banner">
      <img
        class="img-fluid mx-auto pt-3 d-block"
        src="@/asset/images/jump/header-text.png"
        alt="普匯5週年"
      />
      <div class="banner-content">
        <div class="row no-gutters">
          <div class="col-auto banner-content-info">
            <div class="banner-content-info-title">線上投票活動</div>
            <div>上傳跳躍照片 祝普匯生日快樂</div>
            <div>最高獎金<span class="em">＄55,555</span>等你拿！</div>
          </div>
          <div class="col p-2">
            <div>
              目前：
              <span class="em">{{ indexCounter.memberCount || 0 }}</span>
              會員
            </div>
          </div>
        </div>
        <div class="banner-btns">
          <a href="#method">
            <button class="btn btn-join">
              立即參與
              <i class="fa fa-chevron-down"></i>
            </button>
          </a>
          <a href="#vote">
            <button class="btn btn-vote">
              立即投票
              <i class="fa fa-chevron-down"></i>
            </button>
          </a>
        </div>
        <img src="@/asset/images/index/img57.png" id="img-57" />
      </div>
    </div>
    <div class="intro">
      <div class="block-content">
        <div class="intro-content">
          <div>普匯5週年了！ 乘載眾人的希望與期盼</div>
          <div>提供最有溫度的服務，致力打造全新的金融生態模式</div>
          <div>在未來的日子，我們將持續 創新 精進</div>
          <div>普匯金融科技 邀你一起 跳躍世界</div>
        </div>
        <div class="intro-title">跳躍世界！世界由我們拼湊</div>
        <div class="intro-content-2">
          <div>在金融科技的領域</div>
          <div>每個你都是不可或缺的一部分</div>
          <div>攜手創造未來的普匯！</div>
        </div>
        <div class="intro-cover">
          <img
            class="img-fluid"
            src="@/asset/images/jump/intro-cover.png"
            alt="5周年慶"
          />
        </div>
        <div class="intro-join">
          <button class="btn btn-block btn-jump" @click="doJump">
            <div class="mx-auto">
              <span class="jump-icon">
                <img src="@/asset/images/jump/jump-person.svg" alt="" />
              </span>
              <span>我要跳躍</span>
            </div>
          </button>
        </div>
      </div>
    </div>
    <div id="method">
      <div class="block-content">
        <div class="method-title">活動時間</div>
        <div class="method-time">
          22.5.13 (五) 12：00 AM ~ 6.30 (四) 23：59 PM
        </div>
        <div class="method-title">活動結果公布</div>
        <div class="method-time">22.7.4 (一) 15：00 PM</div>
        <div class="method-title">如何參加？</div>
        <div class="method-step">
          <div class="method-step-item">
            <div class="icon">
              <img class="" src="@/asset/images/jump/Group47218.svg" alt="" />
            </div>
            <div>1. 點選「我要跳躍」</div>
          </div>
          <div class="method-step-item">
            <div class="icon">
              <img class="" src="@/asset/images/jump/icon_cert.svg" alt="" />
            </div>
            <div>2. 註冊/登入會員</div>
          </div>
          <div class="method-step-item">
            <div class="icon">
              <img class="" src="@/asset/images/jump/Group47198.svg" alt="" />
            </div>
            <div>3. 輸入暱稱&上傳照片</div>
          </div>
        </div>
        <div class="method-title">如何投票？</div>
        <div class="method-step">
          <div class="method-step-item">
            <div class="icon">
              <img class="" src="@/asset/images/jump/icon_cert.svg" />
            </div>
            <div>1. 註冊/登入會員</div>
          </div>
          <div class="method-step-item">
            <div class="icon">
              <img class="" src="@/asset/images/jump/Group47208.svg" alt="" />
            </div>
            <div>2. 選擇照片</div>
          </div>
          <div class="method-step-item">
            <div class="icon">
              <img src="@/asset/images/jump/seo-internet-like.svg" />
            </div>
            <div>3. 按讚</div>
          </div>
        </div>
        <div class="method-tip">
          <div>- 需註冊/登入普匯會員才能投票</div>
          <div>- 可重複投給一位參賽者</div>
          <div>- 每日一個會員最多送出3個讚</div>
        </div>
      </div>
    </div>
    <div id="vote">
      <div class="block-content">
        <div class="vote-title">跳躍作品集</div>
        <form class="search-input-group" @submit.prevent>
          <img class="loupe" src="../asset/images/loupe.svg" />
          <input class="search-input" type="text" />
        </form>
        <div class="works">
          <div class="work-item" v-for="item in workList" :key="item.id">
            <div class="work-item-title">{{ item.nick_name }}</div>
            <div class="work-item-img">
              <img class="img-fluid" :src="'/' + item.file_name" alt="" />
            </div>
            <div class="work-item-num">NO.{{ item.id }}</div>
            <div class="work-item-vote">
              <button class="btn p-0">
                <img
                  class="vote-img"
                  src="@/asset/images/jump/seo-internet-like.svg"
                />
              </button>
              <span class="vote-num">{{ item.votes }}票</span>
            </div>
          </div>
        </div>
        <div class="text-center">
          <button class="btn" @click="loadList">
            <img src="@/asset/images/jump/load-more.svg" />
          </button>
        </div>
      </div>
    </div>
    <div id="bouns">
      <div class="block-content">
        <div class="bouns-title">活動獎金</div>
        <div class="bouns-info">
          <div>
            第1名：
            <span class="em">＄55,555 </span>
            元
          </div>
          <div>
            第2名： <span class="em">＄5,000</span>元 &emsp;&emsp; 第3名：
            <span class="em">＄4,000</span>
            元
          </div>
          <div>
            第4名： <span class="em">＄3,000</span>元 &emsp;&emsp; 第5名：
            <span class="em">＄2,000</span>
            元
          </div>
          <div>人氣獎 (6~10名)：&emsp;&emsp; ＄1,500 元</div>
          <div class="mt-3">沒有名次嗎？創意和搞笑也可以！</div>
          <div>特別獎</div>
          <div>執行長青睞獎：＄1,000 元</div>
          <div>風格創意獎：＄1,000 元</div>
          <div>捧腹大笑獎：＄1,000 元</div>
          <div class="mt-3">
            凡參與本次活動(含參賽者及投票者)，皆獲抽獎資格！
          </div>
          <div>頭獎：Airpods 3代</div>
          <div>貳獎：1,000 元禮券*2份，共2,000元</div>
          <div>參獎：500 元禮券*3份，共1,500元</div>
        </div>
        <div class="bouns-title mt-3">活動詳情</div>
        <div class="bouns-info">
          <div>一、活動日期：</div>
          <div>2022/5/13(五)中午12:00整至2022/6/30(四)晚間23:59整，</div>
          <div>並於2022/7/4(一)下午15:00於官網公佈本次活動獲獎者。</div>
          <div class="mt-3">二、活動規範：</div>
          <div>
            拍攝主題為『跳躍』，上傳跳躍動作之直式4:3照片，加入慶生祝褔(ex：祝賀語、生日圖示、普匯特色等)，參賽者可自行加入創意與各式元素。(唯主辦單位保有審核參加資格之權利，若有不適當圖片主辦單位有權刪除其內容並取消參賽資格)
          </div>
          <div class="mt-3">三、參賽流程&投票辦法：</div>
          <div>1. 註冊官網會員。</div>
          <div>
            2.
            至活動頁面上傳照片，輸入參賽者暱稱，上傳成功出現於活動頁面時，當下即可開始進行投票。
          </div>
          <div>
            3. 每一會員每天有3票，不限同件作品。(投票者須先成為官方會員)
          </div>
          <div>
            4. 得獎者請於2022/7/15 (五) 前主動聯繫主辦單位，提供「我的使用者編號」及「照片電子檔」驗證獲獎資格，逾期視同放棄。
          </div>
          <div class="mt-3">四、主辦單位聯繫方式：</div>
          <div>聯絡電話 : (02)2507-9990</div>
          <div>電子信箱 : service@influxfin.com</div>
          <div>LINE官方客服 : @influxfin</div>
        </div>
        <div class="bouns-tip mt-3">
          <div>注意事項</div>
          <ul class="bouns-ul">
            <li>參賽者須詳閱活動辦法等並遵守其相關規範。</li>
            <li>每位參賽者投稿作品件數為一件，作品必須符合活動主題。</li>
            <li>
              主辦單位保有審核參加資格之權利，若有不適當圖片，主辦單位有權刪除其內容並取消獲選或參加資格，恕不另行通知。
            </li>
            <li>
              參加作品限本人創作，不得抄襲他人或妨害他人著作權及另重複參加投稿。參賽者如冒用或盜用第三人資料參賽、涉嫌抄襲、侵害他人智慧財產或涉及暴力、色情、譭謗、侵犯個資等違反善良風俗者，一經察覺，立即取消得獎資格，追回獎項、獎金，其衍生的民、刑事責任由參賽者自行負擔，與主辦單位無涉。
            </li>
            <li>
              參賽者須提供其創作作品作為日後公關報導與展示之用，並同意不收取任何費用。
            </li>
            <li>
              本次活動得獎者應主動聯繫主辦單位，依主辦單位之方式領取獎項，並依照中華民國所得稅法規定，獎金超過新台幣
              20,000元者，依法扣繳10%所得稅，如違反領獎之規定者，視同放棄獎項請求權。
            </li>
            <li>
              主辦單位保留活動辦法之異動權利，活動相關訊息以本網站公告內容為準，不另行通知。
            </li>
            <li>
              主辦單位保留修正、停止及更換等值獎品的權利，活動相關修正訊息以本網站公告內容為準，恕不另行個別通知，且若因故活動無法進行時，主辦單位有權決定取消、終止或暫停此活動。
            </li>
            <li>
              凡參與活動者即同意接受活動辦法與注意事項內容，如有不符活動規定或違反本活動之事項，主辦單位得以取消其資格，並對於破壞本活動之行為保留法律追訴權。
            </li>
            <li>若有任何未盡事宜，由主辦單位解釋。</li>
          </ul>
          <div class="text-center">
            Copyright ©2020 普匯金融科技股份有限公司 All rights reserved.
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="uploadModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div class="row no-gutters">
              <div class="col-auto"></div>
              <div class="modal-title col">上傳說明</div>
              <div class="col-auto">
                <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <div class="row no-gutters modal-info">
              <div class="col">
                <div>範例圖&格式說明</div>
                <div>1.4:3直式照片</div>
                <div>2.各式跳躍動作</div>
                <div>3.在照片上寫下想對普匯說的話</div>
                <div>(ex : 普匯五歲生日快樂 !、一支手機貸你完成夢想)</div>
              </div>
              <div class="col-auto">
                <img
                  class="img-fluid"
                  src="@/asset/images/jump/upload-example.png"
                  alt=""
                />
              </div>
            </div>
            <form @submit.prevent="doUpload">
              <div class="row no-gutters">
                <input
                  type="text"
                  class="modal-input w-100"
                  placeholder="*請輸入您的暱稱："
                  v-model="nickNameInput"
                  required
                />
              </div>
              <div
                class="row no-gutters modal-upload-file"
                @drop="drop"
                @dragover.prevent
              >
                <img
                  src="@/asset/images/jump/remove-file.png"
                  class="remove-file"
                  @click="removeFile"
                />
                <img
                  @click="$refs.fileUpload.click()"
                  src="@/asset/images/jump/upload-icon.png"
                  class="upload-icon img-fluid"
                />
                <div v-if="fileName" class="file-name">
                  已上傳檔案：{{ fileName }}
                </div>
              </div>
              <input
                type="file"
                ref="fileUpload"
                @change="onUpload(e)"
                hidden
              />
              <button type="submit" class="btn btn-block btn-upload-submit">
                確認上傳「我的跳躍」
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { alesisIndexCounter } from './api'
import { gsap } from 'gsap'
import Axios from 'axios'
export default {
  data() {
    return {
      indexCounter: {},
      nickNameInput: '',
      flag: '',
      fileName: '',
      file: new File([], ''),
      workList: [],
      currentPage: 1,
      maxPage: 1
    }
  },
  mounted() {
    gsap.from('#img-57', {
      x: 500,
      duration: 2
    })
    this.loadList()
    alesisIndexCounter().then(v => {
      this.indexCounter = v
    })
    this.flag = sessionStorage.getItem('flag') ? sessionStorage.getItem('flag') : '';
  },
  methods: {
    onUpload() {
      this.fileName = ''
      const currentFiles = this.$refs.fileUpload.files
      if (currentFiles.length === 0) {
        return
      } else {
        for (const file of currentFiles) {
          this.file = file
          this.fileName = file.name
        }
      }
    },
    drop(event) {
      event.preventDefault();
      this.$refs.fileUpload.files = event.dataTransfer.files
      this.onUpload()
    },
    removeFile() {
      this.file = new File([], '')
      this.fileName = ''
    },
    doJump() {
      if (this.flag === 'login') {
        $('#uploadModal').modal('show')
      } else {
        this.$store.commit('mutation5thLogin')
      }
    },
    async doUpload() {
      if (!this.fileName) {
        alert('請上傳檔案')
      } else {
        if (!confirm('確定上傳此作品嗎？')) {
          return
        }
        const formData = new FormData()
        formData.append('file', this.file)
        formData.append('nick_name', this.nickNameInput)
        const { data } = await Axios({
          method: 'POST',
          url: '/api/v1/campaign2022/upload',
          data: formData,
          mimeType: 'multipart/form-data',
        })
        if (data.success) {
          alert('上傳成功')
          location.reload()
        } else {
          alert('上傳失敗，請稍後再試')
        }
      }
    },
    loadList() {
      if (this.currentPage > this.maxPage) {
        return
      }
      Axios.get('/api/v1/campaign2022/list/page/' + this.currentPage).then(({ data }) => {
        if (data.success) {
          this.workList = [...this.workList, ...data.data.list]
          this.maxPage = Math.ceil(data.data.total / 3)
          this.currentPage = this.currentPage + 1
        }
      })
    }
  },
}
</script>

<style lang="scss" scoped>
.block-content {
  margin: 0 20px;
}
.jump-event {
  max-width: 475px;
  margin: auto;
  padding-top: 5px;
  #top {
    ul {
      list-style: none;
    }
    .top {
      &-method {
        color: #ffffff;
        transform: skewX(15deg);
        background: #3ba8f3;
        display: flex;
      }
      &-bouns {
        transform: skewX(15deg);
        background: #036eb7;
        display: flex;
      }
    }
    .top-a {
      font-size: 14px;
      padding: 9px 25px;
      color: #ffffff;
    }
  }
  .banner {
    position: relative;
    background-image: url('~images/jump/header.png');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: bottom;
    height: 415px;
    &-content {
      margin-top: 13px;
      position: relative;
      padding-left: 27px;
      font-style: normal;
      font-weight: 700;
      font-size: 14px;
      line-height: 17px;
      color: #ffffff;
      &-info {
        padding: 8px 5px;
        border: 1px solid #ffffff;
        border-radius: 6px;
        font-style: normal;
        font-weight: 500;
        font-size: 12px;
        line-height: 1.3;
        color: #ffffff;
        &-title {
          font-weight: 700;
          font-size: 14px;
          line-height: 20px;
          color: #80e8ff;
          text-align: center;
          margin-bottom: 6px;
        }
      }
      & .em {
        font-size: 16px;
        color: #fff100;
      }
      & .sm {
        font-size: 12px;
      }
      & .btn-join {
        position: relative;
        left: 0px;
        bottom: -50px;
        height: 90px;
        width: 90px;
        border-radius: 50%;
        font-style: normal;
        font-weight: 700;
        font-size: 14px;
        color: #fff;
        background: #083a6e;
        box-shadow: 4px 4px 2px rgba(0, 0, 0, 0.1);
      }
      & .btn-vote {
        position: relative;
        margin-top: 15px;
        left: 15px;
        height: 90px;
        width: 90px;
        border-radius: 50%;
        font-style: normal;
        font-weight: 700;
        font-size: 14px;
        color: #fff;
        background: #ffc535;
        box-shadow: 4px 4px 2px rgba(0, 0, 0, 0.1);
      }
      & #img-57 {
        position: absolute;
        height: 267px;
        right: -15px;
        bottom: -100px;
        transform: rotate(-10deg);
      }
    }
  }
  .intro {
    padding-bottom: 36px;
    &-title {
      margin: 36px 0 15px;
      font-style: normal;
      font-weight: 700;
      font-size: 22px;
      line-height: 32px;
      text-align: center;
      color: #036eb7;
    }
    &-content {
      font-style: normal;
      font-weight: 400;
      font-size: 14px;
      line-height: 1.6;
      text-align: center;
      color: #707070;
      &-2 {
        font-style: normal;
        font-weight: 500;
        font-size: 14px;
        line-height: 20px;
        text-align: center;
        color: #393939;
      }
    }
    &-cover {
      margin: 20px auto 15px;
    }
    .btn-jump {
      background: #036eb7;
      box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.2);
      border-radius: 6px;
      color: #fff;
      font-style: normal;
      font-weight: 700;
      font-size: 18px;
      line-height: 26px;
      .jump-icon {
        margin-right: 8px;
        height: 26px;
      }
    }
  }
  #method {
    padding: 24px 0;
    background: #036eb7;
    .method {
      &-title {
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 2;
        color: #ffffff;
      }
      &-time {
        font-style: normal;
        font-weight: 500;
        font-size: 16px;
        line-height: 1.6;
        color: #fff100;
        margin-bottom: 12px;
      }
      &-step {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        &-item {
          font-style: normal;
          font-weight: 500;
          font-size: 12px;
          line-height: 14px;
          text-align: center;
          color: #ffffff;
          min-width: 90px;
          .icon {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 50px;
            margin-bottom: 10px;
          }
        }
      }
      &-tip {
        font-style: normal;
        font-weight: 500;
        font-size: 12px;
        line-height: 1.6;
        color: #ffffff;
        opacity: 0.5;
      }
    }
  }
  #vote {
    padding-top: 36px;
    .vote-title {
      font-style: normal;
      font-weight: 700;
      font-size: 22px;
      line-height: 32px;
      text-align: center;
      color: #036eb7;
    }
    .search-input-group {
      position: relative;
      margin: 15px auto;
      width: 220px;
      .search-input {
        position: relative;
        width: 100%;
        padding: 3px 20px 3px 40px;
        border-radius: 20px;
        border: solid 1px #036eb7;
        background-color: #fff;
        font-size: 16px;
        font-style: normal;
        line-height: 1.6;
        color: #4d4d4d;
      }
      .loupe {
        z-index: 2;
        position: absolute;
        left: 10px;
        top: 7px;
      }
    }
    .works {
      padding: 15px 0;
      display: grid;
      grid-template-columns: 1fr 1fr;
      grid-template-areas:
        'champion champion'
        '. .';
      gap: 20px;
      grid-auto-rows: auto;
    }
    .work-item {
      &:nth-child(1) {
        &::before {
          content: '';
          background-image: url('~images/jump/crown.png');
          left: -49px;
          top: -49px;
          width: 65px;
          height: 65px;
          display: block;
          position: absolute;
        }
        position: relative;
        grid-area: champion;
        background: #ffc535;

        margin: auto;
      }
      max-width: 200px;
      background: #b4e4ff;
      box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.2);
      border-radius: 6px;
      padding: 12px 20px;
      &-title {
        text-align: center;
        font-style: normal;
        font-weight: 500;
        font-size: 16px;
        line-height: 1.6;
        color: #083a6e;
      }
      &-img {
        margin: 5px 0;
        border-radius: 10px;
        width: 120px;
      }
      &-num {
        font-weight: 500;
        font-size: 12px;
        line-height: 1.4;
        color: #393939;
      }
      &-vote {
        margin-top: 8px;
        text-align: center;
        font-style: normal;
        font-weight: 700;
        font-size: 14px;
        line-height: 1.4;
        color: #083a6e;
      }
    }
  }
  #bouns {
    &::before {
      content: '';
      background-image: url('~images/jump/bg-wave.svg');
      background-repeat: no-repeat;
      background-position: top;
      background-size: contain;
      height: 150px;
      width: 100%;
      position: absolute;
      top: -70px;
      z-index: -1;
    }
    position: relative;
    background-color: #036eb7;
    margin-top: 100px;
    padding-bottom: 30px;
    .bouns {
      &-title {
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 20px;
        text-align: center;
        color: #ffffff;
        margin-bottom: 20px;
      }
      &-info {
        font-style: normal;
        font-weight: 500;
        font-size: 12px;
        line-height: 1.6;
        color: #ffffff;
        .em {
          font-size: 14px;
          color: #fff100;
        }
      }
      &-tip {
        font-style: normal;
        font-weight: 300;
        font-size: 12px;
        line-height: 16px;
        color: #ffffff;
        opacity: 0.75;
      }
      &-ul {
        padding-inline-start: 20px;
      }
    }
  }
}
#uploadModal {
  .modal-title {
    font-weight: 700;
    font-size: 20px;
    line-height: 29px;
    text-align: center;
    color: #036eb7;
    margin-bottom: 14px;
  }
  .modal-info {
    font-style: normal;
    font-weight: 400;
    font-size: 12px;
    line-height: 1.3;
    letter-spacing: -0.02em;
    color: #000000;
  }
  .modal-input {
    margin-top: 14px;
    padding: 8px 16px;
    border-radius: 10px;
    border: 1px solid #383838;
    font-style: normal;
    font-weight: 400;
    font-size: 12px;
    line-height: 1.3;
    letter-spacing: -0.02em;
    color: #000000;
  }
  .modal-upload-file {
    position: relative;
    margin-top: 12px;
    border: 1px dashed #036eb7;
    border-radius: 20px;
    padding: 30px;
    font-size: 14px;
    .remove-file {
      position: absolute;
      right: 10px;
      top: 10px;
    }
    .upload-icon {
      margin: 0 auto;
    }
    .file-name {
      position: absolute;
      bottom: 15px;
      left: 0;
      right: 0;
      text-align: center;
    }
  }
  .btn-upload-submit {
    margin-top: 15px;
    background: #036eb7;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    border-radius: 6px;
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 26px;
    letter-spacing: -0.02em;
    color: #ffffff;
    padding: 12px;
    text-align: center;
  }
}
@media (min-width: 700px) {
  .block-content {
    max-width: 1400px;
    width: 100%;
    margin: 0 auto;
  }
  .jump-event {
    max-width: initial;
    margin: auto;
    padding-top: 5px;
    #top {
      max-width: 1400px;
      width: 100%;
      margin: 0 auto;
      .top-a {
        font-size: 24px;
        padding: 9px 25px;
        color: #ffffff;
      }
    }
    .banner {
      position: relative;
      background-image: url('~images/jump/header.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: top center;
      height: 415px;
      &-content {
        margin-top: 13px;
        position: relative;
        padding-left: 20px;
        font-style: normal;
        font-weight: 700;
        font-size: 14px;
        line-height: 17px;
        color: #ffffff;
        & .em {
          font-size: 16px;
          color: #fff100;
        }
        & .sm {
          font-size: 12px;
        }
        & .btn-join {
          position: relative;
          left: -25px;
          bottom: -50px;
          height: 90px;
          width: 90px;
          border-radius: 50%;
          font-style: normal;
          font-weight: 700;
          font-size: 14px;
          color: #fff;
          background: #083a6e;
          box-shadow: 4px 4px 2px rgba(0, 0, 0, 0.1);
        }
        & .btn-vote {
          position: relative;
          margin-top: 15px;
          left: -10px;
          height: 90px;
          width: 90px;
          border-radius: 50%;
          font-style: normal;
          font-weight: 700;
          font-size: 14px;
          color: #fff;
          background: #ffc535;
          box-shadow: 4px 4px 2px rgba(0, 0, 0, 0.1);
        }
        & #img-57 {
          position: absolute;
          height: 267px;
          right: -15px;
          bottom: -100px;
          transform: rotate(-10deg);
        }
      }
    }
    .intro {
      padding-bottom: 36px;
      &-title {
        margin: 36px 0 15px;
        font-style: normal;
        font-weight: 700;
        font-size: 22px;
        line-height: 32px;
        text-align: center;
        color: #036eb7;
      }
      &-content {
        font-style: normal;
        font-weight: 400;
        font-size: 14px;
        line-height: 1.6;
        text-align: center;
        color: #707070;
        &-2 {
          font-style: normal;
          font-weight: 500;
          font-size: 14px;
          line-height: 20px;
          text-align: center;
          color: #393939;
        }
      }
      &-cover {
        margin: 20px auto 15px;
      }
      .btn-jump {
        background: #036eb7;
        box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.2);
        border-radius: 6px;
        color: #fff;
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        line-height: 26px;
        .jump-icon {
          margin-right: 8px;
          height: 26px;
        }
      }
    }
    #method {
      padding: 24px 0;
      background: #036eb7;
      .method {
        &-title {
          font-style: normal;
          font-weight: 700;
          font-size: 16px;
          line-height: 2;
          color: #ffffff;
        }
        &-time {
          font-style: normal;
          font-weight: 500;
          font-size: 16px;
          line-height: 1.6;
          color: #fff100;
          margin-bottom: 12px;
        }
        &-step {
          display: flex;
          justify-content: space-between;
          margin-bottom: 10px;
          &-item {
            font-style: normal;
            font-weight: 500;
            font-size: 12px;
            line-height: 14px;
            text-align: center;
            color: #ffffff;
            min-width: 90px;
            .icon {
              display: flex;
              align-items: center;
              justify-content: center;
              height: 50px;
              margin-bottom: 10px;
            }
          }
        }
        &-tip {
          font-style: normal;
          font-weight: 500;
          font-size: 12px;
          line-height: 1.6;
          color: #ffffff;
          opacity: 0.5;
        }
      }
    }
    #vote {
      padding-top: 36px;
      .vote-title {
        font-style: normal;
        font-weight: 700;
        font-size: 22px;
        line-height: 32px;
        text-align: center;
        color: #036eb7;
      }
      .search-input-group {
        position: relative;
        margin: 15px auto;
        width: 220px;
        .search-input {
          position: relative;
          width: 100%;
          padding: 3px 20px 3px 40px;
          border-radius: 20px;
          border: solid 1px #036eb7;
          background-color: #fff;
          font-size: 16px;
          font-style: normal;
          line-height: 1.6;
          color: #4d4d4d;
        }
        .loupe {
          z-index: 2;
          position: absolute;
          left: 10px;
          top: 7px;
        }
      }
      .works {
        padding: 15px 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-areas:
          'champion champion'
          '. .';
        gap: 20px;
        grid-auto-rows: auto;
      }
      .work-item {
        &:nth-child(1) {
          &::before {
            content: '';
            background-image: url('~images/jump/crown.png');
            left: -49px;
            top: -49px;
            width: 65px;
            height: 65px;
            display: block;
            position: absolute;
          }
          position: relative;
          grid-area: champion;
          background: #ffc535;

          margin: auto;
        }
        max-width: 200px;
        background: #b4e4ff;
        box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.2);
        border-radius: 6px;
        padding: 12px 20px;
        &-title {
          text-align: center;
          font-style: normal;
          font-weight: 500;
          font-size: 16px;
          line-height: 1.6;
          color: #083a6e;
        }
        &-img {
          margin: 5px 0;
          border-radius: 10px;
          width: 120px;
        }
        &-num {
          font-weight: 500;
          font-size: 12px;
          line-height: 1.4;
          color: #393939;
        }
        &-vote {
          margin-top: 8px;
          text-align: center;
          font-style: normal;
          font-weight: 700;
          font-size: 14px;
          line-height: 1.4;
          color: #083a6e;
        }
      }
    }
    #bouns {
      &::before {
        content: '';
        background-image: url('~images/jump/bg-wave.svg');
        background-repeat: no-repeat;
        background-position: top;
        background-size: contain;
        height: 150px;
        width: 100%;
        position: absolute;
        top: -70px;
        z-index: -1;
      }
      position: relative;
      background-color: #036eb7;
      margin-top: 100px;
      padding-bottom: 30px;
      .bouns {
        &-title {
          font-style: normal;
          font-weight: 700;
          font-size: 16px;
          line-height: 20px;
          text-align: center;
          color: #ffffff;
          margin-bottom: 20px;
        }
        &-info {
          font-style: normal;
          font-weight: 500;
          font-size: 12px;
          line-height: 1.6;
          color: #ffffff;
          .em {
            font-size: 14px;
            color: #fff100;
          }
        }
        &-tip {
          font-style: normal;
          font-weight: 300;
          font-size: 12px;
          line-height: 16px;
          color: #ffffff;
          opacity: 0.75;
        }
        &-ul {
          padding-inline-start: 20px;
        }
      }
    }
  }
}
</style>
