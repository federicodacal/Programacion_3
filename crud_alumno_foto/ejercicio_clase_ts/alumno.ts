/// <reference path="./persona.ts" />

namespace Prueba 
{
    export class Alumno extends Persona
    {
        protected legajo : number;

        public constructor(apellido:string, nombre:string, legajo:number){
            super(apellido, nombre)
            this.legajo = legajo;
        }

        public get Legajo() : number {
            return this.legajo;
        }

        public set Legajo(value : number) {
            this.legajo = value;
        }

        public ToString() : string 
        {
            return `${super.ToString()}. Legajo: ${this.Legajo}`
        }
    }
}

