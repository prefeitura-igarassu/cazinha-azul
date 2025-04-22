export default [
    { field: "id"           , title: "Registro Nº"  , visible: false, sortable: true },
    { field: 'created_at'   , title: 'Ocorrido em'  , visible: true, sortable: true, type: "datetime" },
    { field: "usuario_id"   , title: "Usuário Nº"   , visible: false, sortable: true, type: 'numeric' },
    { field: "usuario.nome" , title: "Usuário"      , visible: true, sortable: true },
    { field: 'externo_id'   , title: 'Referente Nº' , visible: true, sortable: true },
    { field: 'ip'           , title: 'IP'           , visible: false, sortable: true },
    { field: 'modulo'       , title: 'Módulo'       , visible: true, sortable: true },
    { field: 'acao'         , title: 'Ação'         , visible: true, sortable: true },
    { field: 'descricao'    , title: 'Descrição'    , visible: false, sortable: true },
    { field: 'parametros'   , title: 'Parametros'   , visible: false, sortable: true },
];