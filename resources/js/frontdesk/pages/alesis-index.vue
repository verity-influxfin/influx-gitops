<template>
  <div class="index-wrapper">
    <div class="index-intro section">
      <div class="block-content intro-content">
        <!-- phone -->
        <div class="d-sm-none d-flex">
          <div class="swiper sw-headers">
            <div class="swiper-wrapper">
              <div class="swiper-slide" v-for="page in 4" v-if="isNewyearHeaderShow(page)">
                <NewyearHeader :headerIndex="page" year="2024"/>
              </div>
              <a class="swiper-slide" href="/selfieauth">
                <div class="row no-gutters">
                  <div class="col-12">
                    <img src="@/asset/images/index/header/carousel-auth-mobile.png"
                      class="img-fluid d-block mx-auto header-shadow" style="margin: 25px;" />
                  </div>
                </div>
              </a>
              <div class="swiper-slide">
                <smegHeader />
              </div>
              <a class="swiper-slide" href="/workLoan" target="_blank">
                <workloan-header />
              </a>
              <a class="swiper-slide" href="/collegeLoan" target="_blank">
                <collegeloan-header />
              </a>
              <a class="swiper-slide" href="https://www.influxfin.com/articlepage?q=news-8202" target="_blank">
                <div class="row no-gutters mt-4">
                  <div class="col-12">
                    <img src="@/asset/images/index/header/header-csr-phone.jpg"
                      class="img-fluid d-block mx-auto header-shadow" />
                  </div>
                </div>
              </a>
              <!-- <a
                class="swiper-slide"
                href="https://www.influxfin.com/articlepage?q=news-8200"
                target="_blank"
              >
                <div class="row no-gutters speech">
                  <div class="col-auto">
                    <div class="text-bg">
                      <img
                        src="@/asset/images/index/header/speech-text.png"
                        alt="普匯"
                        style="width: 305px"
                      />
                    </div>
                    <div class="solgan">提供公開 透明 安全的金融科技服務</div>
                  </div>
                  <div class="col position-releative">
                    <img
                      src="@/asset/images/index/header/speech-cover.png"
                      style="width: 340px"
                      class="speech-cover"
                    />
                  </div>
                </div>
              </a> -->
            </div>
          </div>
        </div>
        <!-- web -->
        <div class="d-sm-flex d-none">
          <div class="swiper sw-headers">
            <div class="swiper-wrapper">
              <div class="swiper-slide" v-for="page in 4" v-if="isNewyearHeaderShow(page)">
                <NewyearHeader :headerIndex="page" year="2024"/>
              </div>
              <a
                class="swiper-slide"
                href="/selfieauth"
              >
                <div class="row no-gutters">
                  <div class="col-12">
                    <img
                      src="@/asset/images/index/header/carousel-auth.png"
                      class="img-fluid d-block mx-auto header-shadow"
                    />
                  </div>
                </div>
              </a>
              <div class="swiper-slide">
                <smegHeader />
              </div>
              <div class="swiper-slide">
                <workloan-header />
              </div>
              <div class="swiper-slide">
                <collegeloan-header />
              </div>
              <a
                class="swiper-slide"
                href="https://www.influxfin.com/articlepage?q=news-8202"
                target="_blank"
              >
                <div class="row no-gutters">
                  <div class="col-12">
                    <img
                      src="@/asset/images/index/header/header-csr.jpg"
                      class="w-100 img-fluid header-shadow"
                    />
                  </div>
                </div>
              </a>
              <!-- <a
                class="swiper-slide"
                href="https://www.influxfin.com/articlepage?q=news-8200"
                target="_blank"
              >
                <div class="row no-gutters speech">
                  <div class="col-auto">
                    <div class="text-bg">
                      <img
                        src="@/asset/images/index/header/speech-text.png"
                        alt="普匯"
                        style="width: 574px"
                      />
                    </div>
                    <div class="solgan">提供公開 透明 安全的金融科技服務</div>
                  </div>
                  <div class="col position-releative">
                    <img
                      src="@/asset/images/index/header/speech-cover.png"
                      class="speech-cover"
                    />
                  </div>
                </div>
              </a> -->
            </div>
          </div>
        </div>
        <div class="intro-products">
          <div class="group">
            <div class="product-item item-1">
              <div class="card">
                <div class="card-title">個人貸款</div>
                <hr class="card-hr" />
                <div class="card-sub">AI風控 助你圓夢</div>
                <div class="card-info">
                  <div>全台首創一支手機完成貸款，全程人工智慧無人化</div>
                  <div>專為年輕人媒合資金、完成夢想</div>
                </div>
                <div class="card-content">
                  <router-link to="/workLoan" class="card-link clickable">
                    <img
                      src="@/asset/images/work_outline_black.svg"
                      alt="上班族貸"
                      class="card-link-img"
                    />
                    <div class="card-link-text">上班族貸</div>
                  </router-link>
                  <router-link to="/collegeLoan" class="card-link clickable">
                    <img
                      src="@/asset/images/edit_black.svg"
                      alt="學生貸"
                      class="card-link-img"
                    />
                    <div class="card-link-text">學生貸</div>
                  </router-link>
                  <router-link to="/engineerLoan" class="card-link clickable">
                    <img
                      src="@/asset/images/settings_black.svg"
                      alt="資訊工程師貸"
                      class="card-link-img"
                    />
                    <div class="card-link-text">資訊工程師貸</div>
                  </router-link>
                  <router-link to="/houseloan" class="card-link clickable">
                    <img
                      src="@/asset/images/house_black.svg"
                      alt="房屋貸款"
                      class="card-link-img"
                    />
                    <div class="card-link-text">房屋貸款</div>
                  </router-link>
                </div>
              </div>
            </div>
            <div class="product-item item-2">
              <div class="card">
                <div class="card-title">中小企業融資</div>
                <hr class="card-hr" />
                <div class="card-sub">普惠金融 貸你滿足</div>
                <div class="card-info d-md-block d-none">
                  <div>
                    首創第一，透過普匯完全免費，銀行放款 信保保證，一站式服
                  </div>
                  <div>
                    務，一份資料多方資金 銀行同步，提供資金解決企業週轉困擾
                  </div>
                </div>
                <div class="card-info d-md-none d-block">
                  <div>首創第一，透過普匯完全免費，銀行放款 信保保證，</div>
                  <div>一站式服務，一份資料多方資金 銀行同步，提供資金</div>
                  <div>解決企業週轉困擾</div>
                </div>
                <div class="card-content">
                  <div class="card-link">
                    <img
                      src="@/asset/images/paid_black.svg"
                      alt="企業主速貸"
                      class="card-link-img"
                    />
                    <div class="card-link-text">企業主速貸</div>
                  </div>
                  <div class="card-link">
                    <img
                      src="@/asset/images/perm_identity_black.svg"
                      alt="新創/網紅/SOHO"
                      class="card-link-img"
                    />
                    <div class="card-link-text" style="font-size: 14.2px">
                      新創/網紅/SOHO
                    </div>
                  </div>
                  <!-- <router-link to="/business-loan/smeg" class="card-link clickable">
                    <img src="@/asset/images/collections_bookmark_black.svg" alt="信保專案" class="card-link-img" />
                    <div class="card-link-text">信保專案</div>
                  </router-link> -->
                  <div class="card-link">
                    <img
                      src="@/asset/images/collections_bookmark_black.svg"
                      alt="信保專案"
                      class="card-link-img"
                    />
                    <div class="card-link-text">信保專案</div>
                  </div>
                  <div class="card-link">
                    <img
                      src="@/asset/images/paid_black.svg"
                      alt="中小企業信貸"
                      class="card-link-img"
                    />
                    <div class="card-link-text">中小企業信貸</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="product-item item-3">
              <div class="card">
                <div class="card-title">慈善公益ESG</div>
                <hr class="card-hr" />
                <div class="card-sub">永續經營 幸福無所不在</div>
                <div class="card-info">
                  <div>您的樂捐，讓台灣兒童更健康，攜手台大兒童</div>
                  <div>基金會、張鈞甯、鍾欣凌，聯合勸募樂捐助兒童</div>
                </div>
                <div class="card-content">
                  <router-link to="/charitable" class="card-link clickable">
                    <img
                      src="@/asset/images/volunteer_activism_black.svg"
                      alt="慈善公益"
                      class="card-link-img"
                    />
                    <div class="card-link-text">台大兒醫</div>
                  </router-link>
                </div>
              </div>
            </div>
            <div class="product-item item-4 d-none">
              <div class="card">
                <div class="card-title">各項消費分期專案</div>
                <hr class="card-hr" />
                <div class="card-sub">體驗金融科技帶來的便利</div>
                <div class="card-info">
                  <div>
                    秉持金融科技分享式經濟理念，運用點對點區塊鏈技術，結合不限
                  </div>
                  <div>金融銀行、產業電商等異業合作，創新多項產品</div>
                </div>
                <div class="card-content">
                  <div class="card-link">
                    <img
                      src="@/asset/images/paid_black.svg"
                      alt="美賣店商"
                      class="card-link-img"
                    />
                    <div class="card-link-text">美賣店商</div>
                  </div>
                  <div class="card-link">
                    <img
                      src="@/asset/images/collections_bookmark_black.svg"
                      alt="醫美分期"
                      class="card-lin k-img"
                    />
                    <div class="card-link-text">醫美分期</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="product-item item-5">
              <div class="card">
                <div class="card-title">聰明投資</div>
                <hr class="card-hr" />
                <div class="card-sub">小額分散 複利滾投</div>
                <div class="card-info">
                  <div>小金額大財富，穩穩投入、月月回款</div>
                  <div>1000元即可創造人生第一桶金，實現夢想</div>
                </div>
                <div class="card-content">
                  <router-link to="/investment" class="card-link clickable">
                    <img
                      src="@/asset/images/paid_black.svg"
                      alt="債權投資"
                      class="card-link-img"
                    />
                    <div class="card-link-text">債權投資</div>
                  </router-link>
                  <router-link to="/transfer" class="card-link clickable">
                    <img
                      src="@/asset/images/paid_black.svg"
                      alt="債權轉讓"
                      class="card-link-img"
                    />
                    <div class="card-link-text">債權轉讓</div>
                  </router-link>
                  <router-link to="/investment#ai" class="card-link clickable">
                    <img
                      src="@/asset/images/smart_toy_black.svg"
                      alt="AI智能投資"
                      class="card-link-img"
                    />
                    <div class="card-link-text">AI智能投資</div>
                  </router-link>
                  <router-link to="/risk" class="card-link clickable">
                    <img
                      src="@/asset/images/description_black.svg"
                      alt="風險報告書"
                      class="card-link-img"
                    />
                    <div class="card-link-text">風險報告書</div>
                  </router-link>
                </div>
              </div>
            </div>
            <div class="product-item item-6">
              <div class="card">
                <div class="card-title">SelfieAuth 持證自拍</div>
                <hr class="card-hr" />
                <div class="card-sub">資安神器來助力</div>
                <div class="card-info">
                  <div>把代表｢人｣的生物特徵與法定身分資訊連結</div>
                  <div>驗證、核實並進一步轉換數位虛擬身分</div>
                </div>
                <div class="card-content">
                  <router-link to="/selfieauth" class="card-link clickable" style="width:300px !important;max-width:300px !important;">
                    <img
                      src="@/asset/images/selfieauth.svg"
                      alt="SelfieAuth 持證自拍專利系統"
                      class="card-link-img"
                    />
                    <div class="card-link-text">SelfieAuth 持證自拍專利系統</div>
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="index-concept section">
      <div class="block-content">
        <div class="block-title">普惠金融，匯流人才</div>
        <div class="block-info concept-1">
          <div class="op">
            <div>
              惠金融：運用金融科技、點對點區塊鏈，結合AI人工智慧技術，串起社會閒置資源，落實傳統銀行無法提供的服務，
            </div>
            <div>
              幫助年輕人實現夢想，協助新創中小企業取得投融資，體現金融科技創新服務。
            </div>
          </div>
        </div>
        <div
          class="block-info concept-2"
          data-aos="fade-left"
          data-aos-duration="2000"
        >
          <div class="op">
            集人才：開發創新科技，建構AI無人自動化，打造最有溫度的金融新生態，呈現綠色金融科技新環境。
          </div>
        </div>
        <div class="block-title-2 d-sm-block d-none">
          年輕人的金融科技平台、簡單快速安全隱私、創造金融新生態
        </div>
        <div class="block-title-2 d-block d-sm-none">
          <div>年輕人的金融科技平台</div>
          <div>簡單快速安全隱私</div>
          <div>創造金融新生態</div>
        </div>
        <div class="block-info d-block">
          <span class="em">「我們不是銀行」</span>
          <span class="op">，普匯是以</span>
          <span class="em f-yellow">「金融專業」</span>
          <span class="op">為核心，</span>
          <span class="em">「高端科技」</span>
          <span class="op">
            為輔助，提供最人性友善流暢的APP使用者介面，全程AI無人化自動風控審核，運用金融科技技術加快速度、提高安全、保護隱私，提供最有溫度的社群金融服務，讓社會資金運用更便利快速有效率，創造全新的金融生態圈。
          </span>
        </div>
      </div>
      <div class="wave">
        <img
          class="img-fluid w-100"
          src="@/asset/images/index/bg-white-wave.svg"
          alt=""
        />
      </div>
    </div>
    <div class="index-loan section">
      <div class="block-content">
        <div class="block-title">一支手機，完成貸款</div>
        <div class="block-info">隨借隨貸，安全零距離</div>
        <div class="row no-gutters loan-imgs">
          <img
            id="img58"
            class="img-fluid d-sm-block d-none"
            src="@/asset/images/index/img58.png"
          />
          <div id="img56">
            <div class="position-relative text-center">
              <div class="card tip-card tip-1">
                <div class="card-container">
                  <div class="tip">無人打擾、免照會</div>
                </div>
              </div>
              <div class="card tip-card tip-2">
                <div class="card-container">
                  <div class="tip">安全隱私保密機制</div>
                </div>
              </div>
              <img class="img56-img" src="@/asset/images/index/img56.png" />
              <div>
                <div class="block-title mx-auto mt-3">普匯•你的手機ATM</div>
              </div>
            </div>
          </div>
          <img
            id="img57"
            class="img-fluid d-sm-block d-none"
            src="@/asset/images/index/img57.png"
          />
        </div>
      </div>
    </div>
    <div class="index-service section">
      <div class="wave reverse">
        <img
          src="@/asset/images/index/bg-white-wave.svg"
          class="img-fluid w-100"
          alt=""
        />
      </div>
      <div class="block-content">
        <div class="block-title">我們的服務</div>
        <div class="block-info">
          <div>創新金融科技 安全保護投資</div>
          <div>提供多元借貸 服務普惠金融</div>
        </div>
        <div class="row no-gutters service-imgs">
          <img id="service-1" src="@/asset/images/index/service-1.png" />
          <img id="service-2" src="@/asset/images/index/service-2.png" />
          <div id="s1">
            <div>提供多元個人、汽車、</div>
            <div>企業融資貸款產品</div>
          </div>
          <div id="s2">
            <div>嚴格身份驗證專利、完善反詐欺風控、</div>
            <div>嚴謹合規信評貸後、準確金流帳務、</div>
            <div>全方位資安防護</div>
          </div>
          <div id="s3">
            <div>小額分散/安全穩定、債權轉讓/資金流動、</div>
            <div>AI智能/穩賺複利高投報</div>
          </div>
          <div id="s4">
            <div>金控銀行、上市櫃企業、知名大學、</div>
            <div>新創團隊，夥伴多元，共創多贏</div>
          </div>
        </div>
      </div>
    </div>
    <div class="index-tech section">
      <div class="block-content mt-3 mb-5 pb-md-5 pb-0">
        <div class="block-content-header-title">最創新、最安全的金融科技</div>
        <div class="block-content-header-info">
          <div>唯一榮獲金控集團創投投資</div>
          <div>第一個獲得政府同意銀行合作的 中小企業線上信保融資平台</div>
        </div>
      </div>
      <div class="block-content row no-gutters">
        <div class="col-sm-6 col-12 d-flex align-items-center">
          <img
            src="@/asset/images/index/img60.png"
            class="img-fluid p-sm-5 p-2"
          />
        </div>
        <div class="col-sm-6 col-12 fin-left">
          <div class="block-title">台灣金融科技融資領導品牌</div>
          <div class="block-info">台灣金融科技的領導者</div>
          <div class="step-container">
            <div class="step-content-1">
              <div class="step-content-title">強大的KYC風控系統</div>
              <div class="step-content-info">
                <div>
                  擁有最完整且完善的AI自動化審核風控系統，全程透過手機APP，完成嚴謹徵信評估，自動產生信評等額度，「5分鐘申貸、10分鐘審核、最快1小時放款」，365天24小時不停歇。
                </div>
              </div>
            </div>
            <div class="step-content-2">
              <div class="step-content-title">個人身分驗證專利</div>
              <div class="step-content-info">
                <div>
                  2020年12月創新取得個人法定身分，透過系統裝置進行線上實名驗證之專利，並實際獲得全台灣各地方法院判決成立實證。
                </div>
              </div>
            </div>
            <div class="step-content-3">
              <div class="step-content-title">法人實名認證專利</div>
              <div class="step-content-info">
                <div>
                  繼2020年取得個人實名身分驗證專利後，2021年7月再取得法人實名認證專利，完成個人法人線上融資借貸法定身分認證之重要里程碑，開創台灣金融科技應用市場之先驅。
                </div>
              </div>
            </div>
            <div class="step-content-4">
              <div class="step-content-title">
                <div>首創全線上無人化</div>
              </div>
              <div class="step-content-info">
                <div class="content-title-style">中小企業信保融資平台</div>
                <div class="op">
                  獲政府同意攜手銀行共同揭開台灣普惠金融科技序幕。
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="index-tech section">
      <div class="block-content row no-gutters flex-sm-row flex-column-reverse">
        <div class="col-sm-6 col-12 tech-left">
          <div>
            <div class="block-title">創新金融科技融資產品</div>
            <div class="block-info">
              秉持金融科技分享式經濟理念，運用點對點區塊鏈技術，結合不限金融銀行、產業電商、汽車銷售等異業合作，創新多項產品，加速金融服務產業，提升經濟價值，普惠金融落地。
            </div>
          </div>
          <div class="step-container">
            <div class="step-content-1">
              <div class="step-content-title">普匯信用錢包</div>
              <div class="step-content-info">
                <div>
                  創新手機提供不限個人、企業信用錢包，便利生活消費或營運使用，體驗金融科技帶來的便利，提升生活品質及企業營運效能，實現「普匯•你的手機ATM」，隨借隨貸，「一支手機，搞定借貸」。
                </div>
              </div>
            </div>
            <div class="step-content-2">
              <div class="step-content-title">分期超市</div>
              <div class="step-content-info">
                <div>
                  提供多元、多種類、快速便利又安全的「先買後付」信用支付工具，讓你的消費更便利，廠商收款更快速，促進經濟活絡，提升金融服務價值。
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-12 d-flex align-items-center">
          <img
            src="@/asset/images/index/cart_shopping_3d_render_10 1.png"
            class="img-fluid p-sm-4 p-1"
            alt=""
          />
        </div>
      </div>
    </div>
    <div class="index-cert section">
      <div class="block-content">
        <div class="block-title d-none d-sm-block">
          一路走來，獲得許多肯定與認同
        </div>
        <div class="block-title d-sm-none d-block">
          <div>一路走來</div>
          <div>獲得許多肯定與認同</div>
        </div>
        <div class="block-info">我們的努力，就是為了讓你更安心</div>
        <div class="swiper-container cert-swiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide cert-cards">
              <div class="card" data-aos="fade-up">
                <div class="card-body">
                  <div class="card-cover">
                    <img
                      class="img-fluid"
                      src="@/asset/images/index/handshake2.png"
                      alt=""
                    />
                  </div>
                  <div class="card-title">新光金控創投入資</div>
                  <div class="card-divider"></div>
                  <div class="card-text">
                    2021年6月榮獲「新光金控創投」 挹資入股董事，是首家獲得金控
                    投資的金融科技公司，更奠定了 普匯在金融科技領域的品牌領導
                    地位。
                  </div>
                </div>
              </div>
              <div class="card" data-aos="fade-up">
                <div class="card-body">
                  <div class="card-cover">
                    <img
                      class="img-fluid"
                      src="@/asset/images/index/link-1.png"
                      alt=""
                    />
                  </div>
                  <div class="card-title">獲得多家銀行搶先合作</div>
                  <div class="card-divider"></div>
                  <div class="card-text">
                    我們提供使用者最簡單便利安全的金融科技體驗，快速成長10萬會員，榮獲網路評價最佳年輕化金融借貸平台，分別獲得凱基、王道及新光銀行搶先合作，更於2021年破天荒與新光銀行攜手共推全台灣第一全線上無人化「中小微企業信保融資」業務。
                  </div>
                </div>
              </div>
              <div class="card" data-aos="fade-up">
                <div class="card-body">
                  <div class="card-cover">
                    <img
                      class="img-fluid"
                      src="@/asset/images/index/dating-app-1.png"
                      alt=""
                    />
                  </div>
                  <div class="card-title">最年輕化的借貸APP</div>
                  <div class="card-divider"></div>
                  <div class="card-text">
                    我們是全台最大最安全最受學生喜愛的借貸平台，透過普匯APP可以簡單便利取得進修、生活、以及學雜費資金，多年來已陪伴幫助數萬名莘莘學子完成學業，實現夢想。
                  </div>
                </div>
              </div>
              <div class="card" data-aos="fade-up">
                <div class="card-body">
                  <div class="card-cover">
                    <img
                      class="img-fluid"
                      src="@/asset/images/index/planet-earth-1.png"
                      alt=""
                    />
                  </div>
                  <div class="card-title">ESG綠色金融科技平台</div>
                  <div class="card-divider"></div>
                  <div class="card-text">
                    秉持善用點對點區塊鏈科技提供融資服務，創立以來堅持AI自動風控審核，全程無人化無紙化，善盡保護地球保護環境，打造真正的綠色金融科技。更致力於善用AI金融科技投身慈善公益，號召知名藝人張鈞甯、鍾欣凌，共同協助台大兒醫、健康基金會聯合勸募，幫助台灣弱勢兒童，找回身心健康，身體力行ESG。
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="product-step section">
      <div class="block-content p-4">
        <div>
          <div class="product-step-head-title col-12 my-4">
            <span style="color: #f29500" class="pr-sm-2 pr-1">普匯金融</span>
            幫助超過數
            <span style="color: #036eb7" class="pr-sm-2 pr-1">萬人</span>
            渡過難關美夢成真
          </div>
          <div class="block-content-info">我們的成績</div>
        </div>
        <div class="row no-gutters counter-row">
          <alesis-counter
            image="/images/alesis-registered.svg"
            header="累積註冊用戶"
            :number="indexCounter.memberCount"
            unit="人"
          ></alesis-counter>
          <alesis-counter
            image="/images/alesis-totalmoney.svg"
            header="累積媒合金額"
            :number="indexCounter.totalLoanAmount"
            unit="元"
          ></alesis-counter>
          <alesis-counter
            image="/images/alesis-totalapproved.svg"
            header="累積成交筆數"
            :number="indexCounter.transactionCount"
            unit="筆"
          ></alesis-counter>
        </div>
      </div>
      <div class="charity">
        <div class="block-content">
          <div class="block-title">慈善公益</div>
          <div class="block-info">愛心100，捐款100</div>
          <div class="row no-gutters justify-content-center">
            <div class="col-sm-5 col-12 p-2 p-sm-0 z-5">
              <div class="info-title">
                <div>愛心不落人後，即使捐款100</div>
                <div>也能幫助弱勢兒童，找回身心健康</div>
              </div>
              <div class="info-content">
                普匯攜手愛心大使「張鈞甯」、愛心主持「鍾欣凌」，共同協助台大兒醫、台大兒醫健康基金會聯合勸募，透過普匯捐款系統，即使捐款100元也能幫助兒童們健康，我們邀請您一起投入捐款，找回孩子們健康，創造更美好的台灣環境。
              </div>
              <img
                class="img-fluid img-1251 d-sm-none d-block"
                src="@/asset/images/index/Group1251.png"
              />
              <div class="img-group">
                <div class="img-item">
                  <img
                    src="@/asset/images/index/J1.jpg"
                    alt=""
                    class="img-fluid"
                  />
                </div>
                <div class="img-item">
                  <img
                    src="@/asset/images/index/J2.jpg"
                    alt=""
                    class="img-fluid"
                  />
                </div>
                <div class="img-item">
                  <img
                    src="@/asset/images/index/J3.jpg"
                    alt=""
                    class="img-fluid"
                  />
                </div>
                <div class="img-item">
                  <img
                    src="@/asset/images/index/J4.jpg"
                    alt=""
                    class="img-fluid"
                  />
                </div>
                <div class="img-item">
                  <img
                    src="@/asset/images/index/J5.jpg"
                    alt=""
                    class="img-fluid"
                  />
                </div>
                <div class="img-item">
                  <img
                    src="@/asset/images/index/J6.jpg"
                    alt=""
                    class="img-fluid"
                  />
                </div>
              </div>
            </div>
            <div class="col-sm-auto col-12 d-sm-block d-none">
              <img
                class="img-fluid img-1251"
                src="@/asset/images/index/Group1251.png"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="row no-gutters steps">
        <div class="block-content">
          <div class="block-title d-sm-block d-none">
            如何完成夢想，幫助他們圓夢
          </div>
          <div class="block-title d-block d-sm-none">
            <div>如何完成夢想</div>
            <div>幫助他們圓夢</div>
          </div>
        </div>
        <div class="block-content step-container">
          <div class="loan">
            <div class="step-head-title">我想借款</div>
            <div class="step-head-info">
              <div>選擇額度 -</div>
              <div>提供完整資訊，有助提高額度</div>
            </div>
            <div class="step-main">
              <div class="step-content-1">
                <div class="step-content-title">步驟一</div>
                <div class="step-content-info">
                  <div>選擇額度 -</div>
                  <div>提供完整資訊，有助提高額度</div>
                </div>
                <div class="step-content-img">
                  <img
                    class="img-fluid"
                    src="@/asset/images/index/loan-step-1.png"
                    alt=""
                  />
                </div>
              </div>
              <div class="step-content-2">
                <div class="step-content-title">步驟二</div>
                <div class="step-content-info">
                  <div>選擇身分 -</div>
                  <div>根據您的身分選擇適合的專案</div>
                </div>
                <div class="step-content-img">
                  <img
                    class="img-fluid"
                    src="@/asset/images/index/loan-step-2.png"
                    alt=""
                  />
                </div>
              </div>
              <div class="step-content-3">
                <div class="step-content-title">步驟三</div>
                <div class="step-content-info">
                  <div>上傳資料 -</div>
                  <div>AI數據分析審核，全程無人打擾</div>
                </div>
                <div class="step-content-img">
                  <img
                    class="img-fluid"
                    src="@/asset/images/index/loan-step-3.png"
                    alt=""
                  />
                </div>
              </div>
              <div class="step-content-4">
                <div class="step-content-title">步驟四</div>
                <div class="step-content-info">
                  <div>持證自拍 -</div>
                  <div>審核成功後，立即上架幫您媒合投資人</div>
                </div>
                <div class="step-content-img">
                  <img
                    class="img-fluid"
                    src="@/asset/images/index/loan-step-4.png"
                    alt=""
                  />
                </div>
              </div>
              <div class="step-content-5">
                <div class="step-content-title">步驟五</div>
                <div class="step-content-info">
                  <div>媒合成功 -</div>
                  <div>24小時收到款項</div>
                </div>
                <div class="step-content-img">
                  <img
                    class="img-fluid"
                    src="@/asset/images/index/loan-step-5.png"
                    alt=""
                  />
                </div>
              </div>
            </div>
            <div class="solgan">
              <span>AI線上</span>
              <span class="em">借款</span>
              <span>平台</span>
              <span class="sm">提供您最快的資金需求</span>
            </div>
          </div>
          <div class="divide">
            <svg width="4" height="282" viewBox="0 0 4 282">
              <rect opacity="0.4" width="4" height="282" fill="#036FB7" />
            </svg>
            <div class="circle"></div>
            <svg width="4" height="282" viewBox="0 0 4 282">
              <rect opacity="0.4" width="4" height="282" fill="#036FB7" />
            </svg>
          </div>
          <div class="invest">
            <div class="step-head-title">我想投資</div>
            <div class="step-head-info">
              <div>您的一小筆錢，可能幫他圓夢</div>
              <div>更可能救了他的人生</div>
            </div>
            <div class="step-main">
              <div class="step-content-1">
                <div class="step-content-title">步驟一</div>
                <div class="step-content-info">
                  <div>進入我的資產</div>
                </div>
                <div class="step-content-img">
                  <img
                    class="img-fluid"
                    src="@/asset/images/index/invest-step-1.png"
                    alt=""
                  />
                </div>
              </div>
              <div class="step-content-2">
                <div class="step-content-title">步驟二</div>
                <div class="step-content-info">
                  <div>選擇單筆/打包出售</div>
                </div>
                <div class="step-content-img">
                  <img
                    class="img-fluid"
                    src="@/asset/images/index/invest-step-2.png"
                    alt=""
                  />
                </div>
              </div>
              <div class="step-content-3">
                <div class="step-content-title">步驟三</div>
                <div class="step-content-info">
                  <div>調整出讓金額，並點選出讓</div>
                  <div>詳閱轉讓協議書</div>
                </div>
                <div class="step-content-img">
                  <img
                    class="img-fluid"
                    src="@/asset/images/index/invest-step-3.png"
                    alt=""
                  />
                </div>
              </div>
              <div class="step-content-4">
                <div class="step-content-title">步驟四</div>
                <div class="step-content-info">
                  <div>上架成功，等待媒合</div>
                </div>
                <div class="step-content-img">
                  <img
                    class="img-fluid"
                    src="@/asset/images/index/invest-step-4.png"
                    alt=""
                  />
                </div>
              </div>
            </div>
            <div class="solgan">
              <span>小額</span>
              <span class="em">投資</span>
              <span>無負擔</span>
              <span class="sm">活絡資金助人圓夢</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="user-experience section">
      <div class="block-content">
        <div class="row no-gutters">
          <div class="col-12">
            <div class="block-title">用戶體驗</div>
            <div class="block-info">我們提供最溫暖的金融科技服務</div>
          </div>
        </div>
        <div class="row no-gutters swiper-container user-experience-back">
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
            <div class="swiper-slide">
              <div class="row no-gutters user-comment">
                <div class="col-auto">
                  <iframe
                    width="360"
                    height="230px"
                    src="https://www.youtube.com/embed/VTJB7c1fS-4"
                    title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                  >
                  </iframe>
                </div>
                <div class="col-auto mx-5 d-sm-block d-none">
                  <iframe
                    width="360"
                    height="230"
                    src="https://www.youtube.com/embed/YkZyp5iSgxs"
                    title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                  >
                  </iframe>
                </div>
                <div class="col-auto d-sm-block d-none">
                  <iframe
                    width="360"
                    height="230"
                    src="https://www.youtube.com/embed/ewEoWAjkbGs"
                    title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                  >
                  </iframe>
                </div>
              </div>
            </div>
          </div>
          <!-- 導覽按鈕 -->
          <div class="swiper-button-prev ue-prev"></div>
          <div class="swiper-button-next ue-next"></div>
        </div>
      </div>
      <div class="row align-items-center media">
        <div class="col-sm col-auto">
          <a
            class="項目"
            href="https://tw.money.yahoo.com/%E9%87%91%E8%9E%8D%E7%A7%91%E6%8A%80%E5%89%B5%E6%96%B0%E5%9C%92%E5%8D%809%E5%AE%B6%E5%BB%A0%E5%95%86%E5%8A%A0%E5%85%A5-%E4%BC%81%E6%A5%AD%E5%AF%A6%E9%A9%97%E5%AE%A4%E9%A6%96%E6%B3%A2%E5%85%AD%E5%A4%A7%E4%B8%BB%E9%A1%8C%E5%85%AC%E5%B8%83-083826449.html"
            target="_blank"
          >
            <img
              class="圖片 img-fluid d-block mx-auto"
              src="/images/media/a645f762048f5f425d0f0a8486f34612.png"
              alt="Yahoo!新聞"
            />
          </a>
        </div>
        <div class="col-sm col-auto">
          <a
            class="項目"
            href="http://n.yam.com/Article/20180803346949"
            target="_blank"
          >
            <img
              class="圖片 img-fluid d-block mx-auto"
              src="/images/media/1JHY1QOLYPF0.png"
              alt="蕃薯藤"
            />
          </a>
        </div>
        <div class="col-sm col-auto">
          <a
            class="項目"
            href="https://www.nownews.com/news/20180807/2798010/"
            target="_blank"
          >
            <img
              class="圖片 img-fluid d-block mx-auto"
              src="/images/media/Now_News_logo.png"
              alt="今日新聞"
            />
          </a>
        </div>
        <div class="col-sm col-auto">
          <a
            class="項目"
            href="https://act.chinatimes.com/market/content.aspx?AdID=6585&chdtv"
            target="_blank"
          >
            <img
              class="圖片 img-fluid d-block mx-auto"
              src="/images/media/logo-chinatimes2019.png"
              alt="中時電子報"
            />
          </a>
        </div>
        <div class="col-sm col-auto">
          <a
            class="項目"
            href="https://www.wealth.com.tw/home/articles/20567"
            target="_blank"
          >
            <img
              class="圖片 img-fluid d-block mx-auto"
              src="/images/media/logo-red.png"
              alt="財訊"
            />
          </a>
        </div>
        <div class="col-sm col-auto">
          <a
            class="項目"
            href="https://news.cnyes.com/news/id/4267004"
            target="_blank"
          >
            <img
              class="圖片 img-fluid d-block mx-auto"
              src="/images/media/logo-cn-yes-1.png"
              alt="鉅亨網"
            />
          </a>
        </div>
        <div class="col-sm col-auto">
          <a
            class="項目"
            href="https://www.gvm.com.tw/article/71426"
            target="_blank"
          >
            <img
              class="圖片 img-fluid d-block mx-auto"
              src="/images/media/logo_gvm.svg"
              alt="遠見"
            />
          </a>
        </div>
        <div class="col-sm col-auto">
          <a
            class="項目"
            href="https://m.ctee.com.tw/livenews/aj/a95645002019042615564198"
            target="_blank"
          >
            <img
              class="圖片 img-fluid d-block mx-auto"
              src="/images/media/logo300_90.png"
              alt="工商時報"
            />
          </a>
        </div>
        <div class="col-sm col-auto">
          <a
            class="項目"
            href="https://www.setn.com/News.aspx?NewsID=792990"
            target="_blank"
          >
            <img
              class="圖片 img-fluid d-block mx-auto"
              src="/images/media/logo_setn.png"
              alt="三立新聞網"
            />
          </a>
        </div>
        <div class="col-sm col-auto">
          <a
            class="項目"
            href="https://www.cna.com.tw/postwrite/detail/279030"
            target="_blank"
          >
            <img
              class="圖片 img-fluid d-block mx-auto"
              src="/images/media/logo_cna.png"
              alt="中央通訊社"
            />
          </a>
        </div>
      </div>
    </div>
    <float />
  </div>
