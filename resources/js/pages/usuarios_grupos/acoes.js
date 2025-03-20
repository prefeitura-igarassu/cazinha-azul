import service from "@/services/Service";

export default ( parent ) => { return [{ 
    label:"Adicionar", 
    icon: "pi pi-plus", 
    click: () => parent.$router.push( "/usuarios_grupos/adicionar" )
} , { 
    label:"Editar", 
    icon: "pi pi-pencil" , 
    class: "p-button-outlined", 
    showOnlyOne: true, 
    click: function ( selecionados ){ parent.$router.push( "/usuarios_grupos/" + selecionados[0].id ); }
} , { 
    label:"Excluir", 
    icon: "pi pi-trash", 
    class: "p-button-danger p-button-outlined", 
    showOnlyMany: true, 
    click: ( selecionados ) => {
        parent.$confirm.require({
            message: 'Você deseja excluir os grupos selecionados?',
            header: 'Excluir',
            icon: 'pi pi-exclamation-triangle',
            acceptClass: 'p-button-danger',
            accept: () => {
                let requests = selecionados.map( servico => {
                    return service.grupos.destroy( servico.id );
                });

                Promise.all( requests ).then( () => {
                    parent.selecionados = [];
                    parent.pesquisar();
                    parent.$toast.add({severity:'info', summary:'Excluído', detail:`Os grupos foram excluídos com sucesso!`, life: 3000})
                });
            },
            reject: () => {
                parent.$toast.add({severity:'error', summary:'Cancelado', detail:'Você cancelou a exclusão!', life: 3000});
            }
        });
    }
}, { 
    label:"Atualizar", 
    icon: "pi pi-refresh" , 
    class: "p-button-outlined", 
    click: () => parent.pesquisar() 
}
]};