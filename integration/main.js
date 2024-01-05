import modale from "./modale.js";
import slider from "./slider.js";

const main = {
    init : () => {
        modale.init();
        slider.init();
    }
}

document.addEventListener('DOMContentLoaded', main.init);
