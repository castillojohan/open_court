import data from "./data.js";
import domModule from "./domModule.js";
import slotComponent from "./slotsComponents.js";
import bindListeners from "./bindListeners.js";
import handleClick from "./handleClick.js";
import fixtures from "./fixtures.js";

const planning = {
    state: {
        selectedDay : new Date(),
        selectedCourt : 1,
        filteredCourt: [],
        filteredDay: [],
    },
    
    init : () => {
        if(data.state.slots.length < 5){
            fixtures.init();
        }
        domModule.init();
        slotComponent.init();
        //bindListeners.init();
        handleClick.initialiseDate();
    },
    
}
export default planning;