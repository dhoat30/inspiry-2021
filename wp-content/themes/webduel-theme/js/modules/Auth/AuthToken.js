let $ = jQuery

class AuthToken {
    constructor(username, password, email) {
        this.username = username
        this.password = password
        this.email = email
        this.events()
    }

    events() {
        let formData = {
            username: this.username,
            email: this.email,
            password: this.password
        }
        console.log(formData)

        let url = 'https://inspiry.co.nz/wp-json/jwt-auth/v1/token';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            url = 'http://localhost/inspirynew/wp-json/jwt-auth/v1/token';
        }

        console.log("this is url" + url)
        // set auth cookies 
        fetch(url, {
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
}
export default AuthToken