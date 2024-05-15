const burger = {
    menuContainer : document.querySelector('.side-container'),

    init: () => {
        document.querySelector('.burger').addEventListener('click', burger.handleOpenMenu);
        document.querySelector('.close-burger').addEventListener('click', burger.handleCloseMenu);
    },

    handleOpenMenu: () => {
        burger.menuContainer.classList.add("showed");
    },

    handleCloseMenu: () => {
        burger.menuContainer.classList.remove("showed");
    }
}
export default burger;