function iniciarApp(){mostrarAlerta()}function mostrarAlerta(){document.querySelectorAll("#eliminar").forEach(e=>{e.addEventListener("submit",(function(e){e.preventDefault(),Swal.fire({title:"¿Estas seguro que deseas elimiar el servicio?",text:"Se eliminaran todas las citas que tengan este servicio y ¡no podras revertirlo!",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Estoy seguro"}).then(e=>{e.isConfirmed&&(Swal.fire("Servicio eliminado","El servicio ha sido eliminado correctamente","success"),setTimeout(()=>{this.submit()},3e3))})}))})}document.addEventListener("DOMContentLoaded",(function(){iniciarApp()}));