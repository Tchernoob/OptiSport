document.addEventListener("DOMContentLoaded", () => {
    const namePartnersInput = document.querySelector("#partnerName");

    const resultat = document.querySelector(".resultat");
        namePartnersInput.addEventListener("keyup", function(e) {
            e.preventDefault();

            if ((this.value.length > 1) &&
            (e.keyCode == 32 ||
              (e.keyCode > 64 && e.keyCode < 91) ||
              (e.keyCode > 96 && e.keyCode < 123) ||
              (e.keyCode == 8 && namePartnersInput.value !== ""))) 
              
              {
                
                fetch(`filter/${this.value}`)
                .then((response) => response.json())
                .then((response) => {
                    resultat.innerHTML= ""; 
                    console.log(response); 
                    const p = document.createElement('p'); 
                    for (const partner of response)
                    {
                        p.innerHTML += `${partner.name} `
                        resultat.append(p); 
                    }
               
                
                })
              }

        })
});