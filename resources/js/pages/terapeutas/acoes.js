import service from "@/services/Service";

export default ( parent ) => { return [{ 
    label:"Adicionar", 
    icon: "pi pi-plus",
    groups: [1,2],
    click: () => parent.$router.push( "/terapeutas/adicionar" )
} , { 
    label:"Editar", 
    icon: "pi pi-pencil" , 
    class: "p-button-outlined", 
    showOnlyOne: true, 
    groups: [1,2],
    click: function ( selected ){ parent.$router.push( "/terapeutas/" + selected[0].id + "/editar" ); }
} , { 
    label:"Imprimir", 
    icon: "pi pi-print" , 
    class: "p-button-outlined", 
    showOnlyOne: true, 
    groups: [1,2],
    click: function ( selected ){ parent.imprimir( selected ); }
} , { 
    label:"Excluir", 
    icon: "pi pi-trash", 
    class: "p-button-danger p-button-outlined", 
    showOnlyMany: true, 
    groups: [1,2],
    click: ( selected , table ) => {
        parent.$confirm.require({
            message: 'Você deseja excluir a terapeuta selecionada?',
            header: 'Excluir',
            icon: 'pi pi-exclamation-triangle',
            acceptClass: 'p-button-danger',
            accept: () => {
                let requests = selected.map( terapeuta => {
                    return service.terapeutas.destroy( terapeuta.id );
                });

                Promise.all( requests ).then( () => {
                    table.pesquisar();
                    parent.$toast.add({severity:'info', summary:'Excluída', detail:`A terapeuta foi excluída com sucesso!`, life: 3000})
                });
            },
            reject: () => {
                parent.$toast.add({severity:'error', summary:'Cancelado', detail:'Você cancelou a exclusão!', life: 3000});
            }
        });
    }
}
]};