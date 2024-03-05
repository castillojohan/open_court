const pincode= {

    inputDisplay: '',
    inputBack: '',
    inputClear:'',

    init :() => {
        pincode.inputBack = document.querySelector('button.pinpad-back');
        pincode.inputClear = document.querySelector('button.pinpad-clear')
        pincode.inputDisplay = document.querySelector('input');
        pincode.bindPinButton();
    },

    bindPinButton: () => {
        // Pin buttons ( Array )
        const digitPinButtons = document.querySelectorAll(".pinpad-btn");
        digitPinButtons.forEach( pinButton => {
            pinButton.addEventListener('click', pincode.insertValueToInput );
        });
        // Back button ( Simple )
        pincode.inputBack.addEventListener('click', pincode.deleteLastPin)
        // Clear button ( simple )
        pincode.inputClear.addEventListener('click', pincode.clearInputValue)
    },

    insertValueToInput : (event) => {
        pincode.inputDisplay.value += event.target.value ;
    },

    deleteLastPin: () => {
        const lastIndexOfPin = pincode.inputDisplay.value.length-1;
        const value = pincode.inputDisplay.value;
        pincode.inputDisplay.value = value.replace(value[lastIndexOfPin], '')
    },

    clearInputValue: () => {
        pincode.inputDisplay.value = '';
    }
}

export default pincode