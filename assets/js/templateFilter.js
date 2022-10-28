document.addEventListener("DOMContentLoaded", () => {
    //on récupère l'élément ayant pour ID partnerName
    const nameTemplatesInput = document.querySelector("#templateName");

    const tbodyResults = document.querySelector(".tbody-show-results");
    const tbodyContent = document.querySelector(".tbody-content");


    // selection du tbody ayant pour classe table-group-divider
    const resultat = document.querySelector(".table-group-divider");

    nameTemplatesInput.addEventListener("keyup", function (e) {
        e.preventDefault();

        //affichage du contenu de base si l'input est vide
        if(this.value.length === 0 && tbodyContent.style.display === "none") {
            tbodyContent.style.display = "";
            tbodyResults.style.display = "none";  
        }

        // Filtre pour la validité de la recherche selon keycode JS dans l'Input nom du Partenaire
        if ((this.value.length > 1) &&
            (e.keyCode == 32 ||
            (e.keyCode > 64 && e.keyCode < 91) ||
            (e.keyCode > 96 && e.keyCode < 123) ||
            (e.keyCode == 8 && nameTemplatesInput.value !== ""))) {
            //Requête au serveur en fetchant la route symfony avec en paramètre la valeur de l'input
            fetch(`filter/${this.value}`)
                .then((response) => response.json())
                .then((response) => {
                    tbodyResults.innerHTML = "";
                    tbodyResults.style.display = ""; 
                    tbodyContent.style.display = "none";
                    for (const template of response) {
                        // Reconstruction du contenu du  tbody
                        console.log(template);
                        const tr = document.createElement('tr');
                        tr.classList.add('templateTr');

                        const idTd = document.createElement('td');
                        idTd.classList.add('templateId');
                        idTd.append(`${template.id}`);

                        const templateName = document.createElement('td');
                        templateName.classList.add('templateName');
                        templateName.append(`${template.name}`);

                        const templateCreatedAt = document.createElement('td');
                        templateCreatedAt.classList.add('partnerCreatedAt');
                        templateCreatedAt.append(`${template.createdAt}`);

                        const templateStatus = document.createElement('td');
                        templateStatus.classList.add('partnerStatus');

                        let status;
                        let btnStatus;
                        let clsBtn;

                        // Selon le status du partenaire, on affiche le bouton d'action (activation/désactivation)  
                        if (`${template.status}` == 'true') {
                            status = 'Actif';
                            // btnStatus = 'Désactivé';
                            // clsBtn = ['btn', 'btn-outline-danger', 'activatePartner', 'deactivate'];

                        } else {
                            status = 'Inactif';
                            // btnStatus = 'Activé'
                            // clsBtn = ['btn', 'btn-outline-success', 'activatePartner', 'deactivate'];
                        }

                        templateStatus.append(status);

                        const templateModules = document.createElement('td');
                        templateModules.classList.add('templateModules');
                        templateModules.append(`${template.modules}`);
                        console.log(template.modules);
                        // // Pas oublié de modifié; pas fonctionnel
                        // partnerTemplate.append(`${partner.template}`)

                        const templateActions = document.createElement('td');
                        templateActions.classList.add('partnerActions');

                        const a = document.createElement('a');
                        const clsA = ['btn', 'btn-outline-primary', 'btn-entity-show'];
                        // a.classList.add(...clsA);
                        // a.href = `/template/${template['id']}`;
                        a.text = 'Voir';

                        // const activatePartnerBtn = document.createElement('button');
                        // activatePartnerBtn.classList.add(...clsBtn);
                        // activatePartnerBtn.append(btnStatus);

                        templateActions.append(a);

                        tr.append(idTd, templateName, templateCreatedAt, templateStatus, templateModules, templateActions);
                        tbodyResults.append(tr);
                        
                    }
            })
            .catch((error) => {
                console.log(error);
            });
            }
    
            else {
    
            }
        })
    })