<template>
  <div class="content-bg">
    <div class="cardgame-wrapper" v-if="page === 'game'">
      <div class="cardgame-title">
        <img
          src="@/asset/images/2023_new_year/game-title.png"
          alt="揚眉兔氣迎新春！"
          class="img-fluid d-block mx-auto"
        />
      </div>
      <div class="cardgame-sub" style="font-size: 18px">
        「2022網路流行梗語」你跟上了嗎？
      </div>
      <div class="cardgame-sub">普匯時事戳戳樂</div>
      <div class="cards">
        <div class="countdown"><span>15</span></div>
        <template>
          <span
            :class="'card' + (index % 2 === 0 ? 'B' : 'A')"
            v-for="(d, index) in randkeys"
            :key="index"
          >
            <span class="cardFlip card-front" @click.once="timer()">
              <img class="cardFace img-1" :src="cardBase[index % 2]" />
              <img
                src="@/asset/images/2023_new_year/card-finished.png"
                class="cardFace img-2"
              />
            </span>
            <span class="cardFlip card-back">
              <span class="cardQuestion" :data-id="d">
                <span v-html="imgs[d].question"></span><br /><br />
                <div
                  class="cardAns"
                  @click.once="
                    (e) => {
                      ans(e)
                      stopTime()
                    }
                  "
                  data-ans="0"
                >
                  (A){{ imgs[d].selection[0] }}
                </div>
                <div
                  class="cardAns"
                  @click.once="
                    (e) => {
                      ans(e)
                      stopTime()
                    }
                  "
                  data-ans="1"
                >
                  (B){{ imgs[d].selection[1] }}
                </div>
                <div
                  class="cardAns"
                  @click.once="
                    (e) => {
                      ans(e)
                      stopTime()
                    }
                  "
                  data-ans="2"
                >
                  (C){{ imgs[d].selection[2] }}
                </div>
              </span>
            </span>
          </span>
        </template>
      </div>
      <div class="cardgame-prize">挑戰答題 即有機會獲得全家點數！</div>
      <div class="modal" id="falseModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <div class="modal-content-title">
                <div>不小心透露年紀了嗎？</div>
                <div>你離年輕人有點遠喔！</div>
              </div>
              <div class="my-4 text-center">
                <img
                  src="@/asset/images/2023_new_year/false-newyear-img.jpg"
                  class="img-fluid"
                />
              </div>
              <div class="text-center">
                <button
                  class="btn btn-next mx-auto"
                  onclick="location.reload()"
                >
                  再來一次
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal" id="nextModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <div class="modal-content-title">
                <div class="mb-2">恭喜完成！</div>
                <div>你是貨真價實的年輕人</div>
              </div>
              <div class="my-4 text-center">
                <img
                  src="@/asset/images/2023_new_year/next-newyear-img.png"
                  class="img-fluid"
                />
              </div>
              <div
                class="
                  d-flex
                  flex-column
                  align-items-center
                  justify-content-center
                "
              >
                <div>
                  <button class="btn btn-next" @click="goPage('login')">
                    登入領取刮刮樂
                  </button>
                  <div>(最高全家點數 100 點)</div>
                </div>
                <button class="btn btn-next mt-3" @click="goPage('register')">
                  註冊領取 50 點
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else-if="page === 'register'" class="member-wrapper register">
      <div class="page-title">
        <img
          src="@/asset/images/2023_new_year/game-title.png"
          alt="揚眉兔氣迎新春！"
          class="img-fluid d-block mx-auto"
        />
        <div class="my-4">完成註冊即可獲得 全家點數 50 點！</div>
        <img
          src="@/asset/images/2023_new_year/new-year-logo.png"
          class="img-fluid d-block mx-auto"
        />
      </div>
      <form class="group-wrapper" @submit.prevent="newyearRegister">
        <div class="input-group">
          <span class="input-group-title">領取贈品</span>
          <div class="form-control label-input">已全數兌換完畢</div>
        </div>
        <div class="input-group">
          <span class="input-group-title">會員註冊</span>
          <input
            type="text"
            class="form-control label-input"
            placeholder="請輸入手機號碼"
            v-model="phone"
            maxlength="10"
            required
          />
        </div>
        <div class="input-group">
          <input
            type="password"
            class="form-control label-input"
            placeholder="請輸入密碼"
            v-model="password"
            required
          />
        </div>
        <div class="input-group">
          <input
            type="password"
            class="form-control label-input"
            placeholder="請再次輸入密碼"
            v-model="confirmPassword"
            required
          />
        </div>
        <div class="input-group">
          <div class="captcha-row" style="display: flex">
            <input
              type="text"
              class="form-control label-input"
              placeholder="請輸入驗證碼"
              v-model="code"
              maxlength="6"
              required
            />
            <button
              class="btn btn-captcha"
              type="button"
              @click="getCaptcha('registerphone')"
              v-if="!isSended"
            >
              取得驗證碼
            </button>
            <div class="btn btn-disable" v-if="isSended">
              {{ counter }}S有效
            </div>
            <span class="tip" v-if="isSended">驗證碼已寄出</span>
          </div>
        </div>
        <div class="input-group">
          <input
            type="email"
            class="form-control label-input"
            placeholder="請輸入email"
            v-model="email"
            required
          />
          <span
            class="message position-absolute"
            style="top: 25px; width: 100%; text-align: center"
            >*請填寫正確信箱，獎項才能順利送達給你喔</span
          >
        </div>
        <div class="input-group">
          <div class="d-flex" style="margin: 15px auto">
            <input
              type="checkbox"
              @click="isAgree = !isAgree"
              :checked="isAgree"
              class="mr-2"
            />
            <div class="input-group-title">我同意平台相關條款之規定</div>
          </div>
        </div>
        <div class="input-group justify-content-center">
          <button class="btn btn-next">送出</button>
          <button
            type="button"
            class="btn btn-next ml-2"
            @click="page = 'login'"
          >
            我要登入
          </button>
        </div>
        <div class="message mt-0" v-if="message">{{ message }}</div>
      </form>
    </div>
    <div v-else-if="page === 'login'" class="member-wrapper">
      <div class="page-title">
        <img
          src="@/asset/images/2023_new_year/game-title.png"
          alt="揚眉兔氣迎新春！"
          class="img-fluid d-block mx-auto"
        />
        <div>登入後即可獲得刮刮樂！</div>
        <img
          src="@/asset/images/2023_new_year/new-year-logo.png"
          class="img-fluid d-block mx-auto mt-4"
        />
      </div>
      <form class="group-wrapper" @submit.prevent="newyearLogin">
        <div class="input-group">
          <span class="input-group-title">會員登入</span>
          <input
            type="text"
            class="form-control label-input"
            placeholder="請輸入手機號碼"
            v-model="phone"
            maxlength="10"
            required
          />
        </div>
        <div class="input-group">
          <input
            type="password"
            class="form-control label-input"
            placeholder="請輸入密碼"
            v-model="password"
            required
          />
        </div>
        <div class="input-group">
          <input
            type="email"
            class="form-control label-input"
            placeholder="請輸入email"
            v-model="email"
            required
          />
          <span
            class="message position-absolute"
            style="top: 25px; width: 100%; text-align: center"
            >*請填寫正確信箱，獎項才能順利送達給你喔</span
          >
        </div>
        <div class="input-group">
          <div class="d-flex" style="margin: 15px auto">
            <input
              type="checkbox"
              @click="isAgree = !isAgree"
              :checked="isAgree"
              class="mr-2"
            />
            <div class="input-group-title">我同意平台相關條款之規定</div>
          </div>
        </div>
        <div class="input-group justify-content-center">
          <button class="btn btn-next">登入</button>
          <button
            type="button"
            class="btn btn-next ml-2"
            @click="page = 'register'"
          >
            我要註冊
          </button>
        </div>
      </form>
      <div class="message" v-if="message">{{ message }}</div>
    </div>
    <div v-else-if="page === 'prize'" class="prize-wrapper">
      <div class="page-title" v-if="prizeProcess == 'finished'">
        <img
          src="@/asset/images/2023_new_year/game-title.png"
          alt="揚眉兔氣迎新春！"
          class="img-fluid d-block mx-auto mb-2"
        />
        <div style="font-size: 24px">普匯刮刮樂</div>
      </div>
      <transition name="fade">
        <div
          class="page-content"
          v-if="prizeProcess == 'starting'"
          @click="doPrizeProcess"
        >
          <img
            src="@/asset/images/2023_new_year/newyear-card-start.jpg"
            class="d-block mx-auto"
          />
        </div>
        <div class="page-content" v-else-if="prizeProcess == 'doing'">
          <img
            src="@/asset/images/2023_new_year/newyear-card-doing.gif"
            class="d-block mx-auto"
          />
        </div>
        <div class="prize-content" v-else-if="prizeProcess == 'finished'">
          <div>
            <div>恭喜你刮中</div>
            <div>全家點數 100 點</div>
          </div>
          <div>
            <img
              src="@/asset/images/2023_new_year/card-ome.png"
              class="img-fluid"
            />
          </div>
          <div>
            <button class="btn btn-next">
              <a href="/borrowLink" target="_blank" class="text-white"
                >點我實名認證領取</a
              >
            </button>
          </div>
          <div class="share">
            <div class="title w-100 mb-2">分享給好友</div>
            <button class="btn btn_link link" @click="addToFB">
              <img :src="'/images/facebook.svg'" class="img-fluid" />
            </button>
            <button class="btn btn_link link" @click="addToLINE">
              <img :src="'/images/line.png'" class="img-fluid" />
            </button>
            <button class="btn btn_link link" @click="copyLink">
              <img :src="'/images/link_grey.svg'" class="img-fluid" />
            </button>
            <div v-if="copied" class="title mt-2">網址複製成功 !</div>
          </div>
        </div>
      </transition>
    </div>
    <div v-else-if="page === 'noPrize'" class="prize-wrapper">
      <div class="page-title" v-if="prizeProcess == 'finished'">
        <img
          src="@/asset/images/2023_new_year/game-title.png"
          alt="揚眉兔氣迎新春！"
          class="img-fluid d-block mx-auto mb-2"
        />
        <div style="font-size: 24px">普匯刮刮樂</div>
      </div>
      <transition name="fade">
        <div
          class="page-content"
          v-if="prizeProcess == 'starting'"
          @click="doPrizeProcess"
        >
          <img
            src="@/asset/images/2023_new_year/newyear-card-start.jpg"
            class="d-block mx-auto"
          />
        </div>
        <div class="page-content" v-else-if="prizeProcess == 'doing'">
          <img
            src="@/asset/images/2023_new_year/newyear-card-doing.gif"
            class="d-block mx-auto"
          />
        </div>
        <div class="prize-content" v-else-if="prizeProcess == 'finished'">
          <div>
            <div>未中獎</div>
            <div>謝謝你的參與</div>
          </div>
          <div>
            <img
              src="@/asset/images/2023_new_year/card-ome.png"
              class="img-fluid"
            />
          </div>
          <div class="share">
            <div class="title w-100 mb-2">分享給好友</div>
            <button class="btn btn_link link" @click="addToFB">
              <img :src="'/images/facebook.svg'" class="img-fluid" />
            </button>
            <button class="btn btn_link link" @click="addToLINE">
              <img :src="'/images/line.png'" class="img-fluid" />
            </button>
            <button class="btn btn_link link" @click="copyLink">
              <img :src="'/images/link_grey.svg'" class="img-fluid" />
            </button>
            <div v-if="copied" class="title mt-2">網址複製成功 !</div>
          </div>
        </div>
      </transition>
    </div>
    <div v-else-if="page === 'finished'" class="prize-wrapper">
      <div class="page-title">
        <img
          src="@/asset/images/2023_new_year/game-title.png"
          alt="揚眉兔氣迎新春！"
          class="img-fluid d-block mx-auto mb-2"
        />
        <div style="font-size: 24px">普匯刮刮樂</div>
      </div>
      <div class="prize-content">
        <div>
          <div>你已經玩過囉~</div>
          <div>將機會分享給朋友吧!</div>
        </div>
        <div>
          <img
            src="@/asset/images/2023_new_year/card-ome.png"
            class="img-fluid"
          />
        </div>
        <div class="share">
          <div class="title w-100 mb-2">分享給好友</div>
          <button class="btn btn_link link" @click="addToFB">
            <img :src="'/images/facebook.svg'" class="img-fluid" />
          </button>
          <button class="btn btn_link link" @click="addToLINE">
            <img :src="'/images/line.png'" class="img-fluid" />
          </button>
          <button class="btn btn_link link" @click="copyLink">
            <img :src="'/images/link_grey.svg'" class="img-fluid" />
          </button>
          <div v-if="copied" class="title mt-2">網址複製成功 !</div>
        </div>
      </div>
    </div>
    <div class="cardgame-info position-relative">
      <img
        src="@/asset/images/2023_new_year/cardgame-info.jpg"
        class="img-fluid"
      />
      <div class="copyright">
        Copyright ©2020 普匯金融科技股份有限公司 All rights reserved.
        <div class="links">
          <a href="/userTerms" class="item">使用者條款</a> |
          <a href="/privacyTerms" class="item">隱私權條款</a> |
          <a href="/loanerTerms" class="item">借款人服務條款</a> |
          <a href="/lenderTerms" class="item">貸款人服務條款</a> |
          <a href="/transferTerms" class="item">債權受讓人服務條款</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import card0 from '@/asset/images/2023_new_year/card0.png'
