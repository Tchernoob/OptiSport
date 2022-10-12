document.addEventListener("DOMContentLoaded", () => {
    //on récupère tous les boutons ayant la classe activationModule
    const activateModulesBtns = document.querySelectorAll(".activationModule");
    
    //pour chacun de ces boutons: 
    
    for (const activateModulesBtn of activateModulesBtns) {
        //si un bouton est cliqué alors 
        activateModulesBtn.addEventListener("click", (e) => {
            //on recup - liés à ce btn - l'id du mod (mod) et l'id du partner (id) qui sont définis en dataset dans le html
          const mod = e.target.dataset.mod; 
          const id = e.target.dataset.partner; 
          const mods = []; 
          //on fait une requête au serveur en fetchant la route symfony avec en paramètres mod et id ceux recup au dessus
          fetch(`mod/${mod}/activate/${id}`)
          .then((response) => {
            //on recup en réponse le tableau avec id et name des mods du partner 
            return response.json()
          })
          .then((json) => {
            //on créé un tableau avec tous les id de mods présents dans la réponse (mods du partner)
            [...json].forEach(mod => {
                mods.push(mod['id']); 
            }); 
            //on regarde si le mod focus est présent dans ce tableau
            //si oui ça veut dire qu'il est actif et on met le texte à désactiver
            if(mods.includes(parseInt(mod)))
            {
              activateModulesBtn.innerHTML = "Désactiver"
            }
            //si non alors il n'est pas actif et on met le texte à activer
            else 
            {
              activateModulesBtn.innerHTML = "Activer"
            }
          })
          .catch((error) => {
            console.log(error);
          });
      });
    }
  });
  