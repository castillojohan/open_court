import domObserver from "./domObserver.js"

const domModule = {
    
    buildMessagingInterface: (membersList) => {
        const domTarget = document.querySelector('section.create-message');

        const divElement = document.createElement('div');
        divElement.classList.add('messages-recipient');
        const inputTextElement = document.createElement('textarea');
        inputTextElement.maxLength = 255;

        const spanEl = document.createElement("span");
        const sendButtonElement = domModule.createButtons('a', ['button'], 'Envoyer');
        const closeButtonElement = domModule.createButtons('a', ['button','btn-close'], 'X');
        
        const selectRecipientElement = document.createElement('select');
        membersList.members.forEach( (member) => {
            const optionElement = document.createElement('option');
            optionElement.value = member.id;
            optionElement.textContent = member.firstName;
            selectRecipientElement.appendChild(optionElement);
        });
        
        spanEl.append(sendButtonElement, closeButtonElement);
        divElement.append(selectRecipientElement, inputTextElement, spanEl);
        domTarget.append(divElement);

        domObserver.init();
    },

    createButtons: (type, classNames, attributes) => {
        const element = document.createElement(type);
        for (const className of classNames) {
            element.classList.add(className);
        }
        element.textContent = attributes;
        return element;
    }
}




export default domModule;