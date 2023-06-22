window.CardButton = class CardButton {
  constructor(content, classes, onClick) {
    // button
    const buttonEl = document.createElement('button')
    buttonEl.classList.add(...classes)
    buttonEl.innerHTML = content

    // click event listener
    if (typeof onClick === 'function') {
      buttonEl.addEventListener('click', onClick)
    }

    this = buttonEl
  }
}