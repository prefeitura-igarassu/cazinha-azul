import situacoes from "./situacoes.js";

function situacao_tag( chamado )
{
    switch( chamado?.status )
    {
        case 0: return "<span class='cursor-pointer p-tag p-component p-tag-secondary'>Aberto</span>";
        case 1: return "<span class='cursor-pointer p-tag p-component p-tag-warning'>Em Andamento</span>";
        case 2: return "<span class='cursor-pointer p-tag p-component p-tag-success'>Finalizado</span>";
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
    { field: "id"           , title: "Andamento Nº" , visible: true , sortable: true },
    { field: "ocorrido_em"  , title: "Ocorrido em"  , visible: true , sortable: true , type: 'datetime' },
    { field: "usuario.nome" , title: "Usuário"      , visible: true , sortable: true },
    { field: 'status'       , title: "Situação"     , visible: true , sortable: true , type: 'options' , options: situacoes   , html: situacao_tag   , click: situacao_click },
    { field: 'descricao'    , title: "Descrição"    , visible: true , sortable: true },
];