</template>

<script>
import AlesisCounter from '../component/alesis/AlesisCounter'
import float from '../component/floatComponent'
import workloanHeader from '../component/index/header/workloanHeader.vue'
import collegeloanHeader from '../component/index/header/collegeloanHeader.vue'
import smegHeader from '../component/index/header/smegHeader'
// import newyear2023Header3 from '../component/index/header/newyear2023Header3.vue'
import NewyearHeader from '../component/index/header/newyearHeader.vue'
import { alesisIndexCounter } from './api'
import 'swiper/swiper.scss'
import 'swiper/components/navigation/navigation.min.css'
import SwiperCore, { Navigation } from 'swiper/core'
import { gsap } from 'gsap/dist/gsap'
import { ScrollTrigger } from 'gsap/dist/ScrollTrigger'

export default {
  components: {
    float,
    AlesisCounter,
    workloanHeader,
    collegeloanHeader,
    smegHeader,
    NewyearHeader,
  },
  data: () => ({
    indexCounter: {},
  }),
  created() {
    $('title').text(`首頁 - inFlux普匯金融科技`)
  },
  mounted() {
    gsap.registerPlugin(ScrollTrigger)
    gsap.from('#img58', {
      scrollTrigger: {
        trigger: '.index-loan',
        start: '-300px top',
        toggleActions: 'play pause resume reset',
        // markers:true,
      },
      x: -1200,
      duration: 2,
    })
    gsap.from('#img57', {
      scrollTrigger: {
        trigger: '.index-loan',
        start: '-300px top',
        toggleActions: 'play pause resume reset',
      },
      x: 1200,
      duration: 2,
    })
    gsap.from('#img56', {
      scrollTrigger: {
        trigger: '.index-loan',
        start: '-300px top',
        toggleActions: 'play play resume reset',
      },
      y: 1200,
      duration: 2,
    })
    gsap.from('#s1', {
      scrollTrigger: {
        trigger: '.service-imgs',
        start: '-300px top',
        toggleActions: 'play pause resume reset',
      },
      y: 1200,
      duration: 2,
    })
    gsap.from('#s2', {
      scrollTrigger: {
        trigger: '.service-imgs',
        start: '-300px top',
        toggleActions: 'play pause resume reset',
      },
      x: -1200,
      duration: 2,
    })
    gsap.from('#s3', {
      scrollTrigger: {
        trigger: '.service-imgs',
        start: '-300px top',
        toggleActions: 'play pause resume reset',
      },
      x: 1200,
      duration: 2,
    })
    gsap.from('#s4', {
      scrollTrigger: {
        trigger: '.service-imgs',
        start: '-300px top',
        toggleActions: 'play pause resume reset',
      },
      y: 1200,
      duration: 2,
    })
    alesisIndexCounter().then((v) => {
      this.indexCounter = v
    })
    // 用戶體驗
    SwiperCore.use([Navigation])
    new Swiper('.swiper-container.user-experience-back', {
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    })
    // banner
    new Swiper('.sw-headers', {
      loop: true,
      autoplay: {
        delay: 5000,
      },
    })
  },
  methods: {
    isNewyearHeaderShow(page) {
      const mapping = {
        1: [new Date('2024-02-07 00:00'), new Date('2024-02-19 23:59:59')],
        2: [new Date('2024-02-07 00:00'), new Date('2024-02-14 23:59:59')],
        3: [new Date('2024-02-08 12:00'), new Date('2024-02-10 12:00')],
        4: [new Date('2024-02-15 09:00'), new Date('2024-02-15 23:00')],
      }
      const now = new Date()
      const [start_bound, end_bound] = mapping[page]

      return start_bound <= now && now <= end_bound
    },
  },
}
</script>

