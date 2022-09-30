window.onload = () => {
    
    let btnEnviar = <HTMLInputElement>document.getElementById("btnEnviar");  // Elemento que corresponde al button Enviar

    btnEnviar.onclick = () => {

        let cboAccion = <HTMLSelectElement>document.getElementById("cboAccion");
        let frm = <HTMLFormElement>document.getElementById("frm");
        let action = "../ejercicio_clase/nexo_poo.php";
        let method = "post";

        if(cboAccion.value == "listar")
        {
            method = "get";
        }

        frm.method = method;
        frm.action = action;

        frm.submit();
    }
};

// Atributo onload: puedo establecer un manejador de eventos en el momento en la que la página se está cargando con todos sus elementos disponibles pero todavía no visible. Todos los elementos del DOM 'dibujados'.
