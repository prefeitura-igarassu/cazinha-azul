function nao_sim( usuario )
{
    return usuario.ativo ? 'Sim' : 'Não';
}

export default [
    { field: "id"               , title: "Usuário Nº"    , visible: true , sortable: true },
    { field: 'nome'             , title: 'Nome'          , visible: true , sortable: true },
    { field: 'email'            , title: 'E-mail'        , visible: true , sortable: true },
    { field: 'funcao'           , title: 'Função'        , visible: false, sortable: true },
    { field: 'ultimo_acesso_em' , title: 'Último Acesso' , visible: false, sortable: true, type: "datetime" },
    { title: 'Ativo?'           , format: nao_sim        , visible: false, sortable: true },
];