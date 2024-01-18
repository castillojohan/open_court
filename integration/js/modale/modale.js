const modale = {
    init: () => {
        const openModal = document.querySelectorAll("a[href='login']")
        openModal.forEach((buttonLogin) => {
            buttonLogin.addEventListener('click', modale.handleLoginClick);
        })
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