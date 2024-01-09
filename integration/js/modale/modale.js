const modale = {
    init: () => {
        document.querySelector("a[href='login']").addEventListener('click', modale.handleLoginClick);
        document.querySelector('.button-close').addEventListener('click', modale.handleLoginClick);
    },

    handleLoginClick: (event) => {
        event.preventDefault();
        modale.addToDom();
    },

    addToDom : () => {
        document.querySelector('.blur-pattern').classList.toggle('logon');
    }
}

export default modale;