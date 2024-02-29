const showDetailsButtons = document.querySelectorAll('.show-details-sortie');
const detailsContainer = document.querySelector('.detail-mobile-container');
const infoContainer = document.querySelector('.info-container');

showDetailsButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        const sortieId = e.target.nextElementSibling.value;
        fetchSortieDetails(sortieId);
        detailsContainer.classList.toggle('show');
        infoContainer.innerHTML = '';
    });
});

function fetchSortieDetails(sortieId) {
    fetch('/sortie/api/get/' + sortieId)
    .then(response => response.json())
    .then(data => {

        infoContainer.innerHTML = `
            <h2>Détail de la sortie ${data.nom}</h2>
            <p><span>Date et heure : </span>${data.dateHeureDebut}</p>
            <p><span>Ville : </span>${data.ville['nom']}</p>
            <p><span>Lieu : </span>${data.lieu['nom']}</p>
            <p><span>Rue : </span>${data.lieu['rue']}</p>
            <p><span>Code Postal : </span>${data.ville['codePostal']} </p>
            <p><span>Latitude : </span>${data.lieu['latitude']}</p>
            <p><span>Longitude : </span>${data.lieu['longitude']}</p>
            <p><span>Clôture : </span>${data.limite}</p>
            <p><span>Nombre d'inscrits : </span>${data.participants.length} / ${data.nbInscriptionsMax}</p>
            <p><span>Durée : </span>${data.duree} minutes </p>
            <p><span>Informations : </span>${data.infosSortie}</p>
        </div>
        `;

const tableBody = document.querySelector('.table-body');

    tableBody.innerHTML = '';

    data.participants.forEach(participant => {
            tableBody.innerHTML += `
            <tr>
                <td>${participant['prenom']} ${participant['nom']}</td>
                <td>${participant['site']}</td>
            </tr>
            `;
        })
    })
}

const exitDetailsButton = document.querySelector('.exit-details');

exitDetailsButton.addEventListener('click', () => {
    detailsContainer.classList.toggle('show')
})