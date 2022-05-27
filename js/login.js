let form = undefined
let errorMsg = undefined

function checkForm() {
    let msg = ""
    let ok = true

    form.username.value = form.username.value.trim()
    if (form.username.value === "") {
        msg += "<div>Le nom d'utilisateur ne peut pas être vide !</div>"
        form.username.classList.add("bg-error")
        ok = false
    } else form.username.classList.remove("bg-error")

    errorMsg.innerHTML = (ok) ? '' : msg
    return ok
}

document.addEventListener("DOMContentLoaded", () => {
    form = document.getElementById("login-form")
    errorMsg = document.getElementById("error")

    form.addEventListener("submit", function (e) {
        e.preventDefault()
        if (checkForm()) this.submit()
    })
})