// js untuk hamburger menu

document.addEventListener("DOMContentLoaded", () => {
    document.querySelector(".menu .box").addEventListener("click", () => {
        // alert(2);
        let isi = document.querySelector(".menu .isi");
        let box = document.querySelector(".menu .box");
        if (isi.style.display == "flex") {
            isi.style.display = "none";
            box.style.transform = "rotate(0deg)";
        } else {
            isi.style.display = "flex";
            box.style.transform = "rotate(90deg)";
        }
    })
})