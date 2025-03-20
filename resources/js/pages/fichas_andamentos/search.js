import service from "@/services/Service";

import prioridades  from "./prioridades.js";
import situacoes    from "./situacoes.js";

let p = [ { value: null , label: "Todos" } ].concat( prioridades );
let s = [ { value: null , label: "Todos" } ].concat( situacoes   );

export default [
    { field: "requerido_em", title: "Data"         , class: "w-2", type: "date"  },
    { field: "prioridade"  , title: "Prioridade"   , class: "w-2", type: "options", options: p },
    { field: "status"      , title: "Situação"     , class: "w-2", type: "options", options: s },
    { field: "empresa_id"  , title: "Empresa"      , class: "w-2", type: "options", options: () => service.empresas.getOptions( false ) },
    { field: "global"      , title: "Palavra Chave", class: "w-2", type: "string" },
];