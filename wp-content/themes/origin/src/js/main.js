import '../scss/main.scss';

document.documentElement.classList.add('js-enabled');

document.addEventListener('DOMContentLoaded', () => {
  // スキップリンク用
  const main = document.getElementById('main');
  if (main) main.setAttribute('tabindex', '-1');
});
