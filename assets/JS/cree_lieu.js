const newLieuButton = document.querySelector('.a-button')
const cancelButton = document.querySelector('.cancel-lieu')
const newLieuForm = document.querySelector('.add-lieu-container')

newLieuButton.addEventListener('click', function() {
    newLieuForm.classList.toggle('show')
})

cancelButton.addEventListener('click', function(e) {
    e.preventDefault()
    newLieuForm.classList.toggle('show')
})

const submitButton = document.querySelector('.submit-lieu')

submitButton.addEventListener('click', async function(e) {
    e.preventDefault()

    const response = await fetch('/lieu/api/post/newLieu', {
        method: 'POST',
        body: new FormData(document.querySelector('.add-lieu-form'))
    })
        if(response.ok) {
        newLieuForm.classList.toggle('show')
    }

    fetch('/lieu/api/get/lieuByVilleId/' + document.querySelector('.select-ville').value)
    .then(response => response.json())
    .then(data => {
        data = JSON.parse(data);

        let lieu = document.querySelector('.select-lieu')
        lieu.innerHTML = ''
        data.forEach(e => {
            lieu.innerHTML += `<option value="${e.id}">${e.nom}</option>`
        })
    })
})
