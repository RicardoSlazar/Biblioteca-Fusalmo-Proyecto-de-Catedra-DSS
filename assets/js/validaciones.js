function validarFormulario() {

    let titulo = document.getElementById("titulo").value.trim();
    let autor = document.getElementById("autor").value.trim();
    let categoria = document.getElementById("categoria").value.trim();
    let isbn = document.getElementById("isbn").value.trim();
    let cantidad = document.getElementById("cantidad").value.trim();

    if (
        titulo === "" ||
        autor === "" ||
        categoria === "" ||
        isbn === "" ||
        cantidad === ""
    ) {
        alert("Todos los campos son obligatorios");
        return false;
    }

    if (isNaN(cantidad)) {
        alert("La cantidad debe ser numérica");
        return false;
    }

    return true;
}