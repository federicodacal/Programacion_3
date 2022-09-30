"use strict";
var Prueba;
(function (Prueba) {
    class Persona {
        constructor(apellido, nombre) {
            this.apellido = apellido;
            this.nombre = nombre;
        }
        get Apellido() {
            return this.apellido;
        }
        set Apellido(value) {
            this.apellido = value;
        }
        get Nombre() {
            return this.nombre;
        }
        set Nombre(value) {
            this.nombre = value;
        }
        ToString() {
            return `${this.Apellido}, ${this.Nombre}`;
        }
    }
    Prueba.Persona = Persona;
})(Prueba || (Prueba = {}));
//# sourceMappingURL=persona.js.map