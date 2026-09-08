
document.addEventListener("DOMContentLoaded", () => {
    const menu = document.querySelector(".menu-toggle");
    const nav = document.querySelector(".nav-links");
    if(menu && nav){
        menu.addEventListener("click", () => nav.classList.toggle("open"));
    }

    document.querySelectorAll(".wish").forEach(btn => {
        btn.addEventListener("click", () => {
            btn.classList.toggle("active");
            const icon = btn.querySelector("i");
            if(icon){
                icon.classList.toggle("fa-regular");
                icon.classList.toggle("fa-solid");
            }
            showToast(btn.classList.contains("active") ? "Added to wishlist" : "Removed from wishlist");
        });
    });

    document.querySelectorAll("[data-toast]").forEach(el => {
        el.addEventListener("click", e => {
            if(el.tagName === "A" && el.getAttribute("href") === "#") e.preventDefault();
            showToast(el.dataset.toast);
        });
    });

    document.querySelectorAll("form.demo-form").forEach(form => {
        form.addEventListener("submit", e => {
            e.preventDefault();
            showToast(form.dataset.message || "Demo action completed.");
        });
    });
});

function showToast(message){
    let toast = document.querySelector(".toast");
    if(!toast){
        toast = document.createElement("div");
        toast.className = "toast";
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.classList.add("show");
    clearTimeout(window.toastTimer);
    window.toastTimer = setTimeout(() => toast.classList.remove("show"), 2300);
}
