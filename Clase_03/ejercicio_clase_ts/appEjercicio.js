var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var Prueba;
(function (Prueba) {
    var Persona = /** @class */ (function () {
        function Persona(apellido, nombre) {
            this.apellido = apellido;
            this.nombre = nombre;
        }
        Object.defineProperty(Persona.prototype, "Apellido", {
            get: function () {
                return this.apellido;
            },
            set: function (value) {
                this.apellido = value;
            },
            enumerable: false,
            configurable: true
        });
        Object.defineProperty(Persona.prototype, "Nombre", {
            get: function () {
                return this.nombre;
            },
            set: function (value) {
                this.nombre = value;
            },
            enumerable: false,
            configurable: true
        });
        Persona.prototype.ToString = function () {
            return "".concat(this.Apellido, ", ").concat(this.Nombre);
        };
        return Persona;
    }());
    Prueba.Persona = Persona;
})(Prueba || (Prueba = {}));
/// <reference path="./persona.ts" />
var Prueba;
(function (Prueba) {
    var Alumno = /** @class */ (function (_super) {
        __extends(Alumno, _super);
        function Alumno(apellido, nombre, legajo) {
            var _this = _super.call(this, apellido, nombre) || this;
            _this.legajo = legajo;
            return _this;
        }
        Object.defineProperty(Alumno.prototype, "Legajo", {
            get: function () {
                return this.legajo;
            },
            set: function (value) {
                this.legajo = value;
            },
            enumerable: false,
            configurable: true
        });
        Alumno.prototype.ToString = function () {
            return "".concat(_super.prototype.ToString.call(this), ". Legajo: ").concat(this.Legajo);
        };
        return Alumno;
    }(Prueba.Persona));
    Prueba.Alumno = Alumno;
})(Prueba || (Prueba = {}));
/// <reference path="./persona.ts" />
/// <reference path="./alumno.ts" />
var TestPrueba;
(function (TestPrueba) {
    var a1 = new Prueba.Alumno("Perez", "Juan", 1111);
    var a2 = new Prueba.Alumno("Gomez", "Pablo", 1112);
    //console.log(a1.ToString());
    //console.log(a2.ToString());
    var alumnos = [new Prueba.Alumno("Perez", "Pepe", 1113),
        new Prueba.Alumno("Rodriguez", "Juan Carlos", 1114),
        a1, a2];
    alumnos.forEach(function (item) { return console.log(item.ToString()); });
})(TestPrueba || (TestPrueba = {}));
