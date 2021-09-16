const toTop = document.querySelector('.scrollToTop')
const offset = 1800

 window.onscroll = function() {scrollFunction()};

 function scrollFunction() {
 if (document.body.scrollTop > offset || document.documentElement.scrollTop > offset) {
     toTop.style.display = "block";
 } else {
     toTop.style.display = "none";
 }
 }
toTop.addEventListener('click', function () {
    window.scrollTo(0,0)
})
