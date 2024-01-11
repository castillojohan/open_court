const theme = {

    pathToBody : document.querySelector("body"),

    init : () => {
        theme.bindButtons();
        if(localStorage.getItem('theme')){
            theme.loadFavoriteTheme();
        }else{
            theme.changeToPickedColor('theme-blue');
        }
    },

    bindButtons : () => {
        const buttons = document.querySelectorAll(".theme-button");
        buttons.forEach((button) => {
            button.addEventListener("click", theme.handleChangeColor);
        });
    },

    handleChangeColor : (event) => {
        const pickedColor = event.target.ariaLabel ;
        theme.changeToPickedColor(pickedColor);
    },

    changeToPickedColor : (newColor)=>{
        theme.pathToBody.classList.remove('theme-green', 'theme-red', 'theme-blue');
        theme.pathToBody.classList.add(newColor);

        theme.toLocalStorage('theme', newColor);
    },

    toLocalStorage : (key, value) => {
        localStorage.setItem(key, JSON.stringify(value));
    },

    loadFromLocalStorage : (key) => {
        return JSON.parse(localStorage.getItem(key));
    },

    loadFavoriteTheme : () => {
        const favoriteColorTheme = theme.loadFromLocalStorage('theme');
        if (favoriteColorTheme){
            theme.changeToPickedColor(favoriteColorTheme);
        }
    }
}

export default theme;