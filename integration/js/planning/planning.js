import data from "./data.js";
import domModule from "./domModule.js";
import slotComponent from "./slotsComponents.js";
import bindListeners from "./bindListeners.js";
import handleClick from "./handleClick.js";

const planning = {
    state: {
        selectedDay : new Date(),
        selectedCourt : 1,
        filteredCourt: [],
        filteredDay: [],
    },
    
    init : () => {
        domModule.init();
        slotComponent.init();
        //bindListeners.init();
        handleClick.initialiseDate();
    },
    
}
export default planning;