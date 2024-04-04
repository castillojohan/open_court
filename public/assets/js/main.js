import modale from "./modale/modale.js";
import slider from "./slider/slider.js";
import theme from "./theme/theme.js";
import planning from "./planning/planning.js";
import mercure from "./planning/mercureUseCase.js";
import burger from "./burger/burger.js";
import pincode from "./pincode/pincode.js"
import flashesErrors from "./flashes-error/flashesError.js"

const main = {

    isHomePage : document.querySelector(".main__slider"),
    isPlanningPage: document.querySelector("section.main__planning"),
    isPinPad: document.querySelector(".pinpad"),
    isHappenError: document.querySelector("div.flashes"),

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
        if(main.isHappenError){
            flashesErrors.init();
        }
        
    }
}

document.addEventListener('DOMContentLoaded', main.init);
