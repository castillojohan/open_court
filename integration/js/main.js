import modale from "./modale/modale.js";
import slider from "./slider/slider.js";
import theme from "./theme/theme.js";
import planning from "./planning/planning.js";
import burger from "./burger/burger.js";

const main = {

    isHomePage : document.querySelector(".main__slider"),
    isPlanningPage: document.querySelector("table"),

    init : () => {
        modale.init();
        theme.init();
        burger.init();
        if(main.isHomePage){
            slider.init();
        }
        if(main.isPlanningPage){
            planning.init();
        }
        
    }
}

document.addEventListener('DOMContentLoaded', main.init);
