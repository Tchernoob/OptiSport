document.addEventListener("DOMContentLoaded", () => {
    //on récupère tous les boutons ayant la classe activationModule
    const activatePartnersBtns = document.querySelectorAll(".activatePartner");
    
    //pour chacun de ces boutons: 
    
    for (const activatePartnerBtn of activatePartnersBtns) {
        const partnerStatus = activatePartnerBtn.parentNode.parentNode.children[2];
        //si un bouton est cliqué alors 
        activatePartnerBtn.addEventListener("click", (e) => {
          
          //on recup - liés à ce btn - l'id du partner (id) qui sont définis en dataset dans le html
          const id = e.target.dataset.partner; 
          //on fait une requête au serveur en fetchant la route symfony avec en paramètres mod et id ceux recup au dessus
          fetch(`activate/${id}`)
          .then((response) => {
            //on recup en réponse le tableau avec id
            return response.json()
          })
          .then((response) => {
             if(response) {
              activatePartnerBtn.innerHTML = "Désactiver";
              partnerStatus.innerHTML = "Actif";
             }
             else 
             {
              activatePartnerBtn.innerHTML = "Activer";
              partnerStatus.innerHTML = "Inactif";
             }
          })
          .catch((error) => {
            console.log(error);
          });
      });
    }
  });