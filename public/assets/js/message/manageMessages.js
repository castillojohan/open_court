import sendMessage from './sendMessage.js';
import mercure from './mercure.js';

const manageMessages = {

    init : () => {
        const conversationLinksEl = document.querySelectorAll(".conversations section a.button.recipient")
        for (const conversation of conversationLinksEl) {
            conversation.addEventListener('click', manageMessages.handleConversationClick);
        }
        sendMessage.init();
        mercure.init();
    },

    handleConversationClick: (event)=> {
        event.preventDefault();
        const targetSection = event.target.parentNode;
        const popupNewMessage = targetSection.querySelector('a.button.recipient');
        const spanToRemove = popupNewMessage.querySelector('span');
        if(spanToRemove){
            spanToRemove.remove();
        }
        if(!targetSection.classList.contains('expanded')){
            manageMessages.removeExpandedClass();
            const elementHeight = manageMessages.calculHeightElement(targetSection.children);
            targetSection.style.height = `${elementHeight}px`;
            targetSection.classList.add('expanded');
            targetSection.scrollTop = targetSection.scrollHeight;
        }
        else{
            targetSection.classList.remove('expanded');
            targetSection.style.height = "2em";
        }    
    },

    // Calculate height of all element in the Node section
    calculHeightElement: (section) => {
        let totalHeight = 0;
        for (const element of section) {
            totalHeight += element.offsetHeight;
        }
        return totalHeight+20;
    },

    // Check in DOM, if a section already got expanded class , then remove it 
    removeExpandedClass: () => {
        const expandedDiv = document.querySelector('.conversations .expanded');
        if(expandedDiv){
            expandedDiv.classList.remove('expanded');
            expandedDiv.style.height = "2em";
        }
    }
}
document.addEventListener('DOMContentLoaded', manageMessages.init)