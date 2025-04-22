import situacoes from "./situacoes.js";

function situacao_tag( chamado )
{
    switch( chamado?.status )
    {
        case 0: return "<span class='cursor-pointer p-tag p-component p-tag-secondary'>Solicitado</span>";
        case 1: return "<span class='cursor-pointer p-tag p-component p-tag-warning'>Agendado</span>";
        case 2: return "<span class='cursor-pointer p-tag p-component p-tag-success'>Alta</span>";
        default: return "Desconhecido";
    }
}

function situacao_click( chamado , tabela )
{
    //console.log( primeiro , segundo );
    //alert( primeiro.status );
    tabela.$emit( 'dialog' , chamado );
}

// ---------------------- //
// ---------------------- //
// ---------------------- //

export default [
    { field: "solicitado_em"  , title: "Solicitado em"  , visible: true , sortable: true , type: 'datetime' },
    { field: "usuario.nome"   , title: "Usuário"        , visible: false, sortable: true },
    { field: "ficha.nome"     , title: "Ficha"          , visible: true , sortable: true },
    { field: "posicao"        , title: "Posição"        , visible: true , sortable: true },
    { field: "servico.nome"   , title: "Serviço"        , visible: false, sortable: true },
    { field: 'status'         , title: "Situação"       , visible: false, sortable: true , type: 'options' , options: situacoes   , html: situacao_tag   , click: situacao_click },
    { field: "terapeuta.nome" , title: "Terapeuta"      , visible: false, sortable: true },
    { field: "dia"            , title: "Dia"            , visible: false, sortable: true },
    { field: "horario"        , title: "Horário"        , visible: false, sortable: true },
    { field: "periodo_inicial", title: "Período Inicial", visible: false, sortable: true },
    { field: "periodo_final"  , title: "Período Final"  , visible: false, sortable: true },
];