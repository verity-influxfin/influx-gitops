<template>
  <div class="work-loan">
    <div class="banner-swiper position-relative">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <img
            src="@/asset/images/houseloan/houseloan-banner.jpg"
            alt="房產消費貸"
            class="img-fluid d-sm-block d-none w-100"
          />
          <img
            src="@/asset/images/houseloan/houseloan-banner-1-phone.jpg"
            alt="房產消費貸"
            class="img-fluid d-sm-none d-block w-100"
          />
        </div>
        <div class="swiper-slide">
          <img
            src="@/asset/images/houseloan/houseloan-banner-2.jpg"
            alt="購房貸"
            class="img-fluid d-sm-block d-none w-100"
          />
          <img
            src="@/asset/images/houseloan/houseloan-banner-2-phone.jpg"
            alt="購房貸"
            class="img-fluid d-sm-none d-block w-100"
          />
        </div>
        <div class="swiper-slide">
          <img
            src="@/asset/images/houseloan/houseloan-banner-3.jpg"
            alt="房屋裝修款"
            class="img-fluid d-sm-block d-none w-100"
          />
          <img
            src="@/asset/images/houseloan/houseloan-banner-3-phone.jpg"
            alt="房屋裝修款"
            class="img-fluid d-sm-none d-block w-100"
          />
        </div>
        <div class="swiper-slide">
          <img
            src="@/asset/images/houseloan/houseloan-banner-4.jpg"
            alt="添購傢俱家電"
            class="img-fluid d-sm-block d-none w-100"
          />
          <img
            src="@/asset/images/houseloan/houseloan-banner-4-phone.jpg"
            alt="添購傢俱家電"
            class="img-fluid d-sm-none d-block w-100"
          />
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
    <div class="features">
      <div class="block-content">
        <div class="h1" data-aos="fade-right" data-aos-duration="800">
          隨意貸 輕鬆還
        </div>
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
                <div class="card-title">低手續費3%</div>
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
                <div class="card-title">最高可借100萬</div>
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
                <div class="card-title">借款最長36期</div>
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
                <div class="card-title">輕鬆本息攤還</div>
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
            <div class="suit-item">•18~45歲</div>
            <div class="suit-item">•信用良好（無催收/呆帳紀錄）</div>
            <div class="suit-item">•穩定收入之公民</div>
            <div class="suit-item">•具買賣、裝修合約及不動產</div>
          </div>
        </div>
      </div>
    </div>
    <div class="house-suit">
      <div class="block-content">
        <houseSuit />
      </div>
    </div>
    <div class="diagram">
      <div class="block-content">
        <div class="h1">一支手機</div>
        <h2 class="h2">助你幸福成家 資金的事交給普匯</h2>

        <div class="diagram-swiper-full">
          <div class="swiper">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
              <!-- Slides -->

              <!-- step-1 -->
              <div class="swiper-slide">
                <div class="swiper-left swiper-text-1">
                  <div>量身製定資金需求</div>
                  <div>提供您更滿意的貸款</div>
                </div>
                <img
                  src="@/asset/images/houseloan/step-1-1.jpg"
                  class="step-img"
                />
                <img
                  src="@/asset/images/houseloan/step-1-2.jpg"
                  class="step-img"
                />
                <div class="swiper-step">
                  <div class="swiper-step-title">Step 1：</div>
                  <div class="swiper-step-info">• 選擇「申貸專案」</div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">• 選擇「借款金額與期數」</div>
                </div>
              </div>

              <!-- step-2 -->
              <div class="swiper-slide">
                <div class="swiper-left">
                  <div class="swiper-text-1">需要準備什麼資料？</div>
                  <button
                    class="swiper-text-2 btn btn-link"
                    @click="showCheckLists.a = !showCheckLists.a"
                  >
                    Check list
                  </button>
                  <transition name="fade">
                    <div class="check-list-content" v-show="showCheckLists.a">
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
                  src="@/asset/images/houseloan/step-2-1.jpg"
                  class="step-img"
                />
                <img
                  src="@/asset/images/houseloan/step-2-2.jpg"
                  class="step-img"
                />
                <div class="swiper-step">
                  <div class="swiper-step-title">Step 2：</div>
                  <div class="swiper-step-info">• 完成「身分驗證」</div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">• 完成其他資料提供</div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">
                    • 上傳「聯合徵信報告所需資料」
                  </div>
                </div>
              </div>

              <!-- step-3 -->
              <div class="swiper-slide">
                <div class="swiper-left">
                  <div class="swiper-text-1">需要準備什麼資料？</div>
                  <div>購房貸</div>
                  <button
                    class="swiper-text-2 btn btn-link"
                    @click="
                      () => {
                        showCheckLists.b = !showCheckLists.b
                        if (showCheckLists.b) {
                          showStep2Image = 1
                          showCheckLists.c = false
                          showCheckLists.d = false
                        }
                      }
                    "
                  >
                    Check list
                  </button>
                  <transition name="fade">
                    <div
                      class="check-list-content mb-3"
                      v-show="showCheckLists.b"
                    >
                      <div class="swiper-text-3">•房屋所有權狀</div>
                      <div class="swiper-text-3">•購房合約</div>
                      <div class="swiper-text-3">•購房發票(選填)</div>
                      <div class="swiper-text-3">•入屋現勘/遠端視訊</div>
                    </div>
                  </transition>
                  <div>房屋裝修款</div>
                  <button
                    class="swiper-text-2 btn btn-link"
                    @click="
                      () => {
                        showCheckLists.c = !showCheckLists.c
                        if (showCheckLists.c) {
                          showStep2Image = 2
                          showCheckLists.b = false
                          showCheckLists.d = false
                        }
                      }
                    "
                  >
                    Check list
                  </button>
                  <transition name="fade">
                    <div
                      class="check-list-content mb-3"
                      v-show="showCheckLists.c"
                    >
                      <div class="swiper-text-3">•房屋所有權狀</div>
                      <div class="swiper-text-3">•裝修合約</div>
                      <div class="swiper-text-3">•裝修發票(選填)</div>
                      <div class="swiper-text-3">•入屋現勘/遠端視訊</div>
                    </div>
                  </transition>
                  <div>添購傢俱家電</div>
                  <button
                    class="swiper-text-2 btn btn-link"
                    @click="
                      () => {
                        showCheckLists.d = !showCheckLists.d
                        if (showCheckLists.d) {
                          showStep2Image = 3
                          showCheckLists.b = false
                          showCheckLists.c = false
                        }
                      }
                    "
                  >
                    Check list
                  </button>
                  <transition name="fade">
                    <div class="check-list-content" v-show="showCheckLists.d">
                      <div class="swiper-text-3">•房屋所有權狀</div>
                      <div class="swiper-text-3">•傢俱家電合約或發票收據</div>
                      <div class="swiper-text-3">•入屋現勘/遠端視訊</div>
                    </div>
                  </transition>
                </div>

                <template id="img-group">
                  <img
                    v-show="showStep2Image == 1"
                    src="@/asset/images/houseloan/step-3-1-1.jpg"
                    class="step-img"
                  />
                  <img
                    v-show="showStep2Image == 2"
                    src="@/asset/images/houseloan/step-3-1-2.jpg"
                    class="step-img"
                  />
                  <img
                    v-show="showStep2Image == 3"
                    src="@/asset/images/houseloan/step-3-1-3.jpg"
                    class="step-img"
                  />
                  <img
                    src="@/asset/images/houseloan/step-3-2.jpg"
                    class="step-img"
                  />
                </template>
                <div class="swiper-step">
                  <div class="swiper-step-title">Step 3：</div>
                  <div class="swiper-step-info">
                    • 完成「購屋或房屋」資料提供
                  </div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">
                    • 完成「入屋現勘/遠端視訊」預約
                  </div>
                </div>
              </div>

              <!-- step-4 -->
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
                  src="@/asset/images/houseloan/step-4-1.jpg"
                  class="step-img"
                />
                <img
                  src="@/asset/images/houseloan/step-4-2.jpg"
                  class="step-img"
                />
                <div class="swiper-step">
                  <div class="swiper-step-title">Step 4：</div>
                  <div class="swiper-step-info">• 取得核准額度</div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">• 輸入借款金額</div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">• 線上簽約</div>
                  <div class="swiper-step-info text-danger pl-3">
                    *購房貸簽約流程須提供房屋所有權狀
                  </div>
                </div>
              </div>

              <!-- step-5 -->
              <div class="swiper-slide">
                <div class="swiper-left">
                  <div class="swiper-text-1">超速資金媒合效率</div>
                  <div class="swiper-slogan">
                    <div>最快<span class="em">1</span>小時</div>
                    <div>立馬到帳！</div>
                  </div>
                </div>
                <img
                  src="@/asset/images/houseloan/step-5-1.jpg"
                  class="step-img"
                />
                <img
                  src="@/asset/images/houseloan/step-5-2.jpg"
                  class="step-img"
                />
                <div class="swiper-step">
                  <div class="swiper-step-title">Step 5：</div>
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
        <div class="diagram-swiper-mobile">
          <div class="swiper">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
              <!-- Slides -->
              <!-- step-1 -->
              <div class="swiper-slide">
                <img
                  src="@/asset/images/houseloan/step-1-1.jpg"
                  class="step-img"
                />
                <div class="swiper-step">
                  <div class="swiper-step-title">Step 1：</div>
                  <div class="swiper-step-info">• 選擇「申貸專案」</div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">• 選擇「借款金額與期數」</div>
                </div>
                <div class="swiper-left swiper-text-1">
                  <div>量身製定資金需求</div>
                  <div>提供您更滿意的貸款</div>
                </div>
                <img
                  src="@/asset/images/houseloan/step-1-2.jpg"
                  class="step-img"
                />
              </div>
              <!-- step-2 -->
              <div class="swiper-slide">
                <img
                  src="@/asset/images/houseloan/step-2-1.jpg"
                  class="step-img"
                />
                <div class="swiper-step">
                  <div class="swiper-step-title">Step 2：</div>
                  <div class="swiper-step-info">• 完成「身分驗證」</div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">• 完成其他資料提供</div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">
                    • 上傳「聯合徵信報告所需資料」
                  </div>
                </div>
                <div class="swiper-left">
                  <div class="swiper-text-1">需要準備什麼資料？</div>
                  <button
                    class="swiper-text-2 btn btn-link"
                    @click="showCheckLists.a = !showCheckLists.a"
                  >
                    Check list
                  </button>
                  <transition name="fade">
                    <div class="check-list-content" v-show="showCheckLists.a">
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
                  src="@/asset/images/houseloan/step-2-2.jpg"
                  class="step-img"
                />
              </div>
              <!-- step-3 -->
              <div class="swiper-slide">
                <template id="img-group">
                  <img
                    v-show="showStep2Image == 1"
                    src="@/asset/images/houseloan/step-3-1-1.jpg"
                    class="step-img"
                  />
                  <img
                    v-show="showStep2Image == 2"
                    src="@/asset/images/houseloan/step-3-1-2.jpg"
                    class="step-img"
                  />
                  <img
                    v-show="showStep2Image == 3"
                    src="@/asset/images/houseloan/step-3-1-3.jpg"
                    class="step-img"
                  />
                </template>
                <div class="swiper-step">
                  <div class="swiper-step-title">Step 3：</div>
                  <div class="swiper-step-info">
                    • 完成「購屋或房屋」資料提供
                  </div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">
                    • 完成「入屋現勘/遠端視訊」預約
                  </div>
                </div>
                <div class="swiper-left">
                  <div class="swiper-text-1">需要準備什麼資料？</div>
                  <div>購房貸</div>
                  <button
                    class="swiper-text-2 btn btn-link"
                    @click="
                      () => {
                        showCheckLists.b = !showCheckLists.b
                        if (showCheckLists.b) {
                          showStep2Image = 1
                          showCheckLists.c = false
                          showCheckLists.d = false
                        }
                      }
                    "
                  >
                    Check list
                  </button>
                  <transition name="fade">
                    <div
                      class="check-list-content mb-3"
                      v-show="showCheckLists.b"
                    >
                      <div class="swiper-text-3">•房屋所有權狀</div>
                      <div class="swiper-text-3">•購房合約</div>
                      <div class="swiper-text-3">•購房發票(選填)</div>
                      <div class="swiper-text-3">•入屋現勘/遠端視訊</div>
                    </div>
                  </transition>
                  <div>房屋裝修款</div>
                  <button
                    class="swiper-text-2 btn btn-link"
                    @click="
                      () => {
                        showCheckLists.c = !showCheckLists.c
                        if (showCheckLists.c) {
                          showStep2Image = 2
                          showCheckLists.b = false
                          showCheckLists.d = false
                        }
                      }
                    "
                  >
                    Check list
                  </button>
                  <transition name="fade">
                    <div
                      class="check-list-content mb-3"
                      v-show="showCheckLists.c"
                    >
                      <div class="swiper-text-3">•房屋所有權狀</div>
                      <div class="swiper-text-3">•裝修合約</div>
                      <div class="swiper-text-3">•裝修發票(選填)</div>
                      <div class="swiper-text-3">•入屋現勘/遠端視訊</div>
                    </div>
                  </transition>
                  <div>添購傢俱家電</div>
                  <button
                    class="swiper-text-2 btn btn-link"
                    @click="
                      () => {
                        showCheckLists.d = !showCheckLists.d
                        if (showCheckLists.d) {
                          showStep2Image = 3
                          showCheckLists.b = false
                          showCheckLists.c = false
                        }
                      }
                    "
                  >
                    Check list
                  </button>
                  <transition name="fade">
                    <div class="check-list-content" v-show="showCheckLists.d">
                      <div class="swiper-text-3">•房屋所有權狀</div>
                      <div class="swiper-text-3">•傢俱家電合約或發票收據</div>
                      <div class="swiper-text-3">•入屋現勘/遠端視訊</div>
                    </div>
                  </transition>
                </div>
                <img
                  src="@/asset/images/houseloan/step-3-2.jpg"
                  class="step-img"
                />
              </div>
              <!-- step-4 -->
              <div class="swiper-slide">
                <img
                  src="@/asset/images/houseloan/step-4-1.jpg"
                  class="step-img"
                />
                <div class="swiper-step">
                  <div class="swiper-step-title">Step 4：</div>
                  <div class="swiper-step-info">• 取得核准額度</div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">• 輸入借款金額</div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">• 線上簽約</div>
                  <div class="swiper-step-info text-danger pl-3">
                    *購房貸簽約流程須提供房屋所有權狀
                  </div>
                </div>
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
                  src="@/asset/images/houseloan/step-4-2.jpg"
                  class="step-img"
                />
              </div>
              <!-- step-5 -->
              <div class="swiper-slide">
                <img
                  src="@/asset/images/houseloan/step-5-1.jpg"
                  class="step-img"
                />
                <div class="swiper-step">
                  <div class="swiper-step-title">Step 5：</div>
                  <div class="swiper-step-info">• 案件上架</div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">• 快速媒合</div>
                  <div class="swiper-step-next"></div>
                  <div class="swiper-step-info">• 立即到帳</div>
                </div>
                <div class="swiper-left">
                  <div class="swiper-text-1">超速資金媒合效率</div>
                  <div class="swiper-slogan">
                    <div>最快<span class="em">1</span>小時</div>
                    <div>立馬到帳！</div>
                  </div>
                </div>
                <img
                  src="@/asset/images/houseloan/step-5-2.jpg"
                  class="step-img"
                />
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
    <div class="product-feature">
      <div class="block-content">
        <div class="h1">產品特色</div>
        <h2 class="h2">簡單、快速、安全又隱私，您申辦貸款的首選</h2>
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
            <div class="product-feature-title">聯徵不留紀錄</div>
            <div class="product-feature-text">
              <div>不會留下任何貸款相關紀錄，不影響未來信用</div>
              <div>狀況，也不佔任何銀行額度。</div>
            </div>
          </div>
          <div
            class="product-feature-item"
            data-aos="fade-right"
            data-aos-duration="500"
            data-aos-offset="200"
          >
            <div class="product-feature-title">不影響其他銀行申貸額度</div>
            <div class="product-feature-text">
              <div>購屋申請銀行房貸，同時申請普匯房產消費貸</div>
              <div>增加可動用資金，靈活資源</div>
            </div>
          </div>
          <div
            class="product-feature-item product-feature-img"
            data-aos="flip-down"
            data-aos-duration="500"
          >
            <img
              src="@/asset/images/houseloan/house-feature-1.png"
              class="img-fluid"
            />
          </div>
          <div
            class="product-feature-item"
            data-aos="fade-left"
            data-aos-duration="500"
          >
            <div class="product-feature-title">費率單純透明</div>
            <div class="product-feature-text">
              <div>只收取一次性成交手續費，且費率以及每月需</div>
              <div>還本息金額公開透明，不用擔心被收取其他費用。</div>
            </div>
          </div>
        </div>
        <div class="row no-gutters justify-content-center">
          <div class="slogan">普匯房產貸 貸你幸福滿載</div>
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
        <div class="h1">優質案例分享</div>
        <h2 class="h2">
          <div class="d-md-block d-none">
            已有數萬位使用者取得資金，您還在等什麼？
          </div>
          <div class="d-md-none d-block">已有數萬位使用者取得資金</div>
          <div class="d-md-none d-block">您還在等什麼？</div>
        </h2>
        <div class="no-gutters swiper-row">
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
  </div>
