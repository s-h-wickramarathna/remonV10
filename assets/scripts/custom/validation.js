/**
 * Created by USER on 12/22/2015.
 */

function phoneNumberOnKey(inputTxt, event) {
    $(inputTxt).mask('0000000000');
    var phoneNo = /^\d*$/;
    if (inputTxt.value.match(phoneNo)) {
        inputTxt.style.borderColor = "";
        return true;
    } else {
        inputTxt.value = inputTxt.value.substr(0, inputTxt.value.length - 1);
        return false || event.keyCode == 8;
    }
}

function phoneNumberOnBlur(inputTxt, event) {
    var phoneNo = /^\d{10}$/;
    if (inputTxt.value.match(phoneNo)) {
        inputTxt.style.borderColor = "";
        return true;
    } else {
        inputTxt.style.borderColor = "red";
        return false || event.keyCode == 8;
    }
}