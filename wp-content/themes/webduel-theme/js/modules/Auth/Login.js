import AuthToken from './AuthToken'

let $ = jQuery

class Login {
    constructor() {
        this.events()
    }
    events() {

        // submit login form
        $('form#login').on('submit', this.submitLogin)
    }
    submitLogin(e) {
        e.preventDefault();
        // get redirect link from url parameters 
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const redirectLink = urlParams.get('redirect-link')

        $('form#login p.status').show().text(ajax_login_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: {
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#login #username').val(),
                'password': $('form#login #password').val(),
                'security': $('form#login #security').val()
            },
            success: function (data) {
                console.log(data)
                $('form#login p.status').text(data.message);
                if (data.loggedin == true) {
                    console.log("jwt auth")
                    // set auth token 

                    const authToken = new AuthToken(redirectLink, $('form#login #username').val(), $('form#login #password').val())
                }
            }
        });

    }
}
export default Login