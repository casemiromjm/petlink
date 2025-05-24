function toggleVisibility(id) {
    const element = document.getElementById(id);
    if (element.style.display === "none" || getComputedStyle(element).display === "none") {
        element.style.display = "block"; 
        element.previousElementSibling.querySelector('.toggle-icon').textContent = "-";
    } else {
        element.style.display = "none";
        element.previousElementSibling.querySelector('.toggle-icon').textContent = "+";
    }
}
