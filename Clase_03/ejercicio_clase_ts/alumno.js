"use strict";
/// <reference path="./persona.ts" />
var Prueba;
(function (Prueba) {
    class Alumno extends Prueba.Persona {
        constructor(apellido, nombre, legajo) {
            super(apellido, nombre);
            this.legajo = legajo;
        }
        get Legajo() {
            return this.legajo;
        }
        set Legajo(value) {
            this.legajo = value;
        }
        ToString() {
            return `${super.ToString()}. Legajo: ${this.Legajo}`;
        }
    }
    Prueba.Alumno = Alumno;
})(Prueba || (Prueba = {}));
//# sourceMappingURL=alumno.js.map