const filterButtons = document.querySelectorAll('.button-filter');

filterButtons.forEach((button) => {
    button.addEventListener('click', (e) => {
        e.target.classList.toggle('button-filter-selected');
        if(e.target.classList.contains('button-filter-selected')) {

        }
    })
})
