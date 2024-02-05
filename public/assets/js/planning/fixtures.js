import data from "./data.js";

const fixtures = {
    
    dayGenerated : new Date(),
    
    init: () => {
        fixtures.loadFixtures();
    },

    loadFixtures : () =>{
        for(let it = 0; it < 20; it++){
            const test = fixtures.settingRandomDate();
            const newSlot = {
            //* courtId
            courtId : 1,
            //* startReservation 
            startReservation : new Date(test).toISOString(),
            reservationAuthor : `JeanJean${it}`,
            }
            data.state.slots.push(newSlot);
        };
    },

    settingRandomDate:() => {
        const minHoursRange = 8;
        const maxHoursRange = 22;
        const maxDaysRange = data.state.stopReservationDate;
        fixtures.dayGenerated.setDate(data.state.actualDate.getDate()+ Math.random()* maxDaysRange);
        fixtures.dayGenerated.setHours(Math.floor(Math.random()*(maxHoursRange - minHoursRange ) + minHoursRange));
        fixtures.dayGenerated.setMinutes(0);
        fixtures.dayGenerated.setSeconds(0);
        fixtures.dayGenerated.setMilliseconds(0);
        return fixtures.dayGenerated;
    }
}

export default fixtures