export default [
    { field: "id"             , title: "Devolutiva Nº" , visible: true  , sortable: true },
    { field: "feito_em"       , title: "Feito em"      , visible: true  , sortable: true , type: "datetime" },
    { field: "usuario.nome"   , title: "Operador"      , visible: false , sortable: true },
    { field: "ficha.nome"     , title: "Ficha"         , visible: true  , sortable: true },
    { field: "servico.nome"   , title: "Serviço"       , visible: true  , sortable: true },
    { field: "terapeuta.nome" , title: "Terapeuta"     , visible: true  , sortable: true },
];