document.addEventListener("DOMContentLoaded", () => {
    const activateModulesBtns = document.querySelectorAll(".activationModule");
    
    for (const activateModulesBtn of activateModulesBtns) {
        activateModulesBtn.addEventListener("click", (e) => {
          const mod = e.target.dataset.mod; 
          const id = e.target.dataset.partner; 
          const mods = []; 
          fetch(`mod/${mod}/activate/${id}`)
          .then((response) => {
            return response.json()
          })
          .then((json) => {
            [...json].forEach(mod => {
                mods.push(mod['id']); 
            }); 
            if(mods.includes(parseInt(mod)))
            {
              activateModulesBtn.innerHTML = "Désactiver"
            }
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
  