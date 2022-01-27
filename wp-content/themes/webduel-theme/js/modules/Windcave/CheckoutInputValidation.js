const $ = jQuery

class CheckoutInputValidation {
    constructor() {
        this.firstName = $('#billing_first_name').val()
        this.lastName = $("#billing_last_name").val()
        this.emailValue = $('#billing_email').val()
        this.streetAddress = $('#billing_address_1').val()
        this.city = $('#billing_city').val()
        this.postCode = $('#billing_postcode').val()
        this.billingPhone = $('#billing_phone').val()

        $('.error').remove()
    }

    // validate name
    validateName() {
        if (this.firstName.length > 1 && this.lastName.length > 1) {
            return true
        }
        else {
            // if the valid email address in not entered           
            // add error under the email address input field 
            $('#payment').append(`<div class="error">*Please enter your first and last name</div>`)
            // scroll to the email address field 
            return false
        }
    }

    // validate street address 
    validateStreetAddress() {
        if (this.streetAddress.length > 3) {
            return true
        }
        else {
            // if the valid email address in not entered           
            // add error under the email address input field 
            $('#payment').append(`<div class="error">*Please enter your street address</div>`)
            // scroll to the email address field 

            return false
        }
    }

    validateCity() {
        if (this.city.length > 2) {
            return true
        }
        else {
            // if the valid email address in not entered           
            // add error under the email address input field 
            $('#payment').append(`<div class="error">*Please enter your Town/City</div>`)
            // scroll to the email address field 
            return false
        }
    }

    // validate post code
    validatePostCode() {
        if (this.postCode.length > 3) {
            return true
        }
        else {
            // if the valid email address in not entered           
            // add error under the email address input field 
            $('#payment').append(`<div class="error">*Please enter your Postcode</div>`)
            // scroll to the email address field 
            return false
        }
    }

    // validate post code
    validatePhoneNumber() {
        if (this.billingPhone.length > 5) {
            return true
        }
        else {
            // if the valid email address in not entered           
            // add error under the email address input field 
            $('#payment').append(`<div class="error">*Please enter your Phone Number</div>`)
            // scroll to the email address field 
            return false
        }
    }
    // validate email 
    validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,6})?$/;
        let emailValidation = emailReg.test($email);
        // check if the email is valid and length is greater than 3
        if (emailValidation && this.emailValue.length > 3) {
            return true
        }
        else {
            // if the valid email address in not entered           
            // add error under the email address input field 
            $('#payment').append(`<div class="error">*Please enter a valid email address</div>`)
            // scroll to the email address field 
            return false
        }
    }

    validate() {
        // validate email 
        if (
            this.validateName()
            && this.validateStreetAddress()
            && this.validateCity()
            && this.validatePostCode()
            && this.validatePhoneNumber()
            && this.validateEmail(this.emailValue)
        ) {
            // remove the appended element first 
            return true
        }
    }


}
export default CheckoutInputValidation