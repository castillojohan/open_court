import flashesError from "../flashes-error/flashesError.js"

const manageResponse = {
    
        //Le but est de prendre un message contenu dans la réponse et de lafficher comme un flash message 
        //dans la vue.
        
        //1. Controller si il y a des messages ?
        
        manageResponse: (response) => {
            if(Object.keys(response).includes('error') || Object.keys(response).includes('success')){
                // on récupère justa la valeur de la clé pour l'envoyé à la méthode build
                const key = Object.keys(response)[0];
                const message = Object.values(response)[0];
                const messageElement = manageResponse.buildErrorDisplay(key, message);
                const targetLocation = manageResponse.domSibling();
                manageResponse.insertInto(messageElement, targetLocation);
            }
        },

        //2. Cibler la div ou va etre stocké le message
        domSibling: () => {
            const targetedDiv = document.querySelector('section>div:first-child').parentNode;
            return targetedDiv;
        },
        
        //3. Créer l'élément div avec les class error/success ( on peut réutiliser "flashes error/success")
        buildErrorDisplay: (messageKey, messageContent) => {
            const divEl = document.createElement('div');
            divEl.classList.add(`flashes`);
            divEl.classList.add(messageKey);

            const pEl = document.createElement('p');
            pEl.textContent = messageContent;

            const spanEl = document.createElement('span');
            spanEl.classList.add('btn-close');
            spanEl.ariaLabel = "Close";
            spanEl.textContent = "X"

            divEl.appendChild(pEl);
            divEl.appendChild(spanEl);
            return divEl
        },
        
        //4. Afficher le message dans le DOM
        insertInto: (messageElement, targetLocation) => {
            targetLocation.prepend(messageElement);
            // 5. Recharger le module flashes error .
            flashesError.init();
        }
}

export default manageResponse;