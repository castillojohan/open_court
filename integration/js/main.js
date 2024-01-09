import modale from "./modale/modale.js";
import slider from "./slider/slider.js";

const main = {
    init : () => {
        modale.init();
        slider.init();
    }
}

document.addEventListener('DOMContentLoaded', main.init);