<style lang="scss" scoped>
@import '../component/alesis/alesis';

.block-content {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
}

.header-shadow {
  border-radius: 12px;
  box-shadow: 0 6px 6px 0 rgb(0 0 0 / 20%);
}

.wave {
  position: absolute;
  bottom: 0;
  left: 0px;
  width: 100%;
  height: 100px;
  overflow: hidden;

  &.reverse {
    top: 0;
    right: -30px;
    transform: rotate(180deg);
  }
}

.z-5 {
  z-index: 5;
}

.index-intro {

  background-image: url('~images/index/index-header-bg.jpg');
  background-position: bottom;
  padding: 100px 0 15px;
  background-size: cover;
  background-repeat: no-repeat;
  min-height: 850px;

  .intro-content {
    .sw-headers {
      min-height: 300px;
      max-width: 1300px;
      z-index: 0;

      .swiper-slide {
        // padding: 0 45px;
        color: white;
        overflow: hidden;
      }

      .swiper-button-prev,
      .swiper-button-next {
        color: #707070;
        top: 180px;

        &::after {
          font-size: 28px;
        }
      }

      .speech {
        flex-wrap: nowrap;

        .text-bg {
          height: 245px;
          width: 472px;
        }

        .solgan {
          margin-top: 100px;
          font-style: normal;
          font-weight: 700;
          font-size: 24px;
          letter-spacing: 0.04em;
          color: #393939;
        }

        .speech-cover {
          left: 60px;
          position: relative;
          height: 456px;
          top: -50px;
        }
      }

      .general {
        flex-wrap: nowrap;

        .text-bg {
          height: 245px;
          width: 472px;
        }

        .solgan {
          margin-top: 100px;
          font-style: normal;
          font-weight: 700;
          font-size: 24px;
          letter-spacing: 0.04em;
          color: #393939;
        }

        .man {
          left: 350px;
          position: relative;
          height: 695px;
          top: -76px;
        }
      }
    }

    .intro-products {
      position: relative;
      top: -190px;
      margin-bottom: -190px;

      .group {
        display: grid;
        margin: 15px auto;      
        grid-template-rows: 234px 234px;
        gap: 20px;
      }
    }

    .product-item {
      &.item-1 .card {
        background-image: url('~images/index/product-bg-1.jpg');
      }

      &.item-2 .card {
        background-image: url('~images/index/product-bg-2.jpg');
      }

      &.item-3 .card {
        background-image: url('~images/index/product-bg-3.jpg');
      }

      &.item-4 .card {
        background-image: url('~images/index/product-bg-4.jpg');
      }

      &.item-5 .card {
        background-image: url('~images/index/product-bg-5.jpg');
      }

      &.item-6 .card {
        background-image: url('~images/index/product-bg-6.jpg');
      }

      .card {
        box-shadow: 0px 4px 12px rgba(21, 91, 153, 0.36);
        padding: 16px 32px;
        border-radius: 6px;
        overflow: hidden;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top -15px right;
        height: 100%;
      }

      .card-title {
        font-weight: 700;
        font-style: normal;
        font-size: 22px;
        line-height: 1.4;
        margin-bottom: 0;
        color: #083a6e;
      }

      .card-hr {
        margin: 2px 0;
        background: #ddeafa;
        height: 2px;
        width: 40px;
      }

      .card-sub {
        font-style: normal;
        font-weight: 500;
        font-size: 16px;
        line-height: 1.4;
        margin-bottom: 5px;
        color: #036eb7;
      }

      .card-info {
        font-style: normal;
        font-weight: 400;
        font-size: 12px;
        line-height: 1.4;
        color: #393939;
        opacity: 0.75;
      }

      .card-content {
        display: grid;
        grid-template-columns: 172px 1fr;
        margin-top: 10px;
        gap: 12px;
      }

      .card-link {
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid #7ac1ea;
        border-radius: 6px;
        padding: 4px 12px;
        display: flex;
        align-items: center;
        max-width: 172px;
        margin-left: 0;

        &.clickable {
          cursor: pointer;
          transition: all 0.3s;

          &:hover {
            background: #7ac1ea;

            .card-link-text {
              color: #fff;
            }
          }
        }

        &-img {
          width: 24px;
          height: 24px;
          margin-right: 8px;
        }

        &-text {
          font-style: normal;
          font-weight: 400;
          font-size: 18px;
          line-height: 1.4;
          color: #393939;
        }
      }
    }
  }
}

