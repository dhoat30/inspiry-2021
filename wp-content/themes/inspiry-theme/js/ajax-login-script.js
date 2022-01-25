// ajax log in
jQuery(document).ready(function ($) {
    console.log(ajax_login_object)
    // Show the login dialog box on click
    $('a#show_login').on('click', function (e) {
        $('body').prepend('<div class="login_overlay"></div>');
        $('form#login').fadeIn(500);
        $('div.login_overlay, form#login a.close').on('click', function () {
            $('div.login_overlay').remove();
            $('form#login').hide();
        });
        e.preventDefault();
    });

    // Perform AJAX login on form submit
    $('form#login').on('submit', function (e) {
        console.log("form clicked")
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
                $('form#login p.status').text(data.message);
                if (data.loggedin == true) {
                    console.log("jwt auth")
                    jwtAuth($('form#login #username').val(), $('form#login #password').val())
                }
            }
        });
        e.preventDefault();
    });

    function jwtAuth(username, password) {
        let formData = {
            username: username,
            email: username,
            password: password
        }
        console.log(formData)
        fetch("https://inspiry.co.nz/wp-json/jwt-auth/v1/token", {
            method: "POST",
            body: JSON.stringify(formData),
            headers: {
                'Content-Type': 'application/json'
            },
        })
            .then(res => res.json())
            .then(res => {
                // document.forms["login-form"].submit();
                console.log(res)
                if (res.data) {
                    console.log(res.data.status)
                }
                else {
                    document.cookie = `inpiryAuthToken=${res.token}`;
                    console.log(res.token)
                    location.reload()
                }


            })
            .catch(err => console.log(err))
    }



});