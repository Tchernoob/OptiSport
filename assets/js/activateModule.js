document.addEventListener("DOMContentLoaded", () => {
    //on récupère tous les boutons ayant la classe activatModule
    const activateModulesBtns = document.querySelectorAll(".activateModule");
    
    //pour chacun de ces boutons: 
    
    for (const activateModuleBtn of activateModulesBtns) {
        const moduleStatus = activateModuleBtn.parentNode.parentNode.children[3];
        //si un bouton est cliqué alors 
        activateModuleBtn.addEventListener("click", (e) => {
          
          //on recup - liés à ce btn - l'id du partner (id) qui sont définis en dataset dans le html
          const id = e.target.dataset.module; 
          //on fait une requête au serveur en fetchant la route symfony avec en paramètres mod et id ceux recup au dessus
          fetch(`activate/${id}`)
          .then((response) => {
            //on recup en réponse le tableau avec id
            return response.json()
          })
          .then((response) => {
             if(response) {
              activateModuleBtn.classList.remove('btn-outline-success');
              activateModuleBtn.classList.add('btn-outline-danger');
              activateModuleBtn.innerHTML = "Désactiver";
              moduleStatus.innerHTML = "Actif";
             }
             else 
             {
              activateModuleBtn.classList.remove('btn-outline-danger');
              activateModuleBtn.classList.add('btn-outline-success');
              activateModuleBtn.innerHTML = "Activer";
              moduleStatus.innerHTML = "Inactif";
             }
          })
          .catch((error) => {
            console.log(error);
          });
      });
    }
  });