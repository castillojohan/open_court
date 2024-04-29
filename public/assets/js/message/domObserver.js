

const domObserver = {
    init:() => {
        const buttonClose = document.querySelector('.btn-close');
        const grandpaEl = buttonClose.parentElement.parentElement;
        buttonClose.addEventListener("click", function(){
            grandpaEl.remove();
        })
    },

    lookup: () =>{
        if(document.querySelector("div.messages-recipient")){
            return true;
        }
        return false;
    }
}

export default domObserver