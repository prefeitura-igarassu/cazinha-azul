import situacoes from "./situacoes";

export default [
    { field: "id"             , title: "Sessão Nº"     , visible: true  , sortable: true },
    { field: "usuario.nome"   , title: "Operador"      , visible: false , sortable: true },
    { field: "ficha.nome"     , title: "Ficha"         , visible: true  , sortable: true },
    { field: "servico.nome"   , title: "Serviço"       , visible: true  , sortable: true },
    { field: "terapeuta.nome" , title: "Terapeuta"     , visible: true  , sortable: true },
    { field: "agendado_para"  , title: "Agendado para" , visible: true  , sortable: true , type: "datetime"   },
    { field: "status"         , title: "Situação"      , visible: true  , sortable: true , type: "options", options: situacoes },
];