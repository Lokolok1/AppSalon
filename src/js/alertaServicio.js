document.addEventListener("DOMContentLoaded", function() {
    iniciarApp();
});

function iniciarApp() {
    mostrarAlerta();
}

function mostrarAlerta() {
    const formsEliminar = document.querySelectorAll("#eliminar");

    formsEliminar.forEach(formEliminar => {
        formEliminar.addEventListener("submit", function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estas seguro que deseas elimiar este servicio?',
                text: "Se eliminaran todas las citas que tengan este servicio y ¡no podras revertirlo!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Estoy seguro'
            }).then((result) => {
                if (result.isConfirmed) {
                  Swal.fire(
                    'Servicio eliminado',
                    'El servicio ha sido eliminado correctamente',
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