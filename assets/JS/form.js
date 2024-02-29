function sauvegarderDansSessionStorage(formID) {
    // Récupérer le formulaire
    var form = document.getElementById(formID);

    // Ajouter un événement de soumission au formulaire
    form.addEventListener("submit", function(event) {
        // Empêcher le comportement par défaut du formulaire (rechargement de la page)
        event.preventDefault();

        // Sérialiser les données du formulaire en JSON et les sauvegarder dans le sessionStorage
        var formData = {};
        var inputs = this.getElementsByTagName("input");
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type === 'checkbox') {
                formData[inputs[i].name] = inputs[i].checked.toString(); // Stocker la valeur booléenne en tant que chaîne de caractères
            } else {
                formData[inputs[i].name] = inputs[i].value;
            }
        }
        var selects = this.getElementsByTagName("select");
        for (var j = 0; j < selects.length; j++) {
            formData[selects[j].name] = selects[j].value;
        }

        // Sauvegarder les données dans le sessionStorage
        sessionStorage.setItem(formID + "-formData", JSON.stringify(formData));

        // Soumettre le formulaire
        this.submit();
    });

    // Récupérer les données sauvegardées depuis le sessionStorage
    var savedFormData = sessionStorage.getItem(formID + "-formData");

    // Si des données sont présentes dans le sessionStorage, les utiliser
    if (savedFormData) {
        savedFormData = JSON.parse(savedFormData);
        // Parcourir les champs du formulaire pour mettre à jour leurs valeurs
        for (var field in savedFormData) {
            if (savedFormData.hasOwnProperty(field)) {
                var element = form.elements[field];
                if (element) {
                    if (element.type === 'checkbox') {
                        // Si c'est une case à cocher, vérifier si elle doit être cochée
                        if (savedFormData[field] === 'true') {
                            element.checked = true;
                        } else {
                            element.checked = false;
                        }
                    } else {
                        // Sinon, mettre à jour la valeur normalement
                        element.value = savedFormData[field];
                    }
                }
            }
        }
    }
}

// Appeler la fonction pour chaque formulaire que vous souhaitez traiter
sauvegarderDansSessionStorage("filter_sortie");
// Ajoutez plus d'appels pour d'autres formulaires si nécessaire



function reAfficherDonneesSauvegardees(formID) {
    // Récupérer le formulaire
    var form = document.getElementById(formID);

    // Récupérer les données sauvegardées depuis le sessionStorage
    var savedFormData = sessionStorage.getItem(formID + "-formData");

    // Si des données sont présentes dans le sessionStorage, les utiliser
    if (savedFormData) {
        savedFormData = JSON.parse(savedFormData);
        // Parcourir les champs du formulaire pour mettre à jour leurs valeurs
        for (var field in savedFormData) {
            if (savedFormData.hasOwnProperty(field)) {
                var element = form.elements[field];
                if (element) {
                    if (element.type === 'checkbox') {
                        // Si c'est une case à cocher, vérifier si elle doit être cochée
                        if (savedFormData[field] === 'true') {
                            element.checked = true;
                        } else {
                            element.checked = false;
                        }
                    } else {
                        // Sinon, mettre à jour la valeur normalement
                        element.value = savedFormData[field];
                    }
                }
            }
        }
    }
}

// Appeler la fonction pour chaque formulaire que vous souhaitez re-afficher les données sauvegardées
reAfficherDonneesSauvegardees("filter_sortie");
// Ajoutez plus d'appels pour d'autres formulaires si nécessaire

// (function() {
//     document.getElementById('filter_sortie_filter');
//     window.onload=function(){
//         var auto = setTimeout(function(){ submitform(); }, 100);

//         function submitform(){
//           alert('test');
//           document.forms["filter_sortie"].submit();
//         }
//     }



//     let options = document.querySelectorAll('#sortie_lieu option');
//     options.forEach(o => o.remove());
// })();

document.addEventListener("DOMContentLoaded", function() {
    // Vérifier si le paramètre "submitted" est présent dans l'URL
    var urlParams = new URLSearchParams(window.location.search);
    var submitted = urlParams.get('submitted');
    
    // Si "submitted" n'est pas présent, soumettre automatiquement le formulaire
    if (!submitted) {
        document.getElementById("filter_sortie").submit();
    }
});