let $ = jQuery
import AuthToken from "./AuthToken"

class CreateAccount {
    constructor() {
        this.events()
    }
    events() {
        // submit create account form
        // $('.create-account-page #create-account').on('submit', this.createAccount)
    }
    createAccount(e) {
        e.preventDefault()
        $('.create-account-page p.status').show().text("Sending request, please wait...");

        let formData = {
            username: $('#create-account #username').val(),
            email: $('#create-account #email').val(),
            password: $('#create-account #password').val(),
            firstName: $('#create-account #first-name').val(),
            lastName: $('#create-account #last-name').val(),
            subscribeNewsletter: $('#create-account #newsletter').is(":checked")
        }
        console.log(formData)

        // let url = 'https://inspiry.co.nz/wp-json/jwt-auth/v1/token';
        // if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
        //     url = 'http://localhost/inspirynew/wp-json/jwt-auth/v1/token';
        // }

        jQuery.ajax({
            type: "POST",
            url: 'http://localhost/inspirynew/wp-admin/admin-ajax.php',
            data: formData,
            success: function (results) {
                console.log(results);
                $('.create-account-page p.status').show().text(results);
            },
            error: function (results) {

            }
        });
    }
}
export default CreateAccount