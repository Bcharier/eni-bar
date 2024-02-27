const mobileMenu = document.querySelector('.mobile-menu-icon')
const nav = document.querySelector('.nav-menu')
const exitMobileIcon = document.querySelector('.exit-mobile')

mobileMenu.addEventListener('click', () => {
    nav.classList.toggle('nav-open')
    mobileMenu.classList.toggle('hide-icon')
    exitMobileIcon.classList.toggle('show-icon')
})

exitMobileIcon.addEventListener('click', () => {
    nav.classList.toggle('nav-open')
    mobileMenu.classList.toggle('hide-icon')
    exitMobileIcon.classList.toggle('show-icon')
})
