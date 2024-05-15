import data from "./data.js";
import domModule from "./domModule.js";
import slotComponent from "./slotsComponents.js";
import handleClick from "./handleClick.js";

const planning = {
    state: {
        selectedDay : new Date(),
        selectedCourt : 1,
        filteredCourt: [],
        filteredDay: [],
    },
    
    init : () => {
        if(data.state.slots.length < 1){
            slotComponent.init();
        }
        handleClick.initialiseDate();
        domModule.init();
    },
    
}
export default planning;