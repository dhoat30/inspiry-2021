let $ = jQuery

class Login {
    constructor() {
        this.events()
    }

    events() {
        // $('.login-form').on('submit', this.submitHandler)
    }

    submitHandler(e) {
        e.preventDefault()
        console.log("submit handler")
        // $('.login-form').submit()
        let form = document.getElementsByClassName("login-form")
        console.log(form)
        return false;
        let formData = {
            username: $('.login-form #username').val(),
            email: $('.login-form #username').val(),
            password: $('.login-form #password').val()
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

                if (res.data) {
                    console.log(res.data.status)
                }
                else {
                    document.cookie = `inpiryAuthToken=${res.token}`;
                    console.log(res.token)

                }


            })
            .catch(err => console.log(err))
    }
}

export default Login