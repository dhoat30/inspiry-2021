jQuery('.googleBtn').on('click', (e) => {
    eraseCookie('inpiryAuthToken')
})

function eraseCookie(name) {
    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}