document.addEventListener("DOMContentLoaded", () => {
    //on récupère l'élément ayant pour ID partnerStatus
    const statusPartnersInput = document.querySelector("#partnerStatus");

    // selection du tbody résultat
    const tbodyResults = document.querySelector(".tbody-show-results");

    // selection du tbody original
    const tbodyContent = document.querySelector(".tbody-content");

    statusPartnersInput.addEventListener("change", function (event) {
        event.preventDefault();
        {
            // affichage tu tbody originale si option du select status définis à tous les status
            if (this.value === "all") {
                tbodyContent.style.display = "";
                tbodyResults.style.display = "none";  
            }
            else {
            //Requête au serveur en fetchant la route symfony avec en paramètre la valeur de l'input
            fetch(`filterStatus/${this.value}`)
                .then((response) => response.json())
                .then((response) => {
                    tbodyResults.innerHTML = "";
                    tbodyResults.style.display = ""; 
                    tbodyContent.style.display = "none";
                    for (const partner of response) {
                        

                        // Reconstruction du contenu du  tbody
                        const tr = document.createElement('tr');
                        tr.classList.add('partnerTr');

                        const idTd = document.createElement('td');
                        idTd.classList.add('parterId');
                        idTd.append(`${partner.id}`);

                        const partnerName = document.createElement('td');
                        partnerName.classList.add('partnerName');
                        partnerName.append(`${partner.name}`);

                        const partnerCreatedAt = document.createElement('td');
                        partnerCreatedAt.classList.add('partnerCreatedAt');
                        partnerCreatedAt.append(`${partner.createdAt}`);

                        const partnerStatus = document.createElement('td');
                        partnerStatus.classList.add('partnerStatus');

                        let status;
                        let btnStatus;
                        let clsBtn;

                        // Selon le status du partenaire, on affiche le bouton d'action (activation/désactivation)  
                        if (`${partner.status}` == 'true') {
                            status = 'Actif';
                            btnStatus = 'Désactivé';
                            clsBtn = ['btn', 'btn-outline-danger', 'activatePartner', 'deactivate'];

                        } else {
                            status = 'Inactif';
                            btnStatus = 'Activé'
                            clsBtn = ['btn', 'btn-outline-success', 'activatePartner', 'deactivate'];
                        }

                        partnerStatus.append(status);
                        const partnerTemplate = document.createElement('td');
                        partnerTemplate.classList.add("partnerTemplate");

                        // Pas oublié de modifié; pas fonctionnel
                        partnerTemplate.append(`${partner.template}`)

                        const partnerActions = document.createElement('td');
                        partnerActions.classList.add('partnerActions');

                        const a = document.createElement('a');
                        const clsA = ['btn', 'btn-outline-primary', 'btn-entity-show'];
                        a.classList.add(...clsA);
                        a.href = `/partner/${partner['id']}`;
                        a.text = 'Voir';

                        const activatePartnerBtn = document.createElement('button');
                        activatePartnerBtn.classList.add(...clsBtn);
                        activatePartnerBtn.append(btnStatus);

                        partnerActions.append(a, activatePartnerBtn);

                        tr.append(idTd, partnerName, partnerCreatedAt, partnerStatus, partnerTemplate, partnerActions);
                        tbodyResults.append(tr);
                        
                        // Duplication de code, de activatePartner.js, est ce que j'ai une autre solution ? 
                        //on récupère tous les boutons ayant la classe activationModule
                        const activatePartnersBtns = document.querySelectorAll(".activatePartner");

                        //pour chacun de ces boutons: 

                        for (const activatePartnerButton of activatePartnersBtns) {
                            const partnerStatus = activatePartnerButton.parentNode.parentNode.children[3];
                            //si un bouton est cliqué alors 
                            activatePartnerButton.addEventListener("click", (e) => {

                                //on fait une requête au serveur en fetchant la route symfony avec en paramètres mod et id ceux recup au dessus
                                fetch(`activate/${partner.id}`)
                                    .then((response) => {
                                        //on recup en réponse le tableau avec id
                                        return response.json()
                                    })
                                    .then((response) => {
                                        if (response) {
                                            activatePartnerButton.classList.remove('btn-outline-success');
                                            activatePartnerButton.classList.add('btn-outline-danger');
                                            activatePartnerButton.innerHTML = "Désactiver";
                                            partnerStatus.innerHTML = "Actif";
                                        }
                                        else {
                                            activatePartnerButton.classList.remove('btn-outline-danger');
                                            activatePartnerButton.classList.add('btn-outline-success');
                                            activatePartnerButton.innerHTML = "Activer";
                                            partnerStatus.innerHTML = "Inactif";
                                        }
                                    })
                                    .catch((error) => {
                                        console.log(error);
                                    });
                            });
                        }
                    }
                })
           }
    }
})
});