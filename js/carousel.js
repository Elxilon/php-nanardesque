let btnRand = undefined
let deg = 0

let movies = undefined
let slider = undefined
let prevRandom = undefined

function diceBtn(callback) {
    deg += 180
    btnRand.style.transition = "transform .5s ease-in-out"
    btnRand.style.transform = "rotate(" + deg + "deg)"
    window.setTimeout(() => {
        callback()
    }, 500)
}

document.addEventListener("DOMContentLoaded", () => {
    btnRand = document.getElementById("btn-random")
    movies = JSON.parse(document.getElementById("moviesList").value)
    slider = document.getElementById("slider")

    btnRand.addEventListener('click', function () {
        diceBtn(function () {
            if (deg >= 360) {
                deg = 0
                btnRand.style.transition = ""
                btnRand.style.transform = "rotate(" + deg + "deg)"
            }
        })
    })

    slider.addEventListener('slide.bs.carousel', function () {
        let items = document.getElementsByClassName("carousel-item")
        for (let item of items) {
            if (!item.classList.contains('active')) {
                let random
                do {
                    random = Math.floor(Math.random() * movies.length)
                } while (random === prevRandom)
                item.getElementsByTagName("img")[0].src = '/projet/images/' + movies[random].img
                item.getElementsByTagName("h4")[0].innerText = movies[random].titre
                item.getElementsByTagName("span")[0].innerText = movies[random].date_sortie
                item.getElementsByTagName("span")[1].innerText = movies[random].synopsis
                prevRandom = random
            }
        }
    })
})