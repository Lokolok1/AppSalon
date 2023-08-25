document.addEventListener("DOMContentLoaded", function() {
    iniciarApp();
});

function iniciarApp() {
    buscarFecha();
    mostrarAlerta();
}

function buscarFecha() {
    const fechaInput = document.querySelector("#fecha");
    fechaInput.addEventListener("input", function(e) {
        const fechaSeleccionada = e.target.value;
        window.location = `?fecha=${fechaSeleccionada}`;
    });
}

function mostrarAlerta() {
    const formsEliminar = document.querySelectorAll("#eliminar");

    formsEliminar.forEach(formEliminar => {
        formEliminar.addEventListener("submit", function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estas seguro que deseas elimiar la cita?',
                text: "Esto no podra revertirse",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Estoy seguro'
            }).then((result) => {
                if (result.isConfirmed) {
                  Swal.fire(
                    'Cita eliminada',
                    'La cuta ha sido eliminada correctamente',
                    'success'
                  )
                  setTimeout(() => {
                    this.submit();
                }, 1500);
                }
            })
        });
    });
}