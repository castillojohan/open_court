import domModule from "./domModule.js";
import domObserver from "./domObserver.js";

const message = {

    init: () => {
        message.bindButtons();
    },

    bindButtons: () => {
        const newMessage = document.querySelector("a.button.new");
        newMessage.addEventListener("click", message.handleNewMessageClick);

        const conversations = document.querySelectorAll('section.conversations a');
        for(const conversation of conversations){
            conversation.addEventListener("click", message.handleConversationClick);
        }
    },

    handleNewMessageClick: async (event) => {
        event.preventDefault();
        if(!domObserver.lookup()){
            const membersList = await message.loadMembers(event.target.href);
            domModule.buildMessagingInterface(membersList);
        }
    },

    loadMembers: async (link) => {
        try{
            const response = await fetch(link, 
            {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            });
            const members = await response.json();
            return members;
        }
        catch(error){
            console.log(error);
            throw error;
        }
    },

    handleConversationClick : async (event) => {
        event.preventDefault();
        const messages = await message.loadConversation(event.target.href);
        //domModule.createConversation();
    },

    loadConversation: async (link) => {
        try {
            const response = fetch(link,
            {
                method: 'GET',
                header: {
                    'Content-Type': 'application/json',
                },
            });
            const messages = await response.json;
            console.log(messages);
        } catch (error) {
            
        }
    }


}
document.addEventListener("DOMContentLoaded", message.init);