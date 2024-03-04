const pincode= {
    init :() => {
        pincode.bindPinButton();
    },

    bindPinButton: () => {
        const digitPinButtons = document.querySelectorAll(".pinpad-btn");
        console.log(digitPinButtons);
    },
}

export default pincode