.index-concept {
  position: relative;
  padding: 40px 80px;
  background-color: #036eb7;
  background-image: url('~images/index/concept-bg.png');
  background-repeat: no-repeat;
  background-size: cover;
  height: 1044px;

  .block-content {
    padding: 0 50px;

    .block-title {
      font-weight: 500;
      font-size: 56px;
      line-height: 81px;
      margin: 20px 0;
      color: #e6f5ff;
      text-align: center;
    }

    .block-title-2 {
      color: #e6f5ff;
      text-align: center;
      font-size: 32px;
      line-height: 1.4;
      margin: 40px 0;
    }

    .block-info {
      display: flex;
      align-items: flex-start;
      font-size: 20px;
      color: #fff;
      line-height: 2.5;

      &.concept-1::before {
        display: inline-block;
        content: '「普」';
        color: #fff;
        opacity: 1;
        font-size: 40px;
        line-height: 45px;
      }

      &.concept-2::before {
        display: inline-block;
        content: '「匯」';
        color: #ffe200;
        opacity: 1;
        font-size: 40px;
        line-height: 45px;
      }
    }

    .op {
      opacity: 0.56;
    }

    .em {
      font-size: 32px;
      color: #fff;
      opacity: 1;
    }

    .f-yellow {
      color: #ffe200;
      opacity: 1;
    }
  }
}

