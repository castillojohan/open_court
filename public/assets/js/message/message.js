const message = {
    init: () => {
        message.bindButtons();
    },

    bindButtons: () => {
        const newMessage = document.querySelector("a.button.new");
        newMessage.addEventListener("click", message.handleNewMessageClick);
    },

    handleNewMessageClick: () => {
        
    }

}
document.addEventListener("DOMContentLoaded", message.init);