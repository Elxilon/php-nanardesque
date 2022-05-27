let form = undefined
let errorMsg = undefined

let pTitre = undefined
let pDate = undefined
let pImg = undefined

let closeSuccess = undefined

function checkForm() {
    let msg = ""
    let ok = true

    form.titre.value = form.titre.value.trim()
    form.dateSortie.value = form.dateSortie.value.trim()
    form.createText.value = form.createText.value.trim()

    if (form.titre.value === "") {
        msg += "<div>Le titre ne peut pas être vide !</div>"
        form.titre.classList.add("bg-error")
        ok = false
    } else form.titre.classList.remove("bg-error")

    if (form.dateSortie.value !== "" && !RegExp(/^\d{4}$/g).test(form.dateSortie.value)) {
        msg += "<div>L'année saisie n'est pas valide !</div>"
        form.dateSortie.classList.add("bg-error")
        ok = false
    } else form.dateSortie.classList.remove("bg-error")

    if (form.createText.value === "") {
        msg += "<div>Le synopsis ne peut pas être vide !</div>"
        form.createText.classList.add("bg-error")
        ok = false
    } else if (form.createText.value.split(' ').length >= 100) {
        msg += "<div>Le commentaire ne doit pas dépasser 100 mots !</div>"
        form.createText.classList.add("bg-error")
        ok = false
    } else form.createText.classList.remove("bg-error")

    errorMsg.innerHTML = (ok) ? '' : msg
    return ok
}

document.addEventListener("DOMContentLoaded", () => {
    form = document.getElementById("create-form")
    errorMsg = document.getElementById("error")
    closeSuccess = document.getElementById("close-success")
    let btnDel = document.getElementById("del-btn")

    pTitre = document.getElementById("preview-title")
    if (form.titre.value) pTitre.innerText = form.titre.value
    pDate = document.getElementById("preview-date")
    if (form.dateSortie.value) pDate.innerText = form.dateSortie.value
    pImg = document.getElementById("preview-img")
    if (pImg.src !== "") document.getElementById('preview-infos').classList.add('bg-linear')

    if (closeSuccess !== null) {
        closeSuccess.addEventListener('click', function () {
            document.getElementById("success").style.display = "none"
        })
    }

    form.titre.addEventListener('change', function () {
        let val = this.value.trim()
        pTitre.innerText = val.length !== 0 ? val : 'Titre...'
    })

    form.dateSortie.addEventListener('change', function () {
        let val = this.value.trim()
        pDate.innerText = val.length !== 0 ? val : 'Date de sortie...'
    })

    form.img.addEventListener('change', function () {
        if (this.files) {
            pImg.src = URL.createObjectURL(this.files[0])
            pImg.classList.remove('visually-hidden')
            document.getElementById('preview-infos').classList.add('bg-linear')
        }
    })

    form.addEventListener("submit", function (e) {
        e.preventDefault()
        if (checkForm()) this.submit()
    })

    form.addEventListener('reset', function (){
        clear()
    })

    btnDel.addEventListener('click', function () {
        form.delBtn.value = "del-btn"
    })
})

function clear() {
    form.titre.value = ""
    form.titre.defaultValue = ""
    form.dateSortie.value = ""
    form.dateSortie.defaultValue = ""
    form.createText.innerText = ""
    pTitre.innerHTML = "Titre..."
    pDate.innerHTML = "Année de sortie..."
    pImg.src = ""
    pImg.classList.add('visually-hidden')
    document.getElementById('preview-infos').classList.remove('bg-linear')
    errorMsg.innerHTML = ''
    form.titre.classList.remove('bg-error')
    form.dateSortie.classList.remove('bg-error')
    form.createText.classList.remove('bg-error')
}