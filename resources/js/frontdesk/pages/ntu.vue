<template>
  <div class="main-content">
    <div class="banner">
      <img src="../asset/images/ntu_banner.jpg" class="banner-img" />
    </div>
    <div>
      <div class="title-dot-up">
        <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
        <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
        <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
        <div class="dots" style="opacity: 0.75; width: 6px; height: 6px"></div>
      </div>
      <div class="d-flex justify-content-center">
        <img src="../asset/images/太陽.svg" class="img-icon" alt="" />
        <img src="../asset/images/孩子健康 看見希望.svg" class="img-text-1" />
      </div>
      <div class="title-dot-down">
        <div class="dots" style="opacity: 0.75; width: 6px; height: 6px"></div>
        <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
        <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
        <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="content-text col-auto">
        <p>
          孩子愈來愈少了，<br />
          我們未來主人翁的健康好重要 <br />
          一個都不能少，<br />
          兒童健康不能等！<br />
        </p>
        <p>
          孩子生病了，<br />
          看著孩子辛苦面對病魔的眼神<br />
          我們好心疼、好焦急、好擔心，<br />
          我們知道不能再等了！
        </p>
        <p>
          我們比好多生病的孩子幸福好多，<br />
          我們擁有健康，擁有福氣
        </p>
      </div>
      <div class="col-auto">
        <div class="yt-top">
          <div class="yt-top-icon"></div>
          <div class="yt-top-text">台大兒童健康基金會</div>
          <router-link to="/borrowLink" target="_blank">
            <button class="btn btn-outlined yt-top-donate">捐款去</button>
          </router-link>
        </div>
        <div class="yt-iframe">
          <div class="video-container">
            <iframe
              width="560"
              height="315"
              src="https://www.youtube.com/embed/5Bxx0sY9Ns0"
              title="YouTube video player"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen
            ></iframe>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center align-items-end">
      <div class="col-auto">
        <img
          src="../asset/images/dinosaurs.png"
          class="dinosaurs-img"
          alt="dinosaurs"
        />
      </div>
      <div class="col-auto content-text dinosaurs-text">
        <p>
          只有靠著您<br />
          與大家一起透過分享一點小小的幫忙<br />
          支持更好的兒童醫療與健康照顧<br />
        </p>
        <p>
          可以--<br />
          讓孩子的健康，在面對疾病受苦時，有翻盤的機會<br />
          讓孩子有機會得到最好的醫療照顧<br />
          讓孩子有機會發揮最佳潛力，健康快樂成長<br />
        </p>
        <p>
          這一切需要我們更多關注與支持，<br />
          因為隨時都有孩子生重病，<br />
          孩子的生命沒有時間再等了！<br />
        </p>

        <p>
          現在就需要您的愛心參與<br />
          讓生病的孩子，能有更好的健康未來<br />
          孩子健康，才能看見希望<br />
        </p>
      </div>
    </div>
    <div class="row justify-content-center organizer">
      <div class="col-auto">
        <div class="title-dot-up">
          <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
          <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
          <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
          <div
            class="dots"
            style="opacity: 0.75; width: 6px; height: 6px"
          ></div>
        </div>
        <div class="d-flex justify-content-center">
          <img src="../asset/images/彩虹.svg" class="img-moon" alt="" />
          <img src="../asset/images/活動單位.svg" class="img-text-2" />
        </div>
        <div class="title-dot-down">
          <div
            class="dots"
            style="opacity: 0.75; width: 6px; height: 6px"
          ></div>
          <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
          <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
          <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
        </div>
        <div class="fund-img">
          <img src="../asset/images/fund.png" alt="" />
        </div>
        <div class="fund-text">
          <p>
            財團法人台大兒童基金會以提昇兒童健康福祉為宗旨，
            希望經由您的支持，提升兒童醫院之最佳醫療服務。
          </p>
          <div>致力推動下列事項：</div>
          <div>一、協助兒童醫院提昇最佳醫療。</div>
          <div>二、發展兒童醫療研究、使醫療得以創新與精進。</div>
          <div>三、協助培育兒童醫療人才。</div>
          <div>四、促進社會人士對兒童醫療及兒童健康之關懷。</div>
          <div>五、辦理其他提昇兒童健康事項。</div>
        </div>
      </div>
      <div class="col-auto">
        <div class="title-dot-up">
          <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
          <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
          <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
          <div
            class="dots"
            style="opacity: 0.75; width: 6px; height: 6px"
          ></div>
        </div>
        <div class="d-flex justify-content-center">
          <img src="../asset/images/月亮.svg" class="img-icon" />
          <img
            src="../asset/images/如何捐款.svg"
            class="img-text-3"
            alt="如何捐款"
          />
          <img src="../asset/images/NTUCHwhale.png" class="img-whale" />
        </div>
        <div class="title-dot-down">
          <div
            class="dots"
            style="opacity: 0.75; width: 6px; height: 6px"
          ></div>
          <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
          <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
          <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
        </div>
        <div class="donate-text">
          <p>普匯收到您的善款</p>
          <p>
            立即轉付「財團法人台大兒童健康基金會」，捐款成功後
            您將收到該基金會致贈的「電子感謝狀」及「紙本收據」，
            非常感謝您的善心善行！
          </p>
        </div>
        <div class="go-donate-out">
          <div class="go-donate">
            <img class="go-donate-title" src="../asset/images/具名捐款.svg" />
            <img class="go-donate-text" src="../asset/images/愛心.svg" />
            <img class="go-donate-bird" src="../asset/images/NTUCHbird.png" />
            <div class="go-donate-text"></div>
            <router-link to="/borrowLink" target="_blank">
              <div class="go-donate-button"></div>
            </router-link>
          </div>
        </div>
      </div>
    </div>
    <div class="footer">
      <div>
        <img
          src="../asset/images/charitable-footer.png"
          class="footer-img"
          alt=""
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
.main-content {
  background-image: url("../asset/images/ntu-bg.jpg");
  padding-bottom: 50px;
}
.banner {
  margin-bottom: 60px;
  .banner-img {
    width: 100%;
  }
}
.dots {
  background: #3670d3;
  border-radius: 100rem;
  margin-bottom: 12px;
  margin-left: auto;
  margin-right: auto;
}
.title-text {
  color: rgba($color: #fff, $alpha: 0);
  background: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
  background-clip: text;
  font-size: 32px;
  font-weight: bolder;
  width: 100%;
  text-align: center;
  margin: 2px auto 14px;
}
.content-text {
  padding: 0 15px;
  margin: 62.5px 90px 65px 0;
  font-family: NotoSansTC;
  font-size: 30px;
  font-weight: 500;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.5;
  letter-spacing: 1.2px;
  text-align: left;
  color: #7d7d7d;
}
.yt-top {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 40px;
  .yt-top-icon {
    width: 76px;
    height: 76px;
    background-image: url("../asset/images/NTUCH02F.png");
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center center;
  }
  .yt-top-text {
    margin: 0 24px;
    font-family: NotoSansTC;
    font-size: 30px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.33;
    letter-spacing: 1.5px;
    text-align: left;
    color: #0085c4;
  }
  .yt-top-donate {
    font-family: NotoSansTC;
    font-size: 34px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.18;
    letter-spacing: 1.7px;
    text-align: left;
    color: #0085c4;
    border-radius: 33px;
    padding: 8px 34px;
    border: solid 2px #0085c4;
  }
}
.yt-iframe {
  max-width: 670px;
  padding: 0 15px;
  margin-bottom: 62.5px;
  .video-container {
    position: relative;
    padding-bottom: 56.25%;
    padding-top: 30px;
    height: 0;
    overflow: hidden;
  }

  .video-container iframe,
  .video-container object,
  .video-container embed {
    position: absolute;
    top: 0;
    width: 100%;
    height: 100%;
  }
}
.dinosaurs-img {
  margin-bottom: 65px;
}
.dinosaurs-text {
  margin: 0 0 0 40px;
}
.fund-img {
  display: flex;
  justify-content: center;
}
.fund-text {
  position: relative;
  margin: 0 auto 63px;
  padding: 0 15px;
  max-width: 535px;
  font-family: NotoSansTC;
  font-size: 20px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.8;
  letter-spacing: 1px;
  text-align: left;
  color: #000;
  .img-dec-1 {
    position: absolute;
    top: -150px;
    right: -235px;
  }
}
.organizer {
  margin: 80px 0;
  grid-column-gap: 120px;
}
.donate-text {
  position: relative;
  margin: 0 auto 52px;
  padding: 0 15px;
  max-width: 578px;
  font-family: NotoSansTC;
  font-size: 20px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.4;
  letter-spacing: 1px;
  text-align: left;
  color: #000;
}
.go-donate-out {
  position: relative;
  margin: 0 auto;
  max-width: 1100px;
  .img-dec-4 {
    position: absolute;
    right: 140px;
  }
  .img-dec-5 {
    position: absolute;
    left: 240px;
    bottom: 0px;
  }
  .img-dec-6 {
    position: absolute;
    left: 140px;
    bottom: 40px;
  }
}
.go-donate {
  position: relative;
  background-image: url("../asset/images/紙條.png");
  background-size: cover;
  background-repeat: no-repeat;
  margin: 0 auto 50px;
  width: 520px;
  height: 330px;
  padding: 20px 15px;
  display: flex;
  flex-direction: column;
  align-items: center;
  .go-donate-title {
    margin: 10px 0;
    transform: rotate(-1deg);
    width: 225px;
  }
  .go-donate-text {
    transform: rotate(-3deg);
  }
  .go-donate-bird {
    position: absolute;
    height: 150px;
    left: -20px;
    top: 140px;
  }
  .go-donate-button {
    margin: 15px 0 0 15px;
    transform: rotate(-1deg);
    height: 60px;
    width: 180px;
    background-repeat: no-repeat;
    background-position: center;
    background-image: url("../asset/images/捐款去.svg");
  }
}
.img-text-1 {
  width: 400px;
}
.img-text-2 {
  width: 200px;
}
.img-text-3 {
  width: 200px;
}
.img-whale {
  width: 230px;
  position: absolute;
  right: -10px;
  top: -100px;
}
.img-icon {
  margin-right: 16px;
  width: 90px;
}
.footer {
  margin: auto;
  max-width: 1300px;
}
.footer-img {
  max-width: 100%;
}
@media screen and (max-width: 767px) {
  .img-icon {
    margin-right: 8px;
    width: 60px;
  }
  .img-text-1 {
    width: 245px;
  }
  .img-text-2 {
    width: 125px;
  }
  .img-text-3 {
    width: 125px;
  }
  .banner {
    margin-bottom: 30px;
  }
  .yt-top {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 30px;
    .yt-top-icon {
      width: 46px;
      height: 46px;
      background-image: url("../asset/images/NTUCH02F.png");
      background-repeat: no-repeat;
      background-size: contain;
      background-position: center center;
    }
    .yt-top-text {
      margin: 0 6px;
      font-family: NotoSansTC;
      font-size: 18px;
    }
    .yt-top-donate {
      font-size: 20px;
      padding: 2px 12px;
    }
  }
  .dinosaurs-img{
      max-width: 85%;
      margin-bottom: 20px;
      display: block;
      margin-left: auto;
  }
  .dinosaurs-text{
      padding: 0 35px;
  }
  .content-text {
    margin: 30px auto;
    font-size: 16px;
  }
  .organizer{
      margin: 30px 0;
      padding: 0 10px;
  }
  .fund-text {
    width: 300px;
    margin: 0 auto 40px;
    font-size: 14px;
    .img-dec-1 {
      width: 150px;
      top: 50px;
      right: -50px;
    }
  }
  .donate-text {
    margin: 0 auto;
    width: 300px;
    font-size: 14px;
    .img-dec-2 {
      width: 80px;
      top: -70px;
      left: 200px;
    }
    .img-dec-3 {
      height: 177px;
      right: -55px;
      bottom: 7px;
    }
  }
  .go-donate-out {
    .img-dec-4 {
      width: 95px;
      right: 0;
      bottom: 0;
    }
    .img-dec-5 {
      width: 55px;
      left: 20px;
      bottom: 0;
    }
    .img-dec-6 {
      width: 55px;
      left: 0;
      bottom: 40px;
    }
  }
  .img-whale{
      width: 130px;
      right: 10px;
      top: -40px;
  }
  .go-donate {
    position: relative;
    margin: 30px auto;
    height: 210px;
    width: 300px;
    .go-donate-title {
      margin: 0;
      width: 145px;
    }
    .go-donate-text {
      height: 60px;
    }
    .go-donate-bird{
        height: 90px;
        left: -45px;
        top: 100px;
    }

    .go-donate-button {
      margin: 5px 0 0 5px;
      height: 50px;
      width: 130px;
    }
  }
}
</style>
