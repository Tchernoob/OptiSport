document.addEventListener("DOMContentLoaded", () => {
    //on récupère tous les boutons ayant la classe activateStructure
    const activateStructuresBtns = document.querySelectorAll(".activateStructure");
    
    //pour chacun de ces boutons: 
    
    for (const activateStructureBtn of activateStructuresBtns) {
        const structureStatus = activateStructureBtn.parentNode.parentNode.children[4];
        //si un bouton est cliqué alors 
        activateStructureBtn.addEventListener("click", (e) => {
          
          //on recup - liés à ce btn - l'id de la structure (id) qui sont définis en dataset dans le html
          const id = e.target.dataset.structure; 
          //on fait une requête au serveur en fetchant la route symfony avec en paramètres mod et id ceux recup au dessus
          fetch(`activate/${id}`)
          .then((response) => {
            //on recup en réponse le tableau avec id
            return response.json()
          })
          .then((response) => {
             if(response) {
              activateStructureBtn.classList.remove('btn-outline-success');
              activateStructureBtn.classList.add('btn-outline-danger');
              activateStructureBtn.innerHTML = "Désactiver";
              structureStatus.innerHTML = "Active";
             }
             else 
             {
              activateStructureBtn.classList.remove('btn-outline-danger');
              activateStructureBtn.classList.add('btn-outline-success');
              activateStructureBtn.innerHTML = "Activer";
              structureStatus.innerHTML = "Inactive";
             }
          })
          .catch((error) => {
            console.log(error);
          });
      });
    }
  });