</template>

<script>
import featureCard from '../component/index/featureCard'
import AlesisProject from '../component/alesis/AlesisProject'
import houseSuit from '../component/houseSuit'

import 'swiper/swiper.scss'
import 'swiper/components/navigation/navigation.min.css'
import 'swiper/components/pagination/pagination.min.css'
import SwiperCore, { Navigation, Pagination } from 'swiper/core'
export default {
  components: {
    featureCard,
    AlesisProject,
    houseSuit,
  },
  data() {
    return {
      userId: '',
      userIdDisabled: false,
      showCheckLists: {
        a: true,
        b: false,
        c: false,
        d: false,
      },
      showStep2Image: 1,
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
  },
  computed: {
    cases() {
      return [
        {
          id: 841024,
          target_no: 'FGN2022121832164',
          product_name: '購房貸',
          product_id: 3,
          sub_product_id: 0,
          credit_level: 5,
          user_id: 47280,
          user: {
            sex: 'M',
            age: 24,
            company_name: '旺宏電子股份有限公司',
          },
          loan_amount: 75000,
          interest_rate: 10.5,
          instalment: 18,
          repayment: 1,
          expire_time: 1731070212,
          invested: 75000,
          reason: '想買新房，但額度不足',
          targetDatas: [],
          isTargetOpaque: false,
          status: 10,
          sub_status: 4,
          created_at: 1731070212,
        },
        {
          id: 1001665,
          target_no: 'FGN2022121916726',
          product_name: '房屋裝修款',
          product_id: 3,
          sub_product_id: 0,
          credit_level: 8,
          user_id: 1000539,
          user: {
            sex: 'M',
            age: 27,
            company_name: '京元電子股份有限公司',
          },
          loan_amount: 85000,
          interest_rate: 11.5,
          instalment: 12,
          repayment: 1,
          expire_time: 1666428685,
          invested: 85000,
          reason: '老家需要重新裝修，急需錢',
          targetDatas: [],
          isTargetOpaque: false,
          status: 10,
          sub_status: 4,
          created_at: 1671157868,
        },
        {
          id: 1001563,
          target_no: 'FGN20221219624961',
          product_name: '添購傢俱家電',
          product_id: 3,
          sub_product_id: 0,
          credit_level: 7,
          user_id: 1000421,
          user: {
            sex: 'M',
            age: 26,
            company_name: '富智捷股份有限公司',
          },
          loan_amount: 300000,
          interest_rate: 14.5,
          instalment: 24,
          repayment: 1,
          expire_time: 1672036789,
          invested: 300000,
          reason: '家裡電視壞了需要採購新電視，急需錢',
          targetDatas: [],
          isTargetOpaque: false,
          status: 10,
          sub_status: 4,
          created_at: 1666755643,
        },
      ]
    },
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
.btn-link {
  text-decoration: underline;
}
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
}
.house-suit {
  background-image: url('~images/bussinessLoan/smeg-suit-person-bg.png');
  padding: 140px 0 100px;
  background-size: cover;
  background-repeat: no-repeat;
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
    &.slide-5 {
      grid-template-columns: 2fr 3fr 2fr;
      .step-img {
        justify-self: center;
      }
    }
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
    grid-template-rows: 1fr 1fr;
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
          font-size: 46px;
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
      &.slide-5 {
        grid-template-columns: 1fr 1.2fr;
        .swiper-step {
          justify-self: center;
          grid-area: 2 / 2 / 3 / 3;
        }
      }
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
    .swiper-button-next {
      right: 0;
      transform: scale(0.7);
    }
    .swiper-button-prev {
      left: 0;
      transform: scale(0.7);
    }
    .user-comment {
      margin-top: 30px;
      justify-content: center;
      flex-direction: column;
      .comment-card {
        width: auto;
        padding: 10px;
        .star {
          color: #f3cd03;
        }
        .card-text {
          font-size: 16px;
        }
        .portrait {
          width: 56px;
          border-radius: 50%;
        }
      }
    }
  }
}
.diagram-swiper-mobile {
  width: 100%;
  @media (max-width: 768px) {
    // 在螢幕寬度小於等於768px時應用的樣式（手機版）
    display: block;
  }

  @media (min-width: 769px) {
    // 在螢幕寬度大於768px時應用的樣式（桌面版）
    display: none;
  }
}
.diagram-swiper-full {
  width: 100%;
  @media (max-width: 768px) {
    // 在螢幕寬度小於等於768px時應用的樣式（手機版）
    display: none;
  }

  @media (min-width: 769px) {
    // 在螢幕寬度大於768px時應用的樣式（桌面版）
    display: block;
  }
}
</style>
