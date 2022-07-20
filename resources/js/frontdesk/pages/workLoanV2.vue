<template>
  <div class="work-loan">
    <div class="banner-swiper position-relative">
      <!-- Additional required wrapper -->
      <div class="swiper-wrapper">
        <!-- Slides -->
        <div class="swiper-slide">
          <img
            src="@/asset/images/workloan/workloan-cover.png"
            alt="普匯上班族貸主banner"
            class="img-fluid d-sm-block d-none w-100"
          />
          <img
            src="@/asset/images/workloan/workloan-cover-phone.png"
            alt="普匯上班族貸主banner"
            class="img-fluid d-sm-none d-block w-100"
          />
        </div>
        <div class="swiper-slide">
          <img
            src="@/asset/images/workloan/workloan-cover-1.png"
            alt="普匯上班族購房貸banner"
            class="img-fluid d-sm-block d-none w-100"
          />
          <img
            src="@/asset/images/workloan/workloan-cover-phone-1.png"
            alt="普匯上班族購房貸banner"
            class="img-fluid d-sm-none d-block w-100"
          />
        </div>
        <div class="swiper-slide">
          <img
            src="@/asset/images/workloan/workloan-cover-2.png"
            alt="普匯上班族裝修貸banner"
            class="img-fluid d-sm-block d-none w-100"
          />
          <img
            src="@/asset/images/workloan/workloan-cover-phone-2.png"
            alt="普匯上班族裝修貸banner"
            class="img-fluid d-sm-none d-block w-100"
          />
        </div>
        <div class="swiper-slide">
          <img
            src="@/asset/images/workloan/workloan-cover-3.png"
            alt="普匯上班族購車貸banner"
            class="img-fluid d-sm-block d-none w-100"
          />
          <img
            src="@/asset/images/workloan/workloan-cover-phone-3.png"
            alt="普匯上班族購車貸banner"
            class="img-fluid d-sm-none d-block w-100"
          />
        </div>
      </div>
      <!-- If we need pagination -->
      <div class="swiper-pagination"></div>
    </div>
    <div class="features">
      <div class="block-content">
        <h1 class="h1" data-aos="fade-right" data-aos-duration="800">
          隨意貸 輕鬆還
        </h1>
        <div class="row justify-content-center no-gutters">
          <div class="feature-grid">
            <div class="card" data-aos="fade-up">
              <div class="card-body">
                <div class="card-cover">
                  <img
                    src="@/asset/images/workloan/feature-icon-1.png"
                    class="img-fluid mx-auto"
                  />
                </div>
                <div class="card-title">利率最低5%起</div>
                <div class="card-divider"></div>
              </div>
            </div>
            <div class="card" data-aos="fade-up">
              <div class="card-body">
                <div class="card-cover">
                  <img
                    src="@/asset/images/workloan/feature-icon-2.png"
                    class="img-fluid mx-auto"
                  />
                </div>
                <div class="card-title">絕無隱藏費用</div>
                <div class="card-divider"></div>
              </div>
            </div>
            <div class="card" data-aos="fade-up">
              <div class="card-body">
                <div class="card-cover">
                  <img
                    src="@/asset/images/workloan/feature-icon-3.png"
                    class="img-fluid mx-auto"
                  />
                </div>
                <div class="card-title">借款最長24期</div>
                <div class="card-divider"></div>
              </div>
            </div>
            <div class="card" data-aos="fade-up">
              <div class="card-body">
                <div class="card-cover">
                  <img
                    src="@/asset/images/workloan/feature-icon-4.png"
                    class="img-fluid mx-auto"
                  />
                </div>
                <div class="card-title">每期償還本息</div>
                <div class="card-divider"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row no-gutters justify-content-center">
          <div
            class="feature-suit"
            data-aos="zoom-in-up"
            data-aos-duration="800"
          >
            <div class="d-sm-flex align-items-center suit-title-container">
              <div class="suit-title">適用對象</div>
              <div class="suit-underline d-md-none"></div>
            </div>
            <div class="suit-item">•20~45歲</div>
            <div class="suit-item">•持雙證件自拍上傳</div>
            <div class="suit-item">•提供基本收入資料</div>
            <div class="suit-item">•信用良好（無催收/呆帳紀錄）</div>
          </div>
        </div>
        <h1 class="h1" data-aos="fade-left" data-aos-duration="800">
          超過10萬用戶使用
        </h1>
        <h2 class="h2" data-aos="fade-right" data-aos-duration="800">
          <div class="d-md-block d-none">
            體驗超簡便快速、安全又隱私的金融科技貸款服務
          </div>
          <div class="d-md-none d-block">體驗超簡便快速、安全又隱私的</div>
          <div class="d-md-none d-block">金融科技貸款服務</div>
        </h2>
        <div class="feature-user">
          <div class="card" data-aos="fade-up" data-aos-duration="800">
            <div class="bg-text">
              <div>ENTERPRISE</div>
              <div>EMPLOYEES</div>
            </div>
            <div class="card-body">
              <div class="portrait">
                <img
                  src="@/asset/images/workloan/feature-user-1.png"
                  class="img-fluid"
                />
              </div>
              <div class="portrait-info">一般企業員工</div>
              <div class="card-text">
                <div>• 不論公司規模，收入穩定皆適用！</div>
                <div>• 不管生活急用、資金周轉</div>
                <div>• 添購家居、車輛或小孩教養金</div>
              </div>
            </div>
          </div>
          <div class="card" data-aos="fade-up" data-aos-duration="800">
            <div class="bg-text">
              <div>FINANCIAL</div>
              <div>INSTITUTION</div>
            </div>
            <div class="card-body">
              <div class="portrait">
                <img
                  src="@/asset/images/workloan/feature-user-2.png"
                  class="img-fluid"
                />
              </div>
              <div class="portrait-info">金融機構員工</div>
              <div class="card-text">
                <div>• 證券、銀行、保險業皆適用</div>
                <div>• 投資理財，股票交割…</div>
                <div>• 快速取得資金，獲利加倍！</div>
              </div>
            </div>
          </div>
          <div class="card" data-aos="fade-up" data-aos-duration="800">
            <div class="bg-text">
              <div>PUBLIC</div>
              <div>INSTITUTION</div>
            </div>
            <div class="card-body">
              <div class="portrait">
                <img
                  src="@/asset/images/workloan/feature-user-3.png"
                  class="img-fluid"
                />
              </div>
              <div class="portrait-info">公家機關優質貸</div>
              <div class="card-text">
                <div>• 學校、醫院從業人員皆適用</div>
                <div>• 借款記錄不上聯徵，不影響信用</div>
                <div>• 全程免照會，無人化安全又隱私！</div>
              </div>
            </div>
          </div>
          <div class="card" data-aos="fade-up" data-aos-duration="800">
            <div class="bg-text">
              <div>LISTED</div>
              <div>COMPANIES</div>
            </div>
            <div class="card-body">
              <div class="portrait">
                <img
                  src="@/asset/images/workloan/feature-user-4.png"
                  class="img-fluid"
                />
              </div>
              <div class="portrait-info">上市櫃企業菁英貸</div>
              <div class="card-text">
                <div>• 快速審核、持證自拍</div>
                <div>• 免跑銀行、免受干擾</div>
                <div>• 台積電、日月光等大企業</div>
                <div>• 都選擇使用普匯APP！</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="purpose">
      <div class="block-content">
        <h1 class="h1">生活就是需要錢</h1>
        <h2 class="h2">普匯最了解您的資金需求</h2>
        <div class="d-flex justify-content-center">
          <div class="purpose-grid">
            <div
              class="purpose-col"
              data-aos="flip-up"
              data-aos-delay="1500"
              data-aos-duration="800"
            >
              <div class="purpose-img-1">
                <img
                  src="@/asset/images/workloan/purpose-img-1.png"
                  alt=""
                  class="img-fluid"
                />
              </div>
              <div class="purpose-col-title">購車頭期款</div>
              <div class="purpose-col-text">普匯全額貸給你</div>
            </div>
            <div
              class="purpose-col"
              data-aos="flip-left"
              data-aos-delay="1000"
              data-aos-duration="800"
            >
              <div class="purpose-img-2">
                <img
                  src="@/asset/images/workloan/purpose-img-2.png"
                  alt=""
                  class="img-fluid"
                />
              </div>
              <div class="purpose-col-title">投資理財</div>
              <div class="purpose-col-text">股票交割款、投資本金</div>
            </div>
            <div
              class="purpose-col"
              data-aos="flip-right"
              data-aos-delay="500"
              data-aos-duration="800"
            >
              <div class="purpose-img-3">
                <img
                  src="@/asset/images/workloan/purpose-img-3.png"
                  alt=""
                  class="img-fluid"
                />
              </div>
              <div class="purpose-col-title">購房頭期款</div>
              <div class="purpose-col-text">普匯貸你自備款</div>
            </div>
            <div
              class="purpose-col"
              data-aos="flip-down"
              data-aos-duration="800"
            >
              <div class="purpose-img-4">
                <img
                  src="@/asset/images/workloan/purpose-img-4.png"
                  alt=""
                  class="img-fluid"
                />
              </div>
              <div class="purpose-col-title">日常消費</div>
              <div class="purpose-col-text">生活急需、資金周轉</div>
            </div>
          </div>
        </div>
        <div class="form-title">專人溫馨服務</div>
        <form
          ref="contactForm"
          @submit.prevent="doContact"
          class="row no-gutters justify-content-center"
        >
          <div class="col-md col-12">
            <div class="row no-gutters mb-3">
              <div class="form-item-text">*您的尊稱：</div>
              <input
                type="text"
                name="name"
                class="form-item-input col"
                maxlength="20"
                required
              />
            </div>
            <div class="row no-gutters mb-3">
              <div class="form-item-text">*手機號碼：</div>
              <input
                type="text"
                name="phone"
                maxlength="10"
                class="form-item-input col"
              />
            </div>
            <div class="row no-gutters mb-3">
              <div class="form-item-text">*身分證性別：</div>
              <div class="radio-custom">
                <label class="form-item-label">
                  <input
                    type="radio"
                    name="gender"
                    class="radio-inline"
                    value="1"
                    required
                  />
                  <span class="outside"><span class="inside"></span></span>男
                </label>
                <label class="form-item-label">
                  <input
                    type="radio"
                    name="gender"
                    class="radio-inline"
                    value="2"
                    required
                  />
                  <span class="outside"><span class="inside"></span></span>女
                </label>
              </div>
            </div>
            <div class="row no-gutters mb-3">
              <div class="form-item-text">*LINE帳號：</div>
              <input
                type="text"
                name="line"
                maxlength="40"
                required
                class="form-item-input col"
              />
            </div>
            <div class="row no-gutters mb-3">
              <div class="form-item-text">*電子信箱：</div>
              <input
                type="text"
                name="email"
                class="form-item-input col"
                required
              />
            </div>
            <div class="row no-gutters mb-3">
              <div class="form-item-text">*聯絡時間：</div>
              <select
                type="text"
                class="form-item-input col"
                name="contact_time"
                required
              >
                <option selected disabled value="">-請選擇-</option>
                <option value="1">隨時</option>
                <option value="2">上午(09:00 ~ 12:00)</option>
                <option value="3">下午(13:30 ~ 18:00)</option>
              </select>
            </div>
            <div class="row no-gutters mb-3">
              <div class="form-item-text">*資金需求原因：</div>
              <textarea
                type="text"
                name="reason"
                style="min-height: 15vh"
                class="form-item-input col"
                maxlength="100"
                required
              ></textarea>
            </div>
            <div class="row no-gutters">
              <div class="form-item-text d-md-block d-none"></div>
              <button class="btn btn-block btn-form-submit col">送出</button>
            </div>
          </div>
          <div class="col-md-6 col-12">
            <img
              src="@/asset/images/workloan/form-cover-1.png"
              alt=""
              class="img-fluid form-cover"
            />
          </div>
        </form>
      </div>
    </div>
    <div class="diagram">
      <div class="block-content">
        <h1 class="h1">申貸流程</h1>
        <h2 class="h2">5分鐘申貸、10分鐘審核、1小時放款</h2>
        <div class="swiper">
          <!-- Additional required wrapper -->
          <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide">
              <div class="swiper-left swiper-text-1">
                <div>量身製定資金需求</div>
                <div>提供您更滿意的貸款</div>
              </div>
              <img
                src="@/asset/images/workloan/step-1-1.png"
                class="step-img"
              />
              <img
                src="@/asset/images/workloan/step-1-2.png"
                class="step-img"
              />
              <div class="swiper-step">
                <div class="swiper-step-title">Step 1：</div>
                <div class="swiper-step-info">• 選擇「申貸專案」</div>
                <div class="swiper-step-next"></div>
                <div class="swiper-step-info">• 上傳「資金需求佐證文件」</div>
                <div class="swiper-step-next"></div>
                <div class="swiper-step-info">• 選擇「借款金額與期數」</div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="swiper-left">
                <div class="swiper-text-1">需要準備什麼資料？</div>
                <button
                  class="swiper-text-2 btn btn-link"
                  @click="showCheckList = !showCheckList"
                >
                  Check list：
                </button>
                <transition name="fade">
                  <div class="check-list-content" v-show="showCheckList">
                    <div class="swiper-text-3">•雙證件</div>
                    <div class="swiper-text-3">•銀行存款金融卡</div>
                    <div class="swiper-text-3">•工作收入證明</div>
                    <div class="swiper-text-info">
                      <div>(1)勞保異動明細</div>
                      <div>(2)近三個月薪資憑證</div>
                      <div>(3)近三個月存摺封面與內頁</div>
                      <div>(4)近年度扣繳憑單</div>
                    </div>
                    <div class="swiper-text-3">•個人聯徵信用報告</div>
                    <a
                      href="https://www.jcic.org.tw/main_ch/docDetail.aspx?uid=92&pid=73&docid=54"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="swiper-text-link"
                      >如何申請聯徵？</a
                    >
                  </div>
                </transition>
              </div>
              <img
                src="@/asset/images/workloan/step-2-1.png"
                class="step-img"
              />
              <img
                src="@/asset/images/workloan/step-2-2.png"
                class="step-img"
              />
              <div class="swiper-step">
                <div class="swiper-step-title">Step 2：</div>
                <div class="swiper-step-info">• 完成「身分驗證」</div>
                <div class="swiper-step-next"></div>
                <div class="swiper-step-info">• 完成其他資料提供</div>
                <div class="swiper-step-next"></div>
                <div class="swiper-step-info">• 確認送出審核</div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="swiper-left swiper-text-1">
                <div class="swiper-text-1">
                  <div>全台第一</div>
                  <div>AI自動審核風控系統</div>
                  <div class="swiper-text-3">
                    <div>•運用金融科技</div>
                    <div>•快速辨識驗證資訊</div>
                    <div>•提供最安全無人化</div>
                    <div>•FinTech借貸服務</div>
                  </div>
                </div>
              </div>
              <img
                src="@/asset/images/workloan/step-3-1.png"
                class="step-img"
              />
              <img
                src="@/asset/images/workloan/step-3-2.png"
                class="step-img"
              />
              <div class="swiper-step">
                <div class="swiper-step-title">Step 3：</div>
                <div class="swiper-step-info">• 取得核准額度</div>
                <div class="swiper-step-next"></div>
                <div class="swiper-step-info">• 選擇借款金額</div>
                <div class="swiper-step-next"></div>
                <div class="swiper-step-info">• 線上簽約</div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="swiper-left">
                <div class="swiper-text-1">超速資金媒合效率</div>
                <div class="swiper-slogan">
                  <div>最快<span class="em">1</span>小時</div>
                  <div>立馬到帳！</div>
                </div>
              </div>
              <img
                src="@/asset/images/workloan/step-4-1.png"
                class="step-img"
              />
              <img
                src="@/asset/images/workloan/step-4-2.png"
                class="step-img"
              />
              <div class="swiper-step">
                <div class="swiper-step-title">Step 4：</div>
                <div class="swiper-step-info">• 案件上架</div>
                <div class="swiper-step-next"></div>
                <div class="swiper-step-info">• 快速媒合</div>
                <div class="swiper-step-next"></div>
                <div class="swiper-step-info">• 立即到帳</div>
              </div>
            </div>
          </div>
          <!-- If we need pagination -->
          <div class="swiper-pagination"></div>
          <!-- If we need navigation buttons -->
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </div>
    </div>
    <div class="product-feature">
      <div class="block-content">
        <h1 class="h1">上班族產品特色</h1>
        <h2 class="h2">方便、安全、隱私，您申辦貸款的首選</h2>
        <div class="product-feature-grid">
          <div
            class="product-feature-item"
            data-aos="fade-right"
            data-aos-duration="500"
          >
            <div class="product-feature-title">即時審核，快速媒合放款</div>
            <div class="product-feature-text">
              <div>系統自動化審核加快申貸的速度，5分鐘申貸</div>
              <div>10分鐘核准 最快1小時放款！</div>
            </div>
          </div>
          <div
            class="product-feature-item"
            data-aos="fade-right"
            data-aos-duration="500"
            data-aos-offset="300"
          >
            <div class="product-feature-title">全線上無人化</div>
            <div class="product-feature-text">
              運用AI整合會員資料進行大數據分析，自動線上完成徵信、授信、甚至續約程序，操作方便簡單。
            </div>
          </div>
          <div
            class="product-feature-item"
            data-aos="fade-right"
            data-aos-duration="500"
            data-aos-offset="200"
          >
            <div class="product-feature-title">隱私無照會</div>
            <div class="product-feature-text">
              申貸全程無人干擾，更不會接到任何業務員的照會電話，也不會主動聯絡借款人及其緊急聯絡人。
            </div>
          </div>
          <div
            class="product-feature-item product-feature-img"
            data-aos="flip-down"
            data-aos-duration="500"
          >
            <img
              src="@/asset/images/workloan/product-feature-1.png"
              class="img-fluid"
            />
          </div>
          <div
            class="product-feature-item"
            data-aos="fade-left"
            data-aos-duration="500"
          >
            <div class="product-feature-title">聯徵不留紀錄</div>
            <div class="product-feature-text">
              不會留下任何貸款相關紀錄，不影響未來信用狀況，也不佔任何銀行額度。
            </div>
          </div>
          <div
            class="product-feature-item"
            data-aos="fade-left"
            data-aos-duration="500"
            data-aos-delay="200"
            data-aos-offset="100"
          >
            <div class="product-feature-title">費率單純透明</div>
            <div class="product-feature-text">
              只收取一次性成交手續費，且費率以及每月需還本息金額公開透明，不用擔心被收取其他費用。
            </div>
          </div>
          <div
            class="product-feature-item"
            data-aos="fade-left"
            data-aos-duration="500"
            data-aos-delay="200"
            data-aos-offset="100"
          >
            <div class="product-feature-title">金融機構合作</div>
            <div class="product-feature-text">
              在會員用戶逐漸成長的情況下，普匯專業優質的名聲，吸引許多金融機構相邀合作，包含「新光銀行」、「上海商銀」等，共同為台灣金融科技發展貢獻一份力。
            </div>
          </div>
        </div>
        <div class="row no-gutters justify-content-center">
          <div class="slogan">假日放款全年無休</div>
        </div>
        <div class="row no-gutters justify-content-center mt-5">
          <a href="/borrowLink" target="_blank" rel="noopener noreferrer">
            <button class="btn btn-download">取得資金</button>
          </a>
        </div>
      </div>
    </div>
    <div class="case-share">
      <div class="block-content">
        <h1 class="h1">優質案例分享</h1>
        <h2 class="h2">
          <div class="d-md-block d-none">
            已有數萬位使用者取得資金，您還在等什麼？
          </div>
          <div class="d-md-none d-block">已有數萬位使用者取得資金</div>
          <div class="d-md-none d-block">您還在等什麼？</div>
        </h2>
        <div class="row no-gutters swiper-row">
          <div class="swiper">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
              <!-- Slides -->
              <div class="swiper-slide">
                <div class="case-container">
                  <alesis-project v-bind="cases[0]"></alesis-project>
                </div>
                <div class="case-container">
                  <alesis-project v-bind="cases[1]"></alesis-project>
                </div>
                <div class="case-container">
                  <alesis-project v-bind="cases[2]"></alesis-project>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="case-container">
                  <alesis-project v-bind="cases[3]"></alesis-project>
                </div>
                <div class="case-container">
                  <alesis-project v-bind="cases[4]"></alesis-project>
                </div>
                <div class="case-container">
                  <alesis-project v-bind="cases[5]"></alesis-project>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="case-container">
                  <alesis-project v-bind="cases[6]"></alesis-project>
                </div>
                <div class="case-container">
                  <alesis-project v-bind="cases[7]"></alesis-project>
                </div>
                <div class="case-container">
                  <alesis-project v-bind="cases[8]"></alesis-project>
                </div>
              </div>
            </div>
            <!-- If we need pagination -->
            <div class="swiper-pagination"></div>
            <!-- If we need navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="experience-share">
      <div class="block-content">
        <h1 class="h1">上傳分享</h1>
        <div class="row no-gutters form-row">
          <div class="col-md-5 col-12">
            <img
              src="@/asset/images/workloan/exp-share.png"
              class="img-fluid"
            />
          </div>
          <div class="col-md col-12">
            <div class="form-title">
              <div class="d-md-block d-none">立即上傳分享</div>
              <div class="d-md-none d-block">立即上傳分享</div>
              <div class="d-md-none d-block">您在普匯的借款體驗！</div>
            </div>
            <div class="form-text">
              <div class="d-md-block d-none">
                下次申貸時系統將為您的信用分數加分，有助於提高您的核准額度
              </div>
              <div class="d-md-none d-block">
                下次申貸時系統將為您的信用分數加分
              </div>
              <div class="d-md-none d-block">有助於提高您的核准額度</div>
            </div>
            <div class="form-hint">
              <div class="d-md-block d-none">
                *平台不會公開借款人個人基本資料，請借款人安心上傳
              </div>
              <div class="d-md-none d-block">
                *平台不會公開借款人個人基本資料
              </div>
              <div class="d-md-none d-block">請借款人安心上傳</div>
            </div>
            <form ref="shareForm" @submit.prevent="doShare">
              <div class="row no-gutters mb-3">
                <div class="form-item-text">使用者編號：</div>
                <input
                  type="text"
                  name="user_id"
                  v-model="userId"
                  :disabled="userIdDisabled"
                  class="form-item-input col"
                  required
                />
              </div>
              <div class="row no-gutters mb-3">
                <div class="form-item-text">借款體驗：</div>
                <textarea
                  name="experience"
                  class="form-item-input col form-textarea"
                  required
                ></textarea>
              </div>
              <div class="text-center">
                <button class="btn btn-form-submit col">送出</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="experience">
      <div class="block-content">
        <h1 class="h1">用戶體驗</h1>
        <h2 class="h2">我們提供最溫暖的金融科技服務</h2>
        <div class="swiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <div class="row no-gutters user-comment">
                <div class="col-auto">
                  <div class="card comment-card">
                    <div class="card-body">
                      <div class="card-title star">
                        <i class="fa fa-star" v-for="i in 5" :key="i"></i>
                      </div>
                      <div class="card-text">
                        很棒的平台，是一個不錯的投資管道，創造穩定的現金流，不用怕被股市震盪影響心情。
                      </div>
                      <div class="mt-2 d-flex align-items-center">
                        <div class="portrait mr-2">
                          <img src="@/asset/images/ue-1.png" alt="" />
                        </div>
                        <span> 王先生 </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-auto mx-5 d-sm-block d-none">
                  <div class="card comment-card">
                    <div class="card-body">
                      <div class="card-title star">
                        <i class="fa fa-star" v-for="i in 5" :key="i"></i>
                      </div>
                      <div class="card-text">
                        很棒的新創公司，雖然成立時間不長，但是在選校園大使期間，也感受到這間公司的氛圍是很棒的，是歡樂的。
                      </div>
                      <div class="mt-2 d-flex align-items-center">
                        <div class="portrait mr-2">
                          <img src="@/asset/images/ue-2.png" alt="" />
                        </div>
                        <span> 陳小姐 </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-auto d-sm-block d-none">
                  <div class="card comment-card">
                    <div class="card-body">
                      <div class="card-title star">
                        <i class="fa fa-star" v-for="i in 5" :key="i"></i>
                      </div>
                      <div class="card-text">
                        對於實習生培訓企劃用心且真誠，老闆會針對提案親自給予回饋，在乎並深耕於教育與人才養成
                      </div>
                      <div class="mt-2 d-flex align-items-center">
                        <div class="portrait mr-2">
                          <img src="@/asset/images/ue-3.png" alt="" />
                        </div>
                        <span> 林先生 </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- 導覽按鈕 -->
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import featureCard from '../component/index/featureCard'
import AlesisProject from "../component/alesis/AlesisProject";
import axios from 'axios'
import 'swiper/swiper.scss'
import 'swiper/components/navigation/navigation.min.css'
import 'swiper/components/pagination/pagination.min.css'
import SwiperCore, { Navigation, Pagination } from 'swiper/core'
import Axios from 'axios';
export default {
  components: {
    featureCard,
    AlesisProject
  },
  data() {
    return {
      cases: [],
      userId: '',
      userIdDisabled: false,
      showCheckList: false
    }
  },
  mounted() {
    SwiperCore.use([Navigation, Pagination])
    AOS.init({ disable: 'mobile' })
    const swiper = new Swiper('.swiper', {
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
      },
    })
    const swiper2 = new Swiper('.banner-swiper', {
      pagination: {
        el: '.swiper-pagination',
      },
      loop: true,
      autoplay: {
        delay: 5000,
      },
    })
    axios.post(`/getCase`, { status: 10, product_id: 3 }).then((res) => {
      this.cases = res.data.slice(0, 8)
    }).catch((error) => {
      console.error('getCase 發生錯誤，請稍後再試');
    });
    // check login
    const userData = sessionStorage.getItem('userData')
    if (userData) {
      this.userId = JSON.parse(userData)?.id
      if (this.userId) {
        this.userIdDisabled = true
      }
    }
  },
  methods: {
    doContact() {
      const data = new FormData(this.$refs.contactForm)
      Axios({
        url: '/api/v1/work-loan/contact',
        method: 'POST',
        data,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Accept': 'application/json',
        }
      }).then(({ data }) => {
        if (data.success) {
          this.$refs.contactForm.reset()
          alert(data.msg)
        }
      }).catch(({ response }) => {
        const d = response.data.data
        let errorMsg = ''
        for (const item in d) {
          errorMsg += d[item].toString() + '\n'

        }
        alert(errorMsg)
      })
    },
    doShare() {
      const data = new FormData(this.$refs.shareForm)
      Axios({
        url: '/api/v1/work-loan/share',
        method: 'POST',
        data,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Accept': 'application/json',
        }
      }).then(({ data }) => {
        if (data.success) {
          this.$refs.shareForm.reset()
          alert(data.msg)
        }
      }).catch(({ response }) => {
        const d = response.data.data
        let errorMsg = ''
        for (const item in d) {
          errorMsg += d[item].toString() + '\n'

        }
        alert(errorMsg)
      })
    }
  },
}
</script>
<style lang="scss">
.radio-custom-work {
  display: contents;
  label {
    display: inline-block;
    position: relative;
    padding-left: 23px;
    cursor: pointer;
    width: auto;
    margin-right: 20px;
    line-height: 29px;
  }

  input {
    height: 1px;
    width: 1px;
    opacity: 0;
  }

  .work-input-outside {
    display: inline-block;
    position: absolute;
    left: 0%;
    top: 30%;
    margin-top: 0;
    width: 22px;
    height: 22px;
    border: 2px solid #036eb7;
    border-radius: 12px;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    background: #fff;
  }

  .work-input-inside {
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    display: inline-block;
    border-radius: 12px;
    width: 12px;
    height: 12px;
    background: #036eb7;
    left: 3px;
    top: 3px;
    -webkit-transform: scale(0, 0);
    transform: scale(0, 0);
  }
  .no-transforms .work-input-inside {
    left: auto;
    top: auto;
    width: 0;
    height: 0;
  }

  input:checked + .work-input-outside .work-input-inside {
    -webkit-animation: radio-select 0.1s linear;
    animation: radio-select 0.1s linear;
    -webkit-transform: scale(1, 1);
    transform: scale(1, 1);
  }
  .no-transforms input:checked + .work-input-outside .work-input-inside {
    width: 10px;
    height: 10px;
  }
}
@media screen and (max-width: 767px) {
  .radio-custom-work label {
    margin-right: 10px;
  }
  .radio-custom-work .work-input-outside {
    margin-top: -6px;
    width: 22px;
    height: 22px;
  }
  .radio-custom-work .work-input-inside {
    width: 16px;
    height: 16px;
    top: 1px;
    left: 1px;
  }
}
</style>
<style lang="scss" scoped>
$color--primary: #036eb7;
$color__text--primary: #023d64;
$color__background--primary: #f3f9fc;
$color--primary: #036eb7;
$color__text--primary: #023d64;
$color__background--primary: #f3f9fc;
.h1 {
  text-align: center;
  font-style: normal;
  font-weight: 700;
  font-size: 56px;
  line-height: 1.4;
}
.h2 {
  text-align: center;
  font-style: normal;
  font-weight: 500;
  font-size: 40px;
  line-height: 1.4;
}
.block-content {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
}
.form-item-text {
  font-style: normal;
  font-weight: 500;
  font-size: 30px;
  line-height: 1.2;
  min-width: 225px;
  color: $color__text--primary;
}
.form-item-input {
  padding: 4px 12px;
  font-size: 24px;
  line-height: 1.2;
  border: 2px solid $color--primary;
  border-radius: 12px;
}
.form-item-label {
  font-size: 24px;
  line-height: 1.2;
  color: $color__text--primary;
  opacity: 0.7;
  width: fit-content;
  margin-right: 15px;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
.features {
  padding: 100px;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
  .feature-grid {
    display: grid;
    margin: 84px auto 0;
    grid-template-columns: repeat(4, 260px);
    gap: 32px;
    .card {
      width: 100%;
      border: none;
      box-shadow: 0px 4px 12px rgba(21, 91, 153, 0.16);
      border-radius: 16px;
      .card-body {
        padding: 50px 32px;
      }
      .card-cover {
        display: flex;
        width: 84px;
        margin: 0 auto 16px;
        padding: 14px;
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
    }
  }
  .feature-suit {
    margin: 32px 0 120px;
    width: 100%;
    max-width: 1136px;
    padding: 34px 31px;
    background: linear-gradient(90deg, #3781c7 0%, #74bbe4 100%);
    border-radius: 16px;
    display: grid;
    grid-template-columns: auto 1fr auto;
    grid-template-rows: 1fr 1fr;
    gap: 16px;
    .suit-title-container {
      grid-area: 1 / 1 / 3 / 2;
    }
    .suit-title {
      font-style: normal;
      font-weight: 700;
      font-size: 48px;
      line-height: 1.4;
      height: 70px;
      color: #ffffff;
      border-right: 3px solid $color--primary;
      padding-right: 48px;
    }
    .suit-item {
      font-style: normal;
      font-weight: 500;
      font-size: 32px;
      line-height: 1.4;
      color: #ffffff;
    }
  }
  .feature-user {
    display: grid;
    gap: 46.5px;
    margin-top: 150px;
    grid-template-columns: repeat(4, 1fr);
    .card {
      width: 100%;
      border: none;
      background: linear-gradient(90deg, #3781c7 0%, #74bbe4 100%);
      border-radius: 16px;
      position: relative;
      .bg-text {
        position: absolute;
        z-index: 1;
        font-family: 'Arial';
        font-style: normal;
        font-weight: 700;
        font-size: 50px;
        line-height: 1.4;
        letter-spacing: 0.1em;
        color: #60a2d4;
        opacity: 0.64;
        top: 120px;
        left: -120px;
        transform: rotate(90deg);
      }
      .card-body {
        z-index: 2;
        padding-top: 150px;
        height: 400px;
        .portrait {
          position: absolute;
          top: -95px;
          right: 0;
          z-index: -1;
        }
        .portrait-info {
          font-style: normal;
          font-weight: 700;
          font-size: 24px;
          line-height: 1.4;
          color: $color--primary;
          padding: 4px 16px;
          background: #ffffff;
          width: fit-content;
        }
        .card-text {
          margin-top: 18px;
          font-style: normal;
          font-weight: 500;
          font-size: 16px;
          line-height: 2;
          color: #ffffff;
        }
      }
    }
  }
}
.purpose {
  padding: 120px 0;
  background: url('~images/workloan/purpose-bg.png'),
    $color__background--primary;
  background-repeat: no-repeat;
  background-position: center 194px;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
  .purpose-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(320px, 1fr));
    gap: 16px;
    height: 450px;

    .purpose-col {
      padding: 15px 15px 0 15px;
    }
    .purpose-img {
      &-1 {
        margin-top: 118px;
      }
      &-2 {
        margin-top: 156px;
      }
      &-3 {
        margin-top: 82px;
      }
    }
    .purpose-col-title {
      margin-top: -15px;
      font-style: normal;
      font-weight: 700;
      font-size: 20px;
      line-height: 1.4;
      text-align: center;
      color: $color--primary;
    }
    .purpose-col-text {
      margin-top: 6px;
      font-style: normal;
      font-weight: 400;
      font-size: 18px;
      line-height: 1.4;
      letter-spacing: 0.05em;
      text-align: center;
      color: $color__text--primary;
    }
  }
  .form-title {
    margin: 84px 0 120px;
    font-style: normal;
    font-weight: 700;
    font-size: 48px;
    line-height: 1.4;
    text-align: center;
    color: $color__text--primary;
  }
  .form-cover {
    position: relative;
    top: -120px;
  }
  .btn-form-submit {
    font-style: normal;
    font-weight: 500;
    font-size: 30px;
    line-height: 1.4;
    color: #ffffff;
    background-color: $color--primary;
    border-radius: 12px;
  }
}
.diagram {
  padding: 64px 0;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
  .swiper {
    margin-top: 32px;
  }
  .swiper-slide {
    display: grid;
    gap: 20px;
    grid-template-columns: 1fr auto auto 1fr;
    align-items: center;
    padding-bottom: 40px;
  }
  .swiper-left {
    justify-self: end;
    .swiper-text-info {
      margin: 8px 0 8px 18px;
    }
  }
  .swiper-text {
    &-1 {
      font-style: normal;
      font-weight: 700;
      font-size: 24px;
      line-height: 1.4;
      color: $color__text--primary;
      margin-bottom: 4px;
    }
    &-2 {
      font-style: normal;
      font-weight: 700;
      font-size: 24px;
      line-height: 1.4;
      color: $color--primary;
    }
    &-3 {
      font-style: normal;
      font-weight: 700;
      font-size: 20px;
      line-height: 1.4;
      color: $color--primary;
    }
    &-link {
      margin-left: 18px;
      font-style: normal;
      font-weight: 700;
      font-size: 16px;
      line-height: 1.4;
      color: $color--primary;
      text-decoration-line: underline;
    }
  }
  .swiper {
    &-step-title {
      font-style: normal;
      font-weight: 700;
      font-size: 24px;
      line-height: 1.4;
      color: $color--primary;
      margin-bottom: 16px;
    }
    &-step-next {
      background-image: url('~images/workloan/step-next.png');
      width: 16px;
      height: 22px;
      margin: 4px 0;
    }
    &-step-info {
      font-style: normal;
      font-weight: 400;
      font-size: 16px;
      line-height: 1.4;
      color: #393939;
    }
    &-slogan {
      font-style: normal;
      font-weight: 700;
      font-size: 30px;
      line-height: 1.4;
      color: #036eb7;
      .em {
        font-family: 'Arial';
        font-style: italic;
        font-weight: 700;
        font-size: 64px;
        line-height: 1.4;
        color: #f29500;
      }
    }
  }
}
.product-feature {
  padding: 64px 0;
  background: $color__background--primary;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
  .product-feature-grid {
    display: grid;
    margin: 85px 0 90px;
    grid-template-columns: 1fr 380px 1fr;
    grid-template-rows: 1fr 1fr 1fr;
    gap: 12px;
    .product-feature {
      &-img {
        grid-area: 1 / 2 / 4 / 3;
        position: relative;
        top: -35px;
      }
      &-title {
        display: flex;
        align-items: center;
        font-style: normal;
        font-weight: 700;
        font-size: 36px;
        line-height: 1.4;
        color: $color__text--primary;
        &::before {
          content: '';
          display: block;
          width: 16px;
          height: 16px;
          border-radius: 50%;
          margin-right: 16px;
          background: $color--primary;
        }
      }
      &-text {
        margin: 12px 0 0 36px;
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 1.5;
        max-width: 360px;
        color: $color__text--primary;
        opacity: 0.56;
      }
    }
  }
  .slogan {
    font-style: normal;
    font-weight: 700;
    font-size: 48px;
    line-height: 1.4;
    color: #f29500;
  }
  .btn-download {
    border: 2px solid $color--primary;
    border-radius: 10px;
    color: $color--primary;
    font-style: normal;
    font-weight: 500;
    font-size: 28px;
    line-height: 1.4;
    padding: 9px 60px;
  }
}
.case-share {
  padding: 64px 0;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
  .swiper {
    margin-top: 85px;
  }
  .swiper-slide {
    display: grid;
    padding-bottom: 40px;
    gap: 15px;
    grid-template-columns: auto auto auto;
    justify-content: center;
  }
  .case-container {
    transform: scale(0.95);
  }
}
.experience-share {
  padding: 64px 0;
  background: $color__background--primary;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
  .form-row {
    margin-top: 100px;
  }
  .form-title {
    font-style: normal;
    font-weight: 700;
    font-size: 36px;
    line-height: 1.4;
    color: $color__text--primary;
  }
  .form-text {
    margin-top: 12px;
    font-style: normal;
    font-weight: 500;
    font-size: 28px;
    line-height: 1.4;
    color: $color--primary;
  }
  .form-hint {
    margin: 8px 0 24px;
    font-style: normal;
    font-weight: 500;
    font-size: 16px;
    line-height: 1.4;
    color: #f29500;
  }
  .form-item-text {
    min-width: 180px;
  }
  .form-textarea {
    height: 200px;
  }
  .btn-form-submit {
    background: $color--primary;
    font-style: normal;
    font-weight: 500;
    font-size: 28px;
    color: #fff;
    padding: 4px 160px;
    border-radius: 12px;
    width: fit-content;
  }
}
.experience {
  padding: 64px 0;
  background: url('~images/workloan/exp-bg.png'),
    linear-gradient(90deg, #a8b3c8 0%, #d2d8e6 50%);
  background-repeat: no-repeat;
  background-position-x: 80%;
  .h1 {
    color: #fff;
  }
  .h2 {
    font-size: 24px;
    color: #393939;
    margin-top: 12px;
  }
  .swiper-row {
    margin-top: 100px;
  }
  .user-comment {
    margin-top: 100px;
    justify-content: center;
    .comment-card {
      width: 360px;
      padding: 10px;
      .star {
        color: #f3cd03;
      }
      .portrait {
        width: 56px;
        border-radius: 50%;
      }
    }
  }
}
@media screen and (max-width: 1720px) {
  .experience {
    background-position-x: right;
  }
}
@media screen and (max-width: 767px) {
  .h1 {
    font-size: 28px;
  }
  .h2 {
    font-size: 18px;
  }
  .block-content {
    padding: 0 15px;
  }
  .form-item-text {
    font-size: 16px;
    min-width: 125px;
  }
  .form-item-input {
    padding: 4px 12px;
    font-size: 14px;
  }
  .form-item-label {
    font-size: 14px;
    margin-right: 15px;
  }
  .features {
    padding: 36px 0;
    .h2 {
      margin-top: 8px;
    }
    .feature-grid {
      display: grid;
      margin: 24px auto 0;
      grid-template-columns: 1fr 1fr;
      gap: 32px;
      .card {
        width: 100%;
        border: none;
        box-shadow: 0px 4px 12px rgba(21, 91, 153, 0.16);
        border-radius: 16px;
        .card-body {
          padding: 12px;
        }
        .card-cover {
          width: 60px;
          margin: 0 auto 10px;
          padding: 9px;
        }
        .card-title {
          font-size: 18px;
        }
        .card-divider {
          width: 30px;
        }
      }
    }
    .feature-suit {
      margin: 15px 0 50px;
      padding: 20px 25px;
      display: grid;
      grid-template-columns: 1fr;
      gap: 8px;
      .suit-title {
        text-align: center;
        font-size: 28px;
        line-height: 1.4;
        height: auto;
        border-right: none;
        padding-right: 0;
      }
      .suit-underline {
        width: 40px;
        margin: 0 auto;
        text-align: center;
        border-bottom: 3px solid #036eb7;
      }
      .suit-item {
        font-size: 18px;
        color: #ffffff;
      }
    }
    .feature-user {
      display: grid;
      gap: 60px;
      margin-top: 110px;
      grid-template-columns: 1fr;
      .card {
        width: 100%;
        border: none;
        background: linear-gradient(90deg, #3781c7 0%, #74bbe4 100%);
        border-radius: 16px;
        position: relative;
        .bg-text {
          position: absolute;
          z-index: 1;
          font-size: 46px;
          top: 120px;
          left: -105px;
          transform: rotate(90deg);
        }
        .card-body {
          z-index: 2;
          padding-top: 120px;
          height: 365px;
          .portrait {
            position: absolute;
            top: -95px;
            right: -20px;
            z-index: -1;
            transform: scale(0.88);
          }
          .portrait-info {
            font-style: normal;
            font-weight: 700;
            font-size: 24px;
            line-height: 1.4;
            color: $color--primary;
            padding: 4px 16px;
            background: #ffffff;
            width: fit-content;
          }
          .card-text {
            margin-top: 18px;
            font-style: normal;
            font-weight: 500;
            font-size: 16px;
            line-height: 2;
            color: #ffffff;
          }
        }
      }
    }
  }
  .purpose {
    padding: 36px 0;
    background: $color__background--primary;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      color: $color--primary;
      opacity: 0.64;
      margin-top: 24px;
    }
    .purpose-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 16px;
      height: auto;
      .purpose-col {
        padding: 15px 15px 0 15px;
      }
      .purpose-img {
        &-1 {
          margin-top: 0;
        }
        &-2 {
          margin-top: 0;
        }
        &-3 {
          margin-top: 0;
        }
      }
      .purpose-col-title {
        margin-top: -5px;
        font-size: 18px;
      }
      .purpose-col-text {
        margin-top: 6px;
        font-size: 16px;
      }
    }
    .form-title {
      margin: 32px 0 24px;
      font-size: 24px;
    }
    .form-cover {
      position: relative;
      top: 0;
    }
    .btn-form-submit {
      font-style: normal;
      font-weight: 500;
      font-size: 30px;
      line-height: 1.4;
      color: #ffffff;
      background-color: $color--primary;
      border-radius: 12px;
    }
  }
  .diagram {
    padding: 32px 0;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      color: $color--primary;
      opacity: 0.64;
      margin-top: 24px;
    }
    .swiper {
      margin-top: 32px;
    }
    .swiper-slide {
      display: grid;
      gap: 20px;
      grid-template-columns: 1fr 1fr;
      align-items: center;
      padding-bottom: 40px;
    }
    .swiper-left {
      justify-self: end;
      .swiper-text-info {
        margin: 8px 0 8px 18px;
      }
    }
    .swiper-text {
      &-1 {
        font-size: 18px;
      }
      &-2 {
        font-style: normal;
        font-weight: 700;
        font-size: 14px;
        line-height: 1.4;
        color: $color--primary;
      }
      &-3 {
        font-style: normal;
        font-weight: 700;
        font-size: 12px;
        line-height: 1.4;
        color: $color--primary;
      }
      &-link {
        margin-left: 18px;
        font-style: normal;
        font-weight: 700;
        font-size: 12px;
        line-height: 1.4;
        color: $color--primary;
        text-decoration-line: underline;
      }
    }
    .swiper {
      &-step-title {
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        line-height: 1.4;
        color: $color--primary;
        margin-bottom: 16px;
      }
      &-step-next {
        background-image: url('~images/workloan/step-next.png');
        width: 16px;
        height: 22px;
        margin: 4px 0;
      }
      &-step-info {
        font-style: normal;
        font-weight: 400;
        font-size: 12px;
        line-height: 1.4;
        color: #393939;
      }
      &-slogan {
        font-style: normal;
        font-weight: 700;
        font-size: 20px;
        line-height: 1.4;
        color: #036eb7;
        .em {
          font-family: 'Arial';
          font-style: italic;
          font-weight: 700;
          font-size: 40px;
          line-height: 1.4;
          color: #f29500;
        }
      }
      .step-img {
        width: 148px;
      }
    }
  }
  .product-feature {
    padding: 32px 0;
    .h2 {
      margin-top: 8px;
    }
    .product-feature-grid {
      display: grid;
      margin: 24px 0;
      grid-template-columns: 1fr;
      // grid-template-rows: 1fr 1fr 1fr;
      gap: 18x;
      .product-feature {
        &-img {
          display: none;
        }
        &-title {
          display: flex;
          align-items: center;
          font-size: 20px;
          &::before {
            content: '';
            width: 12px;
            height: 12px;
            margin-right: 12px;
          }
        }
        &-text {
          margin: 12px 0;
          font-size: 14px;
        }
      }
    }
    .slogan {
      font-size: 24px;
    }
    .btn-download {
      font-size: 16px;
      line-height: 1.4;
      padding: 6px 40px;
    }
  }
  .case-share {
    padding: 32px 0;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      margin-top: 8px;
    }
    .swiper {
      margin-top: 36px;
    }
    .swiper-slide {
      display: grid;
      padding-bottom: 40px;
      gap: 15px;
      grid-template-columns: auto;
      justify-content: center;
    }
    .case-container {
      transform: scale(0.75);
    }
  }
  .experience-share {
    padding: 32px 0;
    .h2 {
      margin-top: 8px;
    }
    .form-row {
      margin-top: 24px;
      gap: 32px;
      flex-direction: column-reverse;
    }
    .form-title {
      font-size: 24px;
      text-align: center;
    }
    .form-text {
      margin-top: 25px;
      font-size: 18px;
      text-align: center;
    }
    .form-hint {
      margin: 8px 0 24px;
      font-size: 14px;
      text-align: center;
    }
    .form-item-text {
      min-width: 95px;
    }
    .btn-form-submit {
      font-size: 16px;
      padding: 4px;
      width: 100%;
    }
  }
  .experience {
    padding: 64px 0;
    background: linear-gradient(90deg, #a8b3c8 0%, #d2d8e6 50%);
    .h1 {
      color: #fff;
    }
    .h2 {
      font-size: 24px;
      color: #393939;
      margin-top: 12px;
    }
    .swiper-row {
      margin-top: 100px;
    }
    .user-comment {
      margin-top: 30px;
      justify-content: center;
      flex-direction: column;
      .comment-card {
        width: 360px;
        padding: 10px;
        .star {
          color: #f3cd03;
        }
        .portrait {
          width: 56px;
          border-radius: 50%;
        }
      }
    }
  }
}
</style>