import card1 from '@/asset/images/2023_new_year/card1.png'
export default {
  data() {
    return {
      isSended: false,
      copied: false,
      phone: '',
      password: '',
      confirmPassword: '',
      code: '',
      email: '',
      termsTitle: '',
      termsContent: '',
      message: '',
      isAgree: false,
      timerCode: null,
      counter: 180,
      process: false,
      randkeys: [],
      cardBase: [card0, card1],
      page: 'game',
      prizeProcess: 'starting',
      imgs: {
        1: {
          question: '請問「我站在雲林」一詞源於哪個國家歌手的歌曲呢？',
          selection: ['泰國', '越南', '緬甸'],
        },
        2: {
          question: '請問「歸剛欸」這句話意思是什麼？',
          selection: ['回家', '住海邊', '整天、成天'],
        },
        3: {
          question: '請問「YYDS」下列的使用情境，何者錯誤呢？',
          selection: [
            '我上班遲到了啦 YYDS',
            '蔡健雅四度獲得金曲歌后，真是 YYDS',
            '台積電股價又漲 YYDS',
          ],
        },
        4: {
          question: '請問「腦霧」是指什麼意思？',
          selection: ['充滿霧的美景', '確診後的後遺症', '眼前看不到東西'],
        },
        5: {
          question:
            '請問「你知道這是什麼嗎？聽起來還不錯對吧？」出自於哪一個影音平台廣告？',
          selection: ['TikTok', 'IG', 'YouTube'],
        },
        6: {
          question: '請問「降肉」流行熱潮是出自哪位網紅影片？',
          selection: ['阿翰', '阿滴', '蔡阿嘎'],
        },
        7: {
          question:
            '「姨母笑」指的是年長女性看到年輕帥氣的孩子露出寵溺、疼愛的笑容，請問「姨母笑」出自哪一個國家？',
          selection: ['台灣', '韓國', '日本'],
        },
        8: {
          question: '請問「芭比Q」代表什麼意思？',
          selection: ['芭比娃娃太可愛', '吃烤肉', '完蛋了'],
        },
        9: {
          question: '請問「天菜」代表什麼意思？',
          selection: ['喜歡的對象類型', '營養豐富的蔬菜', '在特定日子吃素'],
        },
        10: {
          question: '請問「社畜」代表什麼意思？',
          selection: [
            '家中飼養的哺乳類動物',
            '上班族為公司放棄身為人類尊嚴的狀態',
            '坐著嬰兒車出遊的寵物',
          ],
        },
        11: {
          question: '一部影片或相片的說明內有「#NSFW」是什麼意思？',
          selection: ['那你懂我意思了', '不解釋', '上班不要看'],
        },
        12: {
          question: '請問「挖苦挖苦／哇庫哇庫」代表什麼意思？',
          selection: [
            '充滿期待、喜悅的心情',
            '表達事情太誇張了',
            '機率很低不太可能的意思',
          ],
        },
        13: {
          question: '請問普匯創立於哪一年份？',
          selection: ['2025年', '2017年', '2023年'],
        },
        14: {
          question: '請問普匯是媒合誰與誰的平台？',
          selection: ['你和我', '老師與學生', '借款人與投資人'],
        },
        15: {
          question: '請問普匯投資是多少元起投？',
          selection: ['一千元', '一萬元', '三萬元'],
        },
        16: {
          question: '請問普匯目前主要業務項目為何？',
          selection: ['保險業務', '投資與借貸', '慈善事業'],
        },
        17: {
          question: '請問普匯的代表色為何？',
          selection: ['紅色', '黑色', '藍色'],
        },
        18: {
          question: '請問普匯申請貸款的速度有多快？',
          selection: ['5 分鐘', '60 分鐘', '1 天'],
        },
        19: {
          question: '請問普匯資料審核速度多快？',
          selection: ['2 天', '10 分鐘', '1 週'],
        },
        20: {
          question: '請問普匯放款速度有多快？',
          selection: ['1 週', '3 個工作天', '60 分鐘'],
        },
        21: {
          question: '請問下列何者非普匯主打特色？',
          selection: [
            '專人每日打電話關心',
            'AI人工智慧審核',
            '全線上無人化審核',
          ],
        },
        22: {
          question: '請問普匯的核心理念是什麼？',
          selection: [
            '速度、紀律、績效',
            '普惠金融，匯流人才',
            '誠信、效能、創新',
          ],
        },
        23: {
          question: '請問下列何者為「興奮到模糊」的意思？',
          selection: [
            '眼前一片黑，看不清楚東西',
            '頭暈腦脹',
            '對於某件事物的即將發生感到興奮',
          ],
        },
        24: {
          question: '請問「好 Chill」代表什麼意思？',
          selection: ['放鬆一下、愜意、很酷的生活方式', '很冷靜', '很寒冷'],
        },
        25: {
          question: '請問「廠廠」的意思是什麼？',
          selection: ['工廠很豪華', '呵呵', '場地很大'],
        },
        26: {
          question: '請問「種草」代表什麼意思？',
          selection: ['喜歡上一個人', '愛護環境', '想要衝動購買'],
        },
        27: {
          question: '請問「拔草」代表什麼意思？',
          selection: ['對想買的東西退燒了', '拔雜草', '破壞地球'],
        },
        28: {
          question: '請問「洗澡卡」代表什麼意思？',
          selection: ['邀請你一起幫狗洗澡', '用這個理由中斷對談', '泡湯券'],
        },
        29: {
          question: '請問「五星吹捧」這個詞來自於哪一個 Podcast？',
          selection: ['通勤十分鐘', '百靈果', '股癌'],
        },
        30: {
          question: '請問「史密斯」代表什麼意思？',
          selection: ['什麼意思', '威爾・史密斯', '史密斯牛排'],
        },
      },
    }
  },
  mounted: function () {
    this.randkeys = [
      1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21,
      22, 23, 24, 25, 26, 27, 28, 29, 30,
    ]
    for (let i = this.randkeys.length - 1; i > 0; i--) {
      let j = Math.floor(Math.random() * (i + 1))
        ;[this.randkeys[i], this.randkeys[j]] = [
          this.randkeys[j],
          this.randkeys[i],
        ]
    }
    this.randkeys = this.randkeys.slice(0, 9)
    $('.countdown').hide()
    $(document)
      .off(
        'click',
        '.cardA:not(.done):not(.active),.cardB:not(.done):not(.active)'
      )
      .on(
        'click',
        '.cardA:not(.done):not(.active),.cardB:not(.done):not(.active)',
        function (e, t) {
          $('.cardA,.cardB').hide()
          $(this).addClass('active').show()
          $('.countdown').show()
          $('.countdown span').text('15')
        }
      )
    let data = {
      user_id: localStorage.getItem('userData')
        ? JSON.parse(localStorage.getItem('userData'))['id']
        : {},
    }
  },
  methods: {
    timer() {
      let my = this
      this.time = setInterval(function () {
        var time = $('.countdown span')
        if (time.text() > 0) {
          time.text((time.text() - 1))
        } else if (time.text() == 0) {
          time.text('*')
          $('#falseModal').modal('show')
          setTimeout(function () {
            location.reload()
          }, 5000)
          return
        }
      }, 1000)
    },
    setTime() {
      this.timer()
    },
    goPage(page) {
      $('#nextModal').modal('hide')
      this.page = page
    },
    stopTime() {
      if (this.time) {
        clearInterval(this.time)
      }
    },
    ans(event) {
      if (!this.process) {
        this.process = true
        console.log(event.target.parentElement.dataset.id)
        let data = {
          qnum: event.target.parentElement.dataset.id % 3,
          qans: Number(event.target.dataset.ans),
        }
        axios
          .post('/getAns', data)
          .then((res) => {
            if (res.data.ans == 1) {
              $('.active:not(.done)').addClass('done')
              $('.cardA,.cardB').show()
              $('.active').removeClass('active')
              $(
                '[data-id=' +
                data.qnum +
                '] .cardAns:not([data-ans=' +
                data.qans +
                '])'
              ).remove()
              this.countDown = false
              if ($('.done').length > 2) {
                $('#nextModal').modal('show')
                return
              } else {
                alert('恭喜你答對了!! 下一題~')
              }
            } else {
              $('#falseModal').modal('show')
              return
            }
            this.process = false
            $('.countdown').hide()
          })
          .catch((err) => {
            console.error(err)
          })
      } else {
        console.log('duplicate!!')
      }
    },
    reciprocal() {
      this.counter--
      if (this.counter === 0) {
        clearInterval(this.timerCode)
        this.timerCode = null
        alert('驗證碼失效，請重新申請')
        location.reload()
      }
    },
    getCaptcha(type) {
      let phone = this.phone

      if (!phone) {
        this.message = '請輸入手機'
        return
      }
      this.counter = 180
      axios
        .post(`${location.origin}/getCaptcha`, { phone, type })
        .then((res) => {
          this.isSended = true
          this.message = ''
          this.timerCode = setInterval(() => {
            this.reciprocal()
          }, 1000)
        })
        .catch((error) => {
          let errorsData = error.response.data
          this.message = `${this.$store.state.smsErrorCode[errorsData.error]}`
        })
    },
    addToFB() {
      window.open(
        `https://www.addtoany.com/add_to/facebook?linkurl=${location.href}`,
        "_blank",
        "top=" +
        (window.outerHeight / 2 - 265) +
        ", left=" +
        (window.outerWidth / 2 - 265) +
        ",height=530,width=530,toolbar=no,resizable=no,location=no"
      );
    },
    addToLINE() {
      window.open(
        `https://lineit.line.me/share/ui?url=${location.href}`,
        "_blank",
        "top=" +
        (window.outerHeight / 2 - 265) +
        ", left=" +
        (window.outerWidth / 2 - 265) +
        ",height=530,width=530,toolbar=no,resizable=no,location=no"
      );
    },
    copyLink() {
      let self = this;
      navigator.clipboard.writeText(location.href).then(function () {
        self.copied = true;
        setTimeout(function () {
          self.copied = false;
        }, 1000);
      });
    },
    async newyearRegister() {
      this.prizeProcess = 'starting'
      if (this.isAgree == false) {
        alert('請勾選同意平台相關規定')
        return
      }
      const phone = this.phone
      const password = this.password
      const password_confirmation = this.confirmPassword
      const promo = '2023_newyear_event'
      const email = this.email
      const code = this.code
      clearInterval(this.timerCode)
      this.timerCode = null
      axios
        .post('/newyearRegister', {
          phone,
          password,
          password_confirmation,
          promo,
          email,
          code,
        })
        .then((res) => {
          if (res.data.result == 'SUCCESS') {
            this.page = 'noPrize'
          }
        })
        .catch((error) => {
          let errorsData = error.response.data
          if (errorsData.message) {
            let messages = []
            $.each(errorsData.errors, (key, item) => {
              item.forEach((message, k) => {
                messages.push(message)
              })
            })
            this.message = messages.join('、')
          } else {
            this.message = `${this.$store.state.registerErrorCode[errorsData.error]
              }`
          }
        })
    },
    async newyearLogin() {
      this.prizeProcess = 'starting'
      if (this.isAgree == false) {
        alert('請勾選同意平台相關規定')
        return
      }
      const phone = this.phone
      const password = this.password
      const email = this.email
      axios
        .post('/newyearLogin', {
          phone,
          password,
          email,
        })
        .then(({ data }) => {
          if (data.prize && data.prize === 1) {
            this.page = 'prize'
          } else if (data.prize && data.prize === 2) {
            // 2為已參加過
            this.page = 'finished'
          } else {
            this.page = 'noPrize'
          }
        })
        .catch((error) => {
          let errorsData = error.response.data;
          if (errorsData.message) {
            let messages = [];
            $.each(errorsData.errors, (key, item) => {
              item.forEach((message, k) => {
                messages.push(message);
              });
            });
            this.message = messages.join('、');
          } else {
            this.message = `${this.$store.state.loginErrorCode[errorsData.error]}
                           ${errorsData.data ? `剩餘錯誤次數(${errorsData.data.remind_count})` : ''}`;
          }
        })
    },
    doPrizeProcess() {
      // set prizeProcess change to "doing" 3sec to "finsished"
      this.prizeProcess = 'doing'
      setTimeout(() => {
        this.prizeProcess = 'finished'
      }, 3000)
    },
  },
}
</script>

