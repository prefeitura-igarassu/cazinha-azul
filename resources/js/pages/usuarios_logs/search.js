import service from "@/services/Service";

export default [
    { field: "created_at" , title: "Período"      , class: "w-2", type: "datetime" },
    { field: "usuario_id" , title: "Usuário"      , class: "w-2", type: "options"  , options: service.usuarios .getOptions( false ) },
    { field: "global"     , title: "Palavra Chave", class: "w-2", type: "string"   },
];
