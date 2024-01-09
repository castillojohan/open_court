const theme = {

    pathToBody : document.querySelector("body"),

    init : () => {
       theme.bindButton(); 
    },

    bindButton : () => {
        const buttons = document.querySelectorAll(".theme-button");
        buttons.forEach((button) => {
            document.addEventListener("click", theme.handleChangeColor);
        });
    },

    handleChangeColor : (event) => {
        const pickedColor = event.target.ariaLabel ;
        console.log(pickedColor);
        theme.changeToPickedColor(pickedColor);
    },

    changeToPickedColor : (newColor)=>{
        theme.pathToBody.classList.remove('theme-green', 'theme-red', 'theme-blue');
        theme.pathToBody.classList.add(newColor);
    } 
}

export default theme;