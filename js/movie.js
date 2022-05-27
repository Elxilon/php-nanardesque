let btn = undefined
let btnText = undefined
let form = undefined
let errorMsg = undefined

function checkForm() {
    let msg = ""
    let ok = true

    form.commEmail.value = form.commEmail.value.trim()
    form.commPseudo.value = form.commPseudo.value.trim()
    form.commText.value = form.commText.value.trim()

    let regex = RegExp('[^@ \\t\\r\\n]+@[^@ \\t\\r\\n]+\\.[^@ \\t\\r\\n]+')
    if (form.commEmail.value === "") {
        msg += "<div>L'adresse email ne peut pas être vide !</div>"
        form.commEmail.classList.add("bg-error")
        ok = false
    } else if (!regex.test(form.commEmail.value)) {
        msg += "<div>L'adresse email saisie n'est pas valide !</div>"
        form.commEmail.classList.add("bg-error")
        ok = false
    } else form.commEmail.classList.remove("bg-error")

    if (form.commPseudo.value === "") {
        msg += "<div>Le pseudo ne peut pas être vide !</div>"
        form.commPseudo.classList.add("bg-error")
        ok = false
    } else form.commPseudo.classList.remove("bg-error")

    if (form.commText.value === "") {
        msg += "<div>Le commentaire ne peut pas être vide !</div>"
        form.commText.classList.add("bg-error")
        ok = false
    } else if (form.commText.value.replace(/\s/g, '').length >= 150) {
        msg += "<div>Le commentaire ne doit pas dépasser 150 caractères (espaces non inclus) !</div>"
        form.commText.classList.add("bg-error")
        ok = false
    } else form.commText.classList.remove("bg-error")

    errorMsg.innerHTML = (ok) ? '' : msg
    return ok
}

document.addEventListener("DOMContentLoaded", () => {
    btn = document.getElementById("btn-comment")
    btnText = document.getElementById("btn-text")
    form = document.getElementById("form-comment")
    errorMsg = document.getElementById("error")

    btn.addEventListener("click", function () {
        btnText.innerText = (btnText.innerText === "Fermer") ? "Ajouter votre avis" : "Fermer"
        this.classList.toggle("btn-outline-primary")
        this.classList.toggle("btn-outline-danger")
        form.classList.toggle("h-hide")
    })

    form.addEventListener("submit", function (e) {
        e.preventDefault()
        if (checkForm()) this.submit()
    })
})