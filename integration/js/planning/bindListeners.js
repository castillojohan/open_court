import handleClick from "./handleClick.js";

const bindListeners = {

    init : () => {
        bindListeners.slotCollection();
        bindListeners.daysNavigation();
    },

    slotCollection: () => {
        const slotCollection = document.querySelectorAll("table tr");
        slotCollection.forEach( tableRow => {
            tableRow.addEventListener('click', handleClick.slots);
        });
    },

    daysNavigation : () => {
        document.querySelector('th td:last-child').addEventListener('click', handleClick.rightDayButton);
        document.querySelector('th td:first-child').addEventListener('click', handleClick.leftDayButton);
    } 

}

export default bindListeners;