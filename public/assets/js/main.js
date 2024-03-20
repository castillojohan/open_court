import modale from "./modale/modale.js";
import slider from "./slider/slider.js";
import theme from "./theme/theme.js";
import planning from "./planning/planning.js";
import mercure from "./planning/mercureUseCase.js";
import burger from "./burger/burger.js";
import pincode from "./pincode/pincode.js"

const main = {

    isHomePage : document.querySelector(".main__slider"),
    isPlanningPage: document.querySelector("section.main__planning"),
    isPinPad: document.querySelector(".pinpad"),

    init : () => {
        modale.init();
        theme.init();
        burger.init();
        if(main.isHomePage){
            slider.init();
        }
        if(main.isPlanningPage){
            planning.init();
            mercure.init();
        }
        if(main.isPinPad){
            pincode.init()
        }
    }
}

document.addEventListener('DOMContentLoaded', main.init);