.index-loan {
  position: relative;
  padding: 30px 70px;
  background-image: url('~images/index/index-phone-bg.png');
  background-repeat: no-repeat;
  background-size: cover;
  height: 1063px;

  .block-title {
    color: #036eb7;
    font-size: 56px;
    text-align: center;
  }

  .block-info {
    color: #036eb7;
    font-size: 40px;
    margin-top: 24px;
    opacity: 0.64;
    text-align: center;
  }

  .loan-imgs {
    padding: 35px 0;
    position: relative;
    height: 900px;

    #img56 {
      display: block;
      margin: auto;

      .img56-img {
        width: 380px;
      }
    }

    #img58 {
      left: -50px;
      bottom: -12px;
      position: absolute;
    }

    #img57 {
      top: -20px;
      right: -232px;
      position: absolute;
      transform: rotate(-7.93deg);
    }
  }

  .tip-card {
    &.tip-1 {
      right: -155px;
      top: 200px;
    }

    &.tip-2 {
      left: -144px;
      top: 400px;
    }

    position: absolute;
    border: none;
    width: 300px;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    border-radius: 20px;

    .card-container {
      padding: 8px 26px;
    }

    .tip {
      position: initial;
      text-align: center;
      font-size: 24px;
      color: #036eb7;
    }
  }
}

