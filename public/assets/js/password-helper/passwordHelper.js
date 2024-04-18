const passwordHelper = {
    
    passwordInput: null,
    complexityIndicator: '',

    init: () => {
        
        passwordHelper.displayAndHideHelp();
        passwordHelper.passwordInput = document.querySelector('#registration #password');
        passwordHelper.passwordInput.addEventListener('input', passwordHelper.getData);
    },

    getData: (event) => {
        const inputPassword = event.target.value;
        passwordHelper.currentPasswordValue = inputPassword;
        const complexity = passwordHelper.checkComplexity(inputPassword);
        passwordHelper.buildElementBasedOnComplexity(complexity);
    },

    checkComplexity: (password) => {
        let complexity = 0;
        if(password.length >= 8){
            complexity++;
        }
        if(/[a-z]/.test(password)){
            complexity++;
        }
        if(/[A-Z]/.test(password)){
            complexity++;
        }
        if(/\d/.test(password)){
            complexity++;
        }
        if(/[!@#$%^&*()_+{}\[\]:;<>,.?~\\|\-]/.test(password)){
            complexity++;
        }
        return complexity;
    },

    buildElementBasedOnComplexity: (complexity) => {

        if(complexity == 0){
            passwordHelper.removeElement();
        }
        else if(complexity > 0 && complexity <= 2){
            passwordHelper.createSpanElements(1, 'red');
        }
        else if(complexity > 2 && complexity < 5 ){
            passwordHelper.createSpanElements(2, 'orange');
        }
        else{
            passwordHelper.createSpanElements(3, 'green')
        }
    },

    createSpanElements: (number, color) => {
        const targetInputPassword = document.querySelector('.password-strenght');
        const indicators = targetInputPassword.querySelectorAll('.complexity-indicator');

        // Supprimer les indicateurs en trop
        for (let i = number; i < indicators.length; i++) {
            indicators[i].remove();
        }
    
        // Mettre à jour les classes des indicateurs existants
        for (let i = 0; i < indicators.length; i++) {
            indicators[i].classList.remove('red', 'orange', 'green');
            indicators[i].classList.add(color);
        }
    
        // Créer de nouveaux indicateurs si nécessaire
        for (let i = indicators.length; i < number; i++) {
            const spanElement = document.createElement('span');
            spanElement.classList.add('complexity-indicator', color);
            targetInputPassword.append(spanElement);
        }

        passwordHelper.complexityIndicator = document.querySelectorAll('.complexity-indicator');

    },

    removeElement: () => {
        const elements = document.querySelectorAll('.complexity-indicator');
        for (const element of elements) {
            element.remove();
        }
        
    },

    displayAndHideHelp : () => {
        const passwordHelpButton = document.getElementById('password-help');
        passwordHelpButton.addEventListener('click', passwordHelper.displayHelpMessage);
    },

    displayHelpMessage : (event) => {
        event.preventDefault();
        const passwordMessage = document.getElementById("password-help-message");
        passwordMessage.style.display == 'none' 
            ? passwordMessage.style.display = 'block' 
            : passwordMessage.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', passwordHelper.init);