<style lang="scss">
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
.alesis-footer {
  display: none !important;
}
.content-bg {
  background-image: url('~images/2023_new_year/newyear-bg0.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}
.btn-next {
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 23px;
  color: #ffffff;
  background-color: #036eb7;
  border-radius: 10px;
  padding: 5px 20px;
}
.btn-captcha {
  color: #fff;
  background: #fda200;
  border-radius: 15px;
  line-height: 15px;
  color: #ffffff;
  font-size: 16px;
  margin-left: 20px;
  width: 190px;
}
.btn-disable {
  border-radius: 50px;
  width: 190px;
  height: 30px;
  line-height: 15px;
  font-size: 18px;
  background: #d5d5d5;
  color: #969696;
  cursor: default;
  margin-left: 20px;
}
.cardgame-info {
  max-width: 414px;
  margin: 0 auto;
  .copyright {
    position: absolute;
    bottom: 80px;
    font-size: 14px;
    color: #6b563b;
    text-align: center;
    .links .item {
      color: #6b563b;
    }
  }
}

.tip {
  position: absolute;
  bottom: -17px;
  right: 27px;
  font-size: small;
  color: #fff;
}
.message {
  text-align: center;
  color: #fff;
  font-weight: bolder;
  margin-top: 15px;
}
.modal-content-title {
  font-style: normal;
  font-weight: 500;
  font-size: 20px;
  line-height: 29px;
  text-align: center;
  color: #000000;
}
.cardgame-wrapper {
  background-image: url('~images/2023_new_year/newyear-bg1.jpg');
  background-size: cover;
  background-position: top center;
  max-width: 414px;
  margin: 0 auto;
  height: 750px;
  .cardgame-title {
    padding-top: 45px;
  }
  .cardgame-sub {
    margin-top: 8px;
    font-style: normal;
    font-weight: 700;
    font-size: 16px;
    line-height: 20px;
    text-align: center;

    color: #ffffff;
  }
  .cardgame-prize {
    font-style: normal;
    font-weight: 700;
    font-size: 16px;
    line-height: 23px;
    text-align: center;
    color: #ffffff;
  }
  .cards {
    display: grid;
    place-content: center;
    grid-template-columns: repeat(3, 115px);
    grid-template-rows: repeat(3, 145px);
    margin: 0 auto;
    padding: 20px 0;
    text-align: center;
    .countdown {
      font-size: 28px;
      font-weight: bold;
      letter-spacing: 2px;
      color: red;
      position: relative;
      z-index: 11;
      top: 45px;
      left: 125px;
    }

    .cardA,
    .cardB {
      display: inline-table;
      padding: 8px 5px;
      font-weight: bold;
      color: white;
      margin: 15px 5px 0;
      position: relative;
      transition: transform 1s;
      transform-style: preserve-3d;
      background-repeat: no-repeat;
      &.active {
        transform: rotateY(180deg);
        -moz-transform: rotateY(180deg);
        -webkit-transform: rotateY(180deg);
        -o-transform: rotateY(180deg);
        -ms-transform: rotateY(180deg);
        border-radius: 22px;
        width: 95%;
        height: 100%;
        margin: 0 auto;
        border: 0;
        z-index: 10;
        background-color: transparent;
        .card-front {
          display: none;
        }
        .cardQuestion {
          font-size: 24px;
        }
        .cardFlip {
          -webkit-backface-visibility: visible;
          backface-visibility: visible;
        }
      }
      &.done {
        transform: rotateY(180deg);
        -moz-transform: rotateY(180deg);
        -webkit-transform: rotateY(180deg);
        -o-transform: rotateY(180deg);
        -ms-transform: rotateY(180deg);
        .card-front {
          .img-1 {
            display: none;
          }
          .img-2 {
            display: block;
          }
        }
        .card-back {
          padding: 0px !important;
        }
        .cardQuestion {
          display: none;
        }
        .cardAns {
          display: none;
        }
        .cardFlip {
          -webkit-backface-visibility: visible;
          backface-visibility: visible;
          position: absolute;
          &.card-back {
            left: 0;
            width: 100%;
            height: 100%;
            background: none;
            overflow: hidden;
          }
        }
      }
      .cardFace {
        margin: 0;
        width: 100%;
        position: relative;
        border-radius: 10px;
        &.img-1 {
          display: block;
        }
        &.img-2 {
          display: none;
        }
      }
      .cardQuestion {
        font-size: 12px;
        padding: 0 5px;
        display: block;
      }
      .cardAns {
        display: block;
        font-size: 18px;
        width: 100%;
        text-align: center;
      }
      .cardFlip {
        position: absolute;
        backface-visibility: hidden;
        left: 0;
        padding: 0 5px;
        &.card-back {
          background-image: url('~images/2023_new_year/card-sapce-qa.svg');
          background-size: cover;
          border-radius: 15px;
          left: -111px;
          transform: rotateY(180deg);
          -moz-transform: rotateY(180deg);
          -webkit-transform: rotateY(180deg);
          -o-transform: rotateY(180deg);
          -ms-transform: rotateY(180deg);
          width: 320px;
          height: 515px;
          background-repeat: no-repeat;
          padding: 85px 15px;
        }
      }
    }
  }
}
.page-title {
  padding-top: 45px;
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 23px;
  text-align: center;
  color: #ffffff;
}
.input-group-title {
  width: 100%;
  text-align: left;
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 23px;
  color: #ffffff;
}
.member-wrapper {
  background-image: url('~images/2023_new_year/newyear-bg2.jpg');
  max-width: 414px;
  margin: 0 auto;
  background-size: cover;
  background-position: top center;
  height: 785px;
}
.prize-wrapper {
  background-image: url('~images/2023_new_year/newyear-bg2.jpg');
  max-width: 414px;
  margin: 0 auto;
  background-size: cover;
  background-position: top center;
  height: 580px;
}
.prize-content {
  display: flex;
  flex-direction: column;
  padding: 12px 40px;
  margin: 20px auto;
  max-width: 295px;
  gap: 15px;
  background: #fda200;
  border-radius: 20px;
  font-style: normal;
  font-weight: 500;
  font-size: 20px;
  line-height: 29px;
  text-align: center;
  color: #000000;
}
.share {
  .title {
    font-style: normal;
    font-weight: 500;
    font-size: 20px;
    line-height: 29px;
    text-align: center;
    color: #153a71;
  }
  .link {
    padding: 0;
    margin: 0 1em;
    img {
      max-height: 2em;
    }
  }
}
.group-wrapper {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 15px;
}
</style>
