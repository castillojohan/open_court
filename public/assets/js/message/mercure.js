
const mercure = {

    init:()=>{
        const url = JSON.parse(document.getElementById('mercure-url').textContent);
        const eventSource = new EventSource(url);
        eventSource.onmessage = (evt) => {
            const eventData = JSON.parse(evt.data);
            const {date, sender, recipient, content} = eventData.message;
            const [senderId, senderName] = sender;
            const [recipientId, recipientName] = recipient;
            
            // Vérifier si l'utilisateur est impliqué dans la discussion.
            const currentMemberId = document.querySelector(".conversations").getAttribute('data-member');
            
            if((currentMemberId == senderId) || (currentMemberId == recipientId)){

                //nettoyer l'objet Date()
                const orderedDate = mercure.cleanDate(date);

                // localiser la discussion.
                /* Alors, ici en gros on à deux clients le sender et le recipient, dans un cas
                le sender envoie son message,
                ( en partant du principe qu'il n'est pas possible de s'envoyer un message ) 
                    - pour mettre à jour la discussion je vérifie si sur la page du client, que le recipientId existe dans ma liste de contact
                        - si true je le selectionne
                        - si false je considère que nous sommes sur la vue du client recipient 
                        et je selectionne donc mon contact qui possede un senderID   
                */
                const conversationTargetElement = (document.querySelector(`a.button.recipient[data-member-id='${recipientId}']`) != null) 
                    ? document.querySelector(`a.button.recipient[data-member-id='${recipientId}']`)
                    : document.querySelector(`a.button.recipient[data-member-id='${senderId}']`)
                
                const conversationParentElement = conversationTargetElement.parentElement;
                const threadDivElement = conversationParentElement.childNodes[3]

                // contruire le message qui viens d'arriver
                const newTimeElement = document.createElement('time');
                newTimeElement.dateTime = orderedDate;
                newTimeElement.textContent = orderedDate;

                const newDivElement = document.createElement('div');
                newDivElement.classList.add('reponse');
                const newH4Element = document.createElement('h4');
                newH4Element.textContent = senderName;
                const newTextElement = document.createElement('p');
                newTextElement.textContent = content;

                //on verifie si l'affichage ce fait depuis l'expéditeur
                if(senderName !== conversationTargetElement.textContent){
                    newDivElement.classList.add('self');
                }
                newDivElement.classList.add('instant-message');

                // implanter dans le DOM a la suite des autres ?
                threadDivElement.append(newTimeElement);
                newDivElement.append(newH4Element);
                newDivElement.append(newTextElement);
                threadDivElement.append(newDivElement);

                // suivi du scroll ?
                if(conversationParentElement.offsetHeight > 36){
                    conversationParentElement.scrollTop = conversationParentElement.scrollHeight;

                    // adapter la taille de la div à la nouvelle taille ?
                    const conversationHeight = conversationParentElement.offsetHeight;
                    const totalNewHeight = (conversationHeight + newDivElement.offsetHeight)+30;
                    conversationParentElement.style.height = `${totalNewHeight}px`
                }else{
                    if(conversationParentElement.querySelector('a.button.recipient span') == null){
                        const newSpanElement = document.createElement('span');
                        newSpanElement.classList.add('alert');
                        newSpanElement.textContent = 'new';
                        const newMessageAlert = conversationParentElement.querySelector('a.button.recipient');
                        newMessageAlert.append(newSpanElement);
                    }
                }
               
                // cleanup this textarea
                const textAreaElement = conversationParentElement.querySelector('textarea#message');
                textAreaElement.value = '';
            }
        }    
    },

    cleanDate: (dateObject) => {
        const dateText = dateObject.date.split(' ');

        const date = dateText[0];
        const time = dateText[1];

        const splitedDate = date.split('-');
        const orderedDate = splitedDate.reverse().join('-');
        const cleanedTime = time.slice(0, 8);

        const newDate = `${orderedDate} ${cleanedTime}`;
        return newDate;
    },
}
export default mercure;