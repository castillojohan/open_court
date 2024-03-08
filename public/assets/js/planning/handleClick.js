import data from "./data.js";
import planning from "./planning.js";
import slotComponent from "./slotsComponents.js";

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
            const timeValue = event.target.firstElementChild.firstChild.attributes.dateTime.value;
            const dateTimeValue = timeValue.split('T');
            const hrDateTimeValue = dateTimeValue[0];
            const hrTimeValue = dateTimeValue[1].slice(0, -8);
            const result = confirm(`Etes vous sur de vouloir reserver le : ${hrDateTimeValue} à ${hrTimeValue} ?` )
            if(result){
                handleClick.sendSlot(timeValue);
            };
        }
        return false;
    },

    sendSlot : async (slotValue) => {
        const response = await fetch('http://127.0.0.1:8000/book-slot', 
        {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                start_slot: slotValue,
                end_slot: slotValue,
                court_id: 1,
                user_id: 1
            })
        });
        const reservationSlot = await response.json();
    },

    goToDate : (newDayPosition) => {
        handleClick.daysCollection[newDayPosition];
        data.state.currentDate = handleClick.daysCollection[newDayPosition];
        handleClick.currentDay = newDayPosition;
        planning.init();
        slotComponent.checkDisplayedSlots();
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