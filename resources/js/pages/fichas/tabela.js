export default [
    { field: "id"           , title: "Ficha Nº"       , visible: true  , sortable: true },
    { field: 'unidade.nome' , title: "Unidade"        , visible: false , sortable: true },
    { field: 'cadastrado_em', title: "Cadastrado em"  , visible: true  , sortable: true , type: "datetime" },
    { field: 'nome'         , title: "Nome"           , visible: true  , sortable: true },
    { field: 'cpf'          , title: "CPF Nº"         , visible: true  , sortable: true , type: "cpf_cnpj" },
    { field: 'nascido_em'   , title: "Nascido em"     , visible: false , sortable: true , type: "date"     },
    { field: 'sus'          , title: "SUS Nº"         , visible: false , sortable: true },
    { field: 'nis'          , title: "NIS Nº"         , visible: false , sortable: true },
    { field: 'mae_nome'     , title: "Mãe"            , visible: false , sortable: true },
    { field: 'pai_nome'     , title: "Pai"            , visible: false , sortable: true },
    { field: 'responsavel'  , title: "Responsável"    , visible: false , sortable: true },
    { field: 'escola'       , title: "Escola"         , visible: false , sortable: true },
    { field: 'endereco'     , title: "Endereço"       , visible: false , sortable: true },
    { field: 'posto_saude'  , title: "Posto de Saúde" , visible: false , sortable: true },
    { field: 'telefone'     , title: "Telefone"       , visible: false , sortable: true },
    { field: 'email'        , title: "E-mail"         , visible: false , sortable: true },
    { field: 'cid'          , title: "CID"            , visible: false , sortable: true },
];