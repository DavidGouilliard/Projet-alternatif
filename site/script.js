
const background = document.querySelector('.background-image');

window.addEventListener('scroll', () => {
  if (window.scrollY >= 300) {
    background.classList.add('active');
  } else {
    background.classList.remove('active');
  }
});
