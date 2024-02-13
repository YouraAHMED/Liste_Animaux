// Attend que le DOM soit entièrement chargé avant d'exécuter le script
document.addEventListener('DOMContentLoaded', function () {

    // Fonction pour gérer le clic sur le bouton "Détails"
    function detailsButtonClick(event) {
        // Récupère l'identifiant de l'animal associé au bouton
        var animalId = event.target.getAttribute('data-animal-id');
        // Récupère le conteneur des détails de l'animal
        var detailsContainer = document.getElementById('animal-details-' + animalId);

        // Vérifie si les détails sont déjà visibles
        if (detailsContainer.style.display === 'block') {
            // Les détails sont visibles, les masque
            detailsContainer.style.display = 'none';
            event.target.innerText = 'Détails';
        } else {
            // Les détails ne sont pas visibles, effectue une requête AJAX
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                // Vérifie si la requête AJAX est terminée et réussie afin d'eviter les erreurs
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Parse la réponse JSON
                    var data = JSON.parse(xhr.responseText);
                    // Affiche les détails de l'animal
                    detailsContainer.innerHTML = '<p><strong>Nom :</strong> ' + data.name + '</p>' +
                        '<p><strong>Espece :</strong> ' + data.species + '</p>' +
                        '<p><strong>Age :</strong> ' + data.age + '</p>';
                    detailsContainer.style.display = 'block';
                    event.target.innerText = 'Cacher les détails';
                }
            }
            // Ouvre une requête GET vers l'URL correspondante à l'animal
            xhr.open('GET', 'site.php?action=json&id=' + animalId, true); 
            // Envoie la requête
            xhr.send();
        }
    }

    // Récupère tous les boutons "Détails" et ajoute un écouteur d'événements à chacun
    var detailButtons = document.querySelectorAll('.animal-details-button');
    detailButtons.forEach(function (button) {
        button.addEventListener('click', detailsButtonClick);
    });
});
