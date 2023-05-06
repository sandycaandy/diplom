//слайдер

let slider = document.querySelector('.reviews__list');
let btn_left = document.querySelector('.slider__button--prev');
let btn_right = document.querySelector('.slider__button--next');

let slide = document.querySelectorAll('.reviews__item');

let i = 0;

btn_right.addEventListener('click', () => {
  i++ 
  changeBox()
  console.log(i);
})

btn_left.addEventListener('click', () => {
  i-- 
  changeBox()
  console.log(i);
})

function changeBox() {

  if(i > slide.length - 1 ) {
    i = 0
  }
  else if(i < 0) {
    i = slide.length - 1
  }
  slider.style.transform = `translateX(${-i * 1200}px)`
}