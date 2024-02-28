(function() {
    let options = document.querySelectorAll('#lieu_ville option');
    options.forEach(o => o.remove());
})();

// JavaScript pur pour détecter le changement de sélection de la ville
document.getElementById('lieu_ville').addEventListener('change', function() {
    var villeId = this.value;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/get-lieux-by-ville/' + villeId, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var lieux = JSON.parse(xhr.responseText);
            var lieuSelect = document.getElementById('sortie_lieu');
            // Effacez les options existantes
            lieuSelect.innerHTML = '';
            // Ajoutez les nouvelles options basées sur les lieux retournés
            if (Object.keys(lieux).length === 0) {
                // Si aucun lieu n'est disponible
                var noResultOption = document.createElement('option');
                noResultOption.disabled = true;
                noResultOption.selected = true;
                noResultOption.text = 'Aucun lieu disponible';
                lieuSelect.appendChild(noResultOption);
                // Mettre à zéro les champs "lieu_rue", "lieu_longitude" et "lieu_latitude"
                document.getElementById('lieu_rue').value = '';
                document.getElementById('lieu_longitude').value = '';
                document.getElementById('lieu_latitude').value = '';
            } else {
                // Si des lieux sont disponibles
                lieux.forEach(k => {
                    let option = document.createElement('option');
                    option.value = k['id'];
                    option.text = k['nom'];
                    lieuSelect.appendChild(option);
                });
                /*
                for (let k in lieux) {
                    }
                }
                */
                // Une fois la liste déroulante Lieu remplie pour la première fois,
                // mettez à jour les champs "lieu_rue", "lieu_longitude" et "lieu_latitude"
                updateLieuFields();
            }
        }
    };
    xhr.send();
});

// JavaScript pur pour détecter le changement de sélection de la ville
document.getElementById('lieu_ville').addEventListener('change', function() {
    var villeId = this.value;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/get-ville-cp/' + villeId, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var villeDetails = JSON.parse(xhr.responseText);
            document.getElementById('ville_codePostal').value = villeDetails.cp;
        }
    };
    xhr.send();
});

// Fonction pour mettre à jour les champs de lieu
function updateLieuFields() {
    var lieuId = document.getElementById('sortie_lieu').value;
    if (!lieuId) { // Vérifier si le lieu est disponible
        document.getElementById('lieu_rue').value = '';
        document.getElementById('lieu_longitude').value = '';
        document.getElementById('lieu_latitude').value = '';
        return; // Quitter la fonction si le lieu est indisponible
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/get-lieu-details/' + lieuId, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var lieuDetails = JSON.parse(xhr.responseText);
            // Mettre à jour les champs avec les détails du lieu
            document.getElementById('lieu_rue').value = lieuDetails.rue;
            document.getElementById('lieu_longitude').value = lieuDetails.longitude;
            document.getElementById('lieu_latitude').value = lieuDetails.latitude;
        }
    };
    xhr.send();
}

document.getElementById('sortie_lieu').addEventListener('change', function() {
    updateLieuFields();
});
