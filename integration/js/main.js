import modale from "./modale/modale.js";
import slider from "./slider/slider.js";
import theme from "./theme/theme.js";

const main = {

    isHomePage : document.querySelector(".main__slider"),

    init : () => {
        modale.init();
        theme.init();
        if(main.isHomePage){
            slider.init();
        }
    }
}

document.addEventListener('DOMContentLoaded', main.init);
