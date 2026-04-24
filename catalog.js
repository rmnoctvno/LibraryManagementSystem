const openBtn = document.getElementById("brwBookOpen");
const closeBtn = document.getElementById("closeModal");
const modal = document.getElementById("modal");

openBtn.addEventListener("click", () => {
modal.classList.add("open"); 
});

openBtn.addEventListener("click", () => { 
modal.classList.remove("open"); 
});