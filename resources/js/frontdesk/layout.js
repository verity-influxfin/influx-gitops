import createApp from './app-client'

import "@splidejs/splide/dist/css/themes/splide-default.min.css";

$(() => {
  const app = createApp()
  app.$mount('#web_index')
  $('.back-top').fadeOut();
  document.querySelector(".icon-hamburger").addEventListener("click", () => {
    document.querySelector(".rwd-list").classList.toggle("-active")
  })
  document.querySelectorAll(".rwd-list .item").forEach((v) => {
    v.addEventListener("click", (e) => {
      Array.prototype.filter.call(document.querySelectorAll(".rwd-list .item"), (j) => {
        return v !== j
      }).forEach((v) => {
        v.classList.remove("-active")
      })
      v.classList.toggle("-active")
    })
  })
  $(document).scroll(function () {
    AOS.refresh();
    window.dispatchEvent(new Event("resize"));
    var y = $(this).scrollTop();
    if (y > 800) {
      $('.back-top').fadeIn();
    } else {
      $('.back-top').fadeOut();
    }
  });
});