@keyframes service {
  3% {
    right: -125px;
    opacity: 0.4;
    filter: alpha(opacity=20);
  }

  20% {
    right: 145px;
    opacity: 1;
    filter: alpha(opacity=0);
  }

  45% {
    right: 145px;
    opacity: 0;
    filter: alpha(opacity=0);
  }
}

@keyframes service-img {
  0% {
    top: 0px;
  }

  50% {
    top: 30px;
  }

  100% {
    top: 0;
  }
}

.index-service {
  position: relative;
  padding: 100px 70px 20px;
  background: linear-gradient(180.82deg, #036eb7 0.7%, #ffffff 92.77%);

  .block-title {
    color: #fff;
    font-size: 56px;
    text-align: center;
  }

  .block-info {
    color: #fff;
    font-size: 40px;
    margin-top: 24px;
    opacity: 0.64;
    text-align: center;
  }

  .service-imgs {
    padding: 15px 0 190px;
    position: relative;
    overflow: hidden;

    #service-1 {
      animation: service-img 4s linear infinite;
      position: relative;
      display: block;
      margin: 0 auto;
      height: 500px;
    }

    #service-2 {
      position: absolute;
      display: block;
      margin: 0 auto;
      bottom: -125px;
      left: 400px;
      height: 400px;
    }

    #s1 {
      position: absolute;
      left: 253px;
      top: 127px;
      font-size: 18px;
      line-height: 26px;
      color: #ffffff;
    }

    #s2 {
      position: absolute;
      right: 178px;
      top: 175px;
      font-size: 18px;
      line-height: 26px;
      color: #ffffff;
    }

    #s3 {
      position: absolute;
      left: 188px;
      bottom: 160px;
      font-size: 18px;
      line-height: 26px;
      color: #ffffff;
    }

    #s4 {
      position: absolute;
      right: 210px;
      bottom: 165px;
      font-size: 18px;
      line-height: 26px;
      color: #ffffff;
    }
  }
}

.index-tech {
  padding: 20px 50px;

  .tech-left {
    padding-left: 80px;
  }

  .fin-left {
    padding-left: 30px;
  }

  .block-content-header-title {
    color: #036eb7;
    font-size: 56px;
    text-align: center;
  }

  .block-content-header-info {
    color: #036eb7;
    font-size: 40px;
    margin-top: 24px;
    opacity: 0.64;
    text-align: center;
  }

  .block-title {
    color: #036eb7;
    font-size: 48px;
    text-align: left;
  }

  .block-info {
    color: #036eb7;
    font-size: 30px;
    margin-top: 0;
    opacity: 0.64;
    text-align: left;
  }

  .step-container {
    margin: 15px auto;
    position: relative;
  }

  .step-content-1 {
    width: 435px;
    padding: 0 15px 10px 0;

    .step-content-title::before {
      content: '1';
    }
  }

  .step-content-2 {
    width: 435px;
    padding: 0 15px 10px 0;

    .step-content-title::before {
      content: '2';
    }

    .step-content-info {
      border-left: 5px solid #f29600;
    }
  }

  .step-content-3 {
    width: 435px;
    padding: 0 15px 10px 0;

    .step-content-title::before {
      content: '3';
    }
  }

  .step-content-4 {
    width: 435px;
    padding: 0 15px 10px 0;

    .step-content-title::before {
      content: '4';
    }

    .step-content-info {
      border-left: 5px solid #f29600;
      opacity: 1;
    }

    .op {
      opacity: 0.56;
    }
  }

  .step-content-title {
    padding-left: 42px;
    font-style: normal;
    font-weight: 700;
    font-size: 40px;
    color: #023d64;

    &::before {
      background: #036fb7;
      border-radius: 50%;
      color: #fff;
      font-size: 40px;
      font-style: normal;
      font-weight: 500;
      position: absolute;
      left: -28px;
      text-align: center;
      width: 60px;
    }
  }

  .content-title-style {
    font-style: normal;
    font-weight: 700;
    font-size: 24px;
    color: #023d64;
    margin-bottom: 15px;
  }

  .step-content-info {
    margin: 15px 0;
    padding-left: 42px;
    font-weight: 400;
    font-size: 16px;
    line-height: 23px;
    color: #023d64;
    opacity: 0.56;
    border-left: 5px solid #036fb7;
  }
}

