const bookingHistory = {
    month:[
        "Janvier",
        "Février",
        "Mars",
        "Avril",
        "Mai",
        "Juin",
        "Juillet",
        "Aout",
        "Septembre",
        "Octobre",
        "Novembre",
        "Décembre"
    ], 

    countHours: document.querySelectorAll('ul.slot-list li'),

    init: () => {
        bookingHistory.loadCountHours();
        bookingHistory.cleanDateTime();
    },

    loadCountHours: () => {
        const targetHoursCounted = document.querySelector('h3');
        targetHoursCounted.textContent += `${bookingHistory.countHours.length} heures de réservation`;
    },

    cleanDateTime: () => {

        let memberSlots = [];
        let oldValue = '';
        let oldMember = '';
        let datesList = [];
        if(bookingHistory.countHours.length){
            for (const slot of bookingHistory.countHours) {
                const [user, dateTime] = slot.innerText.split(' ');
                if(!memberSlots[user]){
                    memberSlots[user] = []
                }
                memberSlots[user].push(dateTime);
            }
        }
        for (const member in memberSlots) {
            if (Object.hasOwnProperty.call(memberSlots, member)) {
                const slots = memberSlots[member];
                const target = document.querySelector('main section>section');
                const newDivMember = document.createElement('div'); 
                const newH4Member = document.createElement('h4');
                newH4Member.textContent = member;
                newDivMember.appendChild(newH4Member);
                target.append(newDivMember);

                console.log(`Membre : ${member}`);
                console.log('Créneaux horaires :');
                slots.forEach(slot => {
                    console.log(slot);
                });
            }
        }
        /*
        if(bookingHistory.countHours.length){
            for (const liElement of bookingHistory.countHours){
                //dates = liElement.innerText.split("-")[0].map(dateString => new Date(dateString));
                const parentElement = liElement.parentElement;
                const h4MemberName = parentElement.parentElement.children[0].textContent;
                const liMonth = bookingHistory.month[parseInt(liElement.innerText.split('/')[1])-1];
                if((liMonth !== oldValue) || (h4MemberName !== oldMember)){
                    bookingHistory.createElement('li', liElement, liMonth);
                    oldValue = liMonth;
                    oldMember = h4MemberName;
                }
            }
        }
        */
    },

    createElement: (tag, target, attr) => {
        const parentEl = target;
        const newliElement = document.createElement(tag);
        newliElement.textContent = attr
        parentEl.append(newliElement);
    }
}

document.addEventListener('DOMContentLoaded', bookingHistory.init);