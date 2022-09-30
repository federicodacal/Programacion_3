/// <reference path="./persona.ts" />
/// <reference path="./alumno.ts" />

namespace Manejador 
{
    export function CrearAlumno():void{
        let nombre : string = (<HTMLInputElement>document.getElementById("txtNombre")).value;
        let apellido : string = (<HTMLInputElement>document.getElementById("txtApellido")).value;
        let legajo : number = parseInt((<HTMLInputElement>document.getElementById("txtLegajo")).value);

        let nuevoAlumno = new Prueba.Alumno(apellido, nombre, legajo);

        console.log(nuevoAlumno.ToString());
        alert(nuevoAlumno.ToString());
    }
}