import data from "./data.js";
import planning from "./planning.js";

const handleClick = {
    currentDay: 0,
    daysCollection: [],

    initialiseDate :() => {
        const dateOfTheDay = data.state.actualDate;
        for(let day = 0; day <= data.state.stopReservationDate; day++){
            const createdDay = new Date();
            createdDay.setDate(dateOfTheDay.getDate()+day);
            handleClick.daysCollection.push(createdDay);
        }
    },

    slots : (event) => {
        if(event.target.className !== "reserved" && event.target.childElementCount >= 2){
            console.log(event.target.firstElementChild.firstChild.attributes.dateTime.value);    
        }
        return false;
    },

    goToDate : (newDayPosition) => {
        handleClick.daysCollection[newDayPosition];
        data.state.currentDate = handleClick.daysCollection[newDayPosition];
        handleClick.currentDay = newDayPosition;
        planning.init()
    },

    leftDayButton: () => {
        const newCurrentDay = handleClick.currentDay <= 0 ? handleClick.daysCollection.length -1 : handleClick.currentDay - 1;
        handleClick.goToDate(newCurrentDay);
    },

    rightDayButton: () => {
        const newCurrentDay = handleClick.currentDay === handleClick.daysCollection.length -1 ? 0 : handleClick.currentDay + 1;
        handleClick.goToDate(newCurrentDay);
    },
}
export default handleClick;