.index-cert {
  padding: 64px;
  background: #f3f9fc;

  .cert-swiper {
    overflow: initial;
  }

  .block-title {
    color: #036eb7;
    font-size: 56px;
    text-align: center;
  }

  .block-info {
    color: #036eb7;
    font-size: 40px;
    margin-top: 24px;
    opacity: 0.64;
    text-align: center;
  }

  .cert-cards {
    display: flex;
    margin-top: 60px;
    justify-content: center;
    gap: 20px;

    .card {
      width: 330px;
      border: none;
      box-shadow: 0px 4px 12px rgba(21, 91, 153, 0.16);
      border-radius: 16px;

      .card-body {
        padding: 24px;
      }

      .card-cover {
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
  }
}

.product-step {
  .block-title {
    margin-top: 40px;
    color: #036eb7;
    font-size: 56px;
    text-align: center;
  }

  .product-step-head-title {
    font-style: normal;
    font-weight: 700;
    font-size: 56px;
    text-align: center;
  }

  .block-content-info {
    color: #036eb7;
    font-size: 40px;
    margin: 24px 0;
    opacity: 0.64;
    text-align: center;
  }

  .counter-row {
    justify-content: space-evenly;
  }

  .steps {
    height: 1000px;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    background-image: url('~images/index/step-bg.png');
  }

  .step-container {
    display: grid;
    grid-template-columns: 1fr 32px 1fr;
    padding: 60px;
    gap: 20px;

    .loan {
      .step-head-title {
        color: #036fb7;
      }

      .step-content-1 {
        width: 275px;
        padding: 0 15px 10px 30px;
        border-left: 5px solid #036fb7;

        .step-content-title::before {
          content: '1';
        }

        .step-content-img {
          opacity: 1;
          z-index: 3;
        }
      }

      .step-content-2 {
        width: 275px;
        padding: 0 15px 10px 30px;
        border-left: 5px solid #036fb7;

        .step-content-title::before {
          content: '2';
        }

        &:hover,
        &:focus-within {
          .step-content-img {
            z-index: 10;
            opacity: 1;
            transition-property: opacity;
            transition-duration: 1s;
          }
        }
      }

      .step-content-3 {
        width: 275px;
        padding: 0 15px 10px 30px;
        border-left: 5px solid #036fb7;

        .step-content-title::before {
          content: '3';
        }

        &:hover,
        &:focus-within {
          .step-content-img {
            z-index: 10;
            opacity: 1;
            transition-property: opacity;
            transition-duration: 1s;
          }
        }
      }

      .step-content-4 {
        width: 275px;
        padding: 0 15px 10px 30px;
        border-left: 5px solid #036fb7;

        .step-content-title::before {
          content: '4';
        }

        &:hover,
        &:focus-within {
          .step-content-img {
            z-index: 10;
            opacity: 1;
            transition-property: opacity;
            transition-duration: 1s;
          }
        }
      }

      .step-content-5 {
        width: 275px;
        padding: 0 15px 10px 30px;

        .step-content-title::before {
          content: '5';
        }

        &:hover,
        &:focus-within {
          .step-content-img {
            z-index: 10;
            opacity: 1;
            transition-property: opacity;
            transition-duration: 1s;
          }
        }
      }

      .solgan {
        border-radius: 16px;
        border: 1px solid #036fb7;
        color: #393939;
        font-size: 20px;
        margin: 30px 0 0 45px;
        padding: 14px;
        text-align: center;
        width: 210px;

        .em {
          font-size: 28px;
          color: #036eb7;
        }

        .sm {
          font-size: 14px;
          opacity: 0.8;
        }
      }
    }

    .invest {
      .step-head-title {
        color: #f29500;
      }

      .step-content-1 {
        width: 275px;
        padding: 0 15px 40px 30px;
        border-left: 5px solid #f29500;

        .step-content-title::before {
          content: '1';
          background: #f29500;
        }

        .step-content-img {
          opacity: 1;
          z-index: 3;
        }
      }

      .step-content-2 {
        width: 275px;
        padding: 0 15px 40px 30px;
        border-left: 5px solid #f29500;

        .step-content-title::before {
          content: '2';
          background: #f29500;
        }

        &:hover,
        &:focus-within {
          .step-content-img {
            z-index: 10;
            opacity: 1;
            transition-property: opacity;
            transition-duration: 1s;
          }
        }
      }

      .step-content-3 {
        width: 275px;
        padding: 0 15px 25px 30px;
        border-left: 5px solid #f29500;

        .step-content-title::before {
          content: '3';
          background: #f29500;
        }

        &:hover,
        &:focus-within {
          .step-content-img {
            z-index: 10;
            opacity: 1;
            transition-property: opacity;
            transition-duration: 1s;
          }
        }
      }

      .step-content-4 {
        width: 275px;
        padding: 0 15px 10px 30px;

        .step-content-title::before {
          content: '4';
          background: #f29500;
        }

        &:hover,
        &:focus-within {
          .step-content-img {
            z-index: 10;
            opacity: 1;
            transition-property: opacity;
            transition-duration: 1s;
          }
        }
      }

      .solgan {
        border-radius: 16px;
        border: 1px solid #036fb7;
        color: #393939;
        font-size: 20px;
        margin: 139px 0 0 45px;
        padding: 14px;
        text-align: center;
        width: 210px;

        .em {
          font-size: 28px;
          color: #036eb7;
        }

        .sm {
          font-size: 14px;
          opacity: 0.8;
        }
      }
    }

    .step-head-title {
      font-weight: 700;
      font-size: 32px;
      text-align: center;
      letter-spacing: 0.2px;
    }

    .step-head-info {
      margin-top: 8px;
      font-weight: 700;
      font-size: 20px;
      text-align: center;
      color: #393939;
    }

    .step-main {
      margin-top: 45px;
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: flex-end;
    }

    .divide {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;

      .circle {
        width: 32px;
        height: 32px;
        background: #036fb7;
        margin: 60px 0;
        border-radius: 50%;
      }
    }

    .step-content-title {
      font-style: normal;
      font-weight: 700;
      font-size: 24px;
      color: #036fb7;

      &::before {
        background: #036fb7;
        border-radius: 50%;
        color: #fff;
        font-size: 24px;
        font-style: normal;
        font-weight: 500;
        position: absolute;
        right: 255px;
        text-align: center;
        width: 36px;
      }
    }

    .step-content-info {
      font-weight: 400;
      font-size: 16px;
      line-height: 23px;
      color: #023d64;
      opacity: 0.56;
    }

    .step-content-img {
      top: 0;
      left: 15px;
      position: absolute;
      transition-property: opacity;
      transition-duration: 1s;
      opacity: 0;
    }
  }
}

.charity {
  padding: 64px;
  background: #f3f9fc;

  .block-title {
    margin-top: 0;
    font-weight: 500;
    font-size: 56px;
    color: #036fb7;
    text-align: center;
  }

  .block-info {
    color: #036fb7;
    font-size: 40px;
    margin-top: 24px;
    margin-bottom: 70px;
    opacity: 0.64;
    text-align: center;
  }

  .info-title {
    font-size: 32px;
    color: #023d64;
  }

  .info-content {
    margin: 12px 0 32px;
    font-size: 18px;
    line-height: 26px;
    color: #023d64;
    opacity: 0.64;
  }

  .img-group {
    display: grid;
    grid-template-rows: 1fr 1fr;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 15px;

    .img-item {
      transition: transform 0.5s ease;
    }

    .img-item:hover {
      transform: scale(2.5);
    }
  }

  .img-1251 {
    height: 505px;
    margin-left: 15px;
  }
}

/**
    user experience
*/
.user-experience {
  position: relative;
  background-image: url('~images/user_experence_cover.png');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: 0 top;
  height: 820px;

  .block-title {
    margin-top: 36px;
    font-size: 56px;
    line-height: 81px;
    text-align: center;
    letter-spacing: 0.2px;
    color: #fff;
  }

  .block-info {
    font-size: 24px;
    line-height: 35px;
    text-align: center;
    color: #393939;
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

  .media {
    position: absolute;
    padding: 20px;
    bottom: 0;
    width: 101%;
    left: 15px;
    background: rgba($color: #8c97af, $alpha: 0.5);

    .圖片 {
      height: 48px;
    }
  }

  .ue-prev,
  .ue-next {
    top: 215px;
  }
}

@media (min-width: 1290px) {

  .group{
    max-width: 1300px;
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 1290px) {
  .group {
    max-width: 1000px;
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 850px) {
  .index-intro .intro-content .intro-products {
    margin-top: 160px !important;
  }

  .block-content {
    margin: 0 auto;
  }

  .wave {
    position: absolute;
    bottom: initial;
    height: 100px;
    overflow: hidden;

    &.reverse {
      top: -40px;
      transform: rotate(180deg);
    }
  }

  .index-intro {
    background-image: none;
    padding: 0;
    height: auto;

    .intro-content {
      background-image: url('~images/index/header-bg-phone.jpg');
      background-position: initial;
      background-size: cover;
      background-repeat: no-repeat;

      .sw-headers {
        min-height: 300px;

        .swiper-slide {
          padding: 0 25px;
          color: white;
        }

        .swiper-button-prev,
        .swiper-button-next {
          color: #fff;

          &::after {
            font-size: 20px;
          }
        }

        .speech {
          padding-top: 20px;
          flex-wrap: wrap;

          .text-bg {
            margin-top: 0;
            height: 210px;
            width: 178px;
          }

          .solgan {
            margin-top: 100px;
            font-size: 14px;
          }

          .speech-cover {
            left: 0;
            top: -260px;
            position: relative;
            height: 258px;
          }
        }

        .general {
          flex-wrap: wrap;
          padding-top: 20px;

          .text-bg {
            height: 104px;
            width: 215px;
          }

          .solgan {
            margin-top: 182px;
            font-size: 14px;
          }

          .man {
            left: 136px;
            position: relative;
            height: 295px;
            top: -233px;
          }
        }
      }

      .intro-products {
        margin-top: -10px;
        padding-bottom: 60px;
        top: -260px;
        margin-bottom: -300px;

        .group {
          display: grid;
          margin: 15px;
          grid-template-columns: 1fr;
          grid-template-rows: auto auto 245px 245px auto;
          gap: 18px;
        }
      }

      .product-item {
        .card {
          padding: 16px 14px;
          overflow: hidden;
        }

        .card-title {
          font-size: 26px;
        }

        .card-hr {
          width: 40px;
        }

        .card-sub {
          font-size: 17px;
          line-height: 1.4;
        }

        .card-content {
          grid-template-columns: 155px 1fr;
          gap: 11px;
          margin-top: 12px;
        }

        .card-link {
          padding: 6px;
          max-width: 155px;

          &-img {
            width: 20px;
            height: 20px;
            margin-right: 4px;
          }

          &-text {
            font-size: 17px;
            line-height: 1.4;
          }
        }
      }
    }
  }

  .index-concept {
    position: relative;
    padding: 30px 10px;
    background-image: url('~images/index/concept-bg.png');
    height: auto;

    .block-content {
      padding: 0 0 50px;

      .block-title {
        font-size: 28px;
        line-height: 1.4;
        margin: 0 0 20px;
      }

      .block-title-2 {
        font-size: 20px;
        line-height: 1.4;
        margin: 0 0 20px;
      }

      .block-info {
        display: flex;
        align-items: flex-start;
        font-size: 14px;
        line-height: 1.5;

        &.concept-1::before {
          content: '「普」';
          font-size: 20px;
          line-height: 14px;
        }

        &.concept-2::before {
          display: inline-block;
          content: '「匯」';
          font-size: 20px;
          line-height: 14px;
        }
      }

      .op {
        margin-bottom: 10px;
      }

      .em {
        font-size: 20px;
      }
    }
  }

  .index-loan {
    padding: 20px 0;
    background-image: url('~images/index/index-phone-bg.png');
    background-repeat: no-repeat;
    background-size: cover;
    height: 600px;

    .block-title {
      font-size: 28px;
    }

    .block-info {
      font-size: 18px;
      margin-top: 8px;
    }

    .loan-imgs {
      padding: 35px 0;
      position: relative;
      height: auto;

      #img56 .img56-img {
        width: 210px;
      }

      //   #img58 {
      //     left: -50px;
      //     bottom: -12px;
      //     position: absolute;
      //     transform: matrix(1, 0.08, 0.08, 1, 0, 0);
      //   }
      //   #img57 {
      //     top: -20px;
      //     right: -232px;
      //     position: absolute;
      //     transform: rotate(-7.93deg);
      //   }
    }

    .tip-card {
      &.tip-1 {
        right: -40px;
        top: 128px;
      }

      &.tip-2 {
        left: -40px;
        top: 280px;
      }

      width: 160px;

      .card-container {
        padding: 8px 12px;
      }

      .tip {
        font-size: 14px;
      }
    }
  }

  @keyframes service {
    3% {
      right: -125px;
      opacity: 0.4;
      filter: alpha(opacity=20);
    }

    20% {
      right: 145px;
      opacity: 1;
      filter: alpha(opacity=0);
    }

    45% {
      right: 145px;
      opacity: 0;
      filter: alpha(opacity=0);
    }
  }

  @keyframes service-img {
    0% {
      top: 0px;
    }

    50% {
      top: 30px;
    }

    100% {
      top: 0;
    }
  }

  .index-service {
    position: relative;
    padding: 75px 0 25px;
    background: linear-gradient(180.82deg, #036eb7 13.7%, #ffffff 99.77%);

    .block-title {
      color: #fff;
      font-size: 32px;
      text-align: center;
    }

    .block-info {
      color: #fff;
      font-size: 20px;
      margin-top: 16px;
      opacity: 0.64;
      text-align: center;
    }

    .service-imgs {
      padding: 15px 0 130px;
      position: relative;
      overflow: hidden;

      #service-1 {
        margin-top: 15px;
        animation: none;
        max-width: 70%;
        margin: 0 auto;
        height: auto;
      }

      #service-2 {
        position: absolute;
        display: block;
        margin: 0 auto;
        bottom: -75px;
        left: 0px;
        height: 260px;
      }

      #s1 {
        left: 20px;
        top: 20px;
        font-size: 12px;
        line-height: 1.6;
        width: 70px;
      }

      #s2 {
        position: absolute;
        right: 3px;
        top: 19px;
        font-size: 12px;
        line-height: 1.6;
        width: 100px;
      }

      #s3 {
        position: absolute;
        left: 15px;
        bottom: 58px;
        font-size: 12px;
        line-height: 1.6;
        width: 100px;
      }

      #s4 {
        position: absolute;
        right: 3px;
        bottom: 80px;
        font-size: 12px;
        line-height: 1.6;
        width: 110px;
      }
    }
  }

  .index-tech {
    padding: 0 30px;

    .tech-left {
      padding-left: 30px;
    }

    .block-content-header-title {
      font-size: 28px;
    }

    .block-content-header-info {
      font-size: 18px;
      width: 256px;
      margin: 8px auto;
    }

    .block-title {
      font-size: 20px;
    }

    .block-info {
      font-size: 16px;
      margin-top: 8px;
    }

    .step-container {
      margin: 15px auto;
      padding: 0 10px;
      position: relative;
    }

    .step-content-1 {
      width: 100%;
      padding: 0;

      .step-content-title::before {
        content: '1';
      }
    }

    .step-content-2 {
      width: 100%;
      padding: 0;

      .step-content-title::before {
        content: '2';
      }

      .step-content-info {
        border-left: 5px solid #f29600;
      }
    }

    .step-content-3 {
      width: 100%;
      padding: 0;

      .step-content-title::before {
        content: '3';
      }
    }

    .step-content-4 {
      width: 100%;
      padding: 0;

      .step-content-title::before {
        content: '4';
      }

      .step-content-info {
        border-left: 5px solid #f29600;
        opacity: 1;
      }

      .op {
        opacity: 0.56;
      }
    }

    .step-content-title {
      padding-left: 30px;
      font-size: 18px;
      line-height: 36px;

      &::before {
        background: #036fb7;
        color: #fff;
        font-size: 18px;
        line-height: 36px;
        left: -5px;
        width: 36px;
      }
    }

    .content-title-style {
      font-style: normal;
      font-weight: 700;
      font-size: 18px;
      line-height: 1.2;
      color: #036fb7;
      margin-bottom: 10px;
    }

    .step-content-info {
      font-size: 12px;
      line-height: 1.4;
      padding-left: 24px;
    }
  }

  .index-cert {
    padding: 36px;

    .cert-swiper {
      overflow: initial;
    }

    .block-title {
      font-size: 28px;
    }

    .block-info {
      font-size: 18px;
      margin-top: 6px;
    }

    .cert-cards {
      flex-direction: column;
      margin-top: 20px;
      gap: 20px;

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
  }

  .product-step {
    .product-step-head-title {
      font-size: 28px;
    }

    .block-title {
      margin-top: 30px;
      color: #036eb7;
      font-size: 28px;
      text-align: center;
    }

    .block-content-info {
      font-size: 18px;
      margin: 8px 0;
    }

    .counter-row {
      flex-direction: column;
      gap: 15px;
    }

    .steps {
      height: auto;
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      background-image: url('~images/index/step-bg.png');
    }

    .step-container {
      display: grid;
      grid-template-columns: 1fr;
      padding: 20px 0;
      gap: 20px;

      .loan {
        .step-head-title {
          color: #036fb7;
        }

        .step-content-1 {
          width: 160px;
          padding: 0 10px 8px 24px;
          border-left: 5px solid #036fb7;

          .step-content-title::before {
            content: '1';
          }

          .step-content-img {
            opacity: 1;
            z-index: 3;
          }
        }

        .step-content-2 {
          width: 160px;
          padding: 0 10px 8px 24px;
          border-left: 5px solid #036fb7;

          .step-content-title::before {
            content: '2';
          }

          &:hover,
          &:focus-within {
            .step-content-img {
              z-index: 10;
              opacity: 1;
              transition-property: opacity;
              transition-duration: 1s;
            }
          }
        }

        .step-content-3 {
          width: 160px;
          padding: 0 10px 8px 24px;
          border-left: 5px solid #036fb7;

          .step-content-title::before {
            content: '3';
          }

          &:hover,
          &:focus-within {
            .step-content-img {
              z-index: 10;
              opacity: 1;
              transition-property: opacity;
              transition-duration: 1s;
            }
          }
        }

        .step-content-4 {
          width: 160px;
          padding: 0 10px 8px 24px;
          border-left: 5px solid #036fb7;

          .step-content-title::before {
            content: '4';
          }

          &:hover,
          &:focus-within {
            .step-content-img {
              z-index: 10;
              opacity: 1;
              transition-property: opacity;
              transition-duration: 1s;
            }
          }
        }

        .step-content-5 {
          width: 160px;
          padding: 0 10px 8px 24px;

          .step-content-title::before {
            content: '5';
          }

          &:hover,
          &:focus-within {
            .step-content-img {
              z-index: 10;
              opacity: 1;
              transition-property: opacity;
              transition-duration: 1s;
            }
          }
        }

        .solgan {
          font-size: 18px;
          margin: 30px auto;
          padding: 14px;
          width: 180px;

          .em {
            font-size: 24px;
          }

          .sm {
            font-size: 12px;
          }
        }
      }

      .invest {
        .step-head-title {
          color: #f29500;
        }

        .step-content-1 {
          width: 160px;
          padding: 0 15px 40px 30px;
          border-left: 5px solid #f29500;

          .step-content-title::before {
            content: '1';
            background: #f29500;
          }

          .step-content-img {
            opacity: 1;
            z-index: 3;
          }
        }

        .step-content-2 {
          width: 160px;
          padding: 0 15px 40px 30px;
          border-left: 5px solid #f29500;

          .step-content-title::before {
            content: '2';
            background: #f29500;
          }

          &:hover,
          &:focus-within {
            .step-content-img {
              z-index: 10;
              opacity: 1;
              transition-property: opacity;
              transition-duration: 1s;
            }
          }
        }

        .step-content-3 {
          width: 160px;
          padding: 0 15px 25px 30px;
          border-left: 5px solid #f29500;

          .step-content-title::before {
            content: '3';
            background: #f29500;
          }

          &:hover,
          &:focus-within {
            .step-content-img {
              z-index: 10;
              opacity: 1;
              transition-property: opacity;
              transition-duration: 1s;
            }
          }
        }

        .step-content-4 {
          width: 160px;
          padding: 0 15px 10px 30px;

          .step-content-title::before {
            content: '4';
            background: #f29500;
          }

          &:hover,
          &:focus-within {
            .step-content-img {
              z-index: 10;
              opacity: 1;
              transition-property: opacity;
              transition-duration: 1s;
            }
          }
        }

        .solgan {
          font-size: 18px;
          margin: 30px auto;
          padding: 14px;
          width: 180px;

          .em {
            font-size: 24px;
          }

          .sm {
            font-size: 12px;
          }
        }
      }

      .step-head-title {
        font-size: 18px;
      }

      .step-head-info {
        margin-top: 8px;
        font-size: 12px;
      }

      .step-main {
        margin-top: 25px;
        display: flex;
        // width: 200px;
        flex-direction: column;
        align-items: flex-end;
      }

      .divide {
        display: none;
      }

      .step-content-title {
        font-size: 18px;
        line-height: 2;
        color: #036fb7;

        &::before {
          right: 140px;
          font-size: 18px;
          line-height: 2;
          width: 36px;
        }
      }

      .step-content-info {
        font-size: 12px;
      }

      .step-content-img {
        top: 30px;
        left: 0;
        width: 195px;
        transition-property: opacity;
        transition-duration: 1s;
        opacity: 0;
      }
    }
  }

  .charity {
    padding: 32px 0;

    .block-title {
      font-size: 28px;
    }

    .block-info {
      font-size: 18px;
      margin-bottom: 10px;
    }

    .info-title {
      font-size: 18px;
      text-align: center;
    }

    .info-content {
      margin: 12px 0 12px;
      font-size: 12px;
      line-height: 1.3;
    }

    .img-group {
      display: grid;
      grid-template-rows: 1fr 1fr;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 15px;
      position: relative;

      .img-item {
        &:hover,
        &:focus-within {
          &:nth-child(1),
          &:nth-child(4) {
            transform: scale(3.27) translate(38px, 0);
          }

          &:nth-child(2),
          &:nth-child(5) {
            transform: scale(3.27);
          }

          &:nth-child(3),
          &:nth-child(6) {
            transform: scale(3.27) translate(-38px, 0);
          }
        }
      }
    }

    .img-1251 {
      height: auto;
      margin-left: 0;
      margin-bottom: 15px;
    }
  }

  /**
    user experience
*/
  .user-experience {
    position: relative;
    height: 575px;
    background-position: top;

    .block-title {
      margin-top: 24px;
      font-size: 28px;
      line-height: 1.4;
    }

    .block-info {
      font-size: 18px;
      line-height: 1.2;
    }

    .user-comment {
      margin-top: 50px;
      justify-content: center;

      .comment-card {
        width: 270px;
        padding: 10px;

        .portrait {
          width: 40px;
        }
      }
    }

    .media {
      position: absolute;
      padding: 20px;
      bottom: 0;
      width: 101%;
      left: 15px;
      background: rgba($color: #8c97af, $alpha: 0.5);

      .圖片 {
        height: 32px;
        max-width: initial;
      }
    }

    .ue-prev,
    .ue-next {
      top: 175px;
    }
  }
}
</style>
