import service from "@/services/Service";

export default ( parent ) => { return [{ 
    label:"Adicionar", 
    icon: "pi pi-plus",
    groups: [1,2],
    click: function (){ parent.setDialogNovo(); }
} , { 
    label:"Editar", 
    icon: "pi pi-pencil" , 
    class: "p-button-outlined", 
    showOnlyOne: true, 
    groups: [1,2],
    click: function ( selected ){ parent.setDialog( selected[0] ); }
} , { 
    label:"Excluir", 
    icon: "pi pi-trash", 
    class: "p-button-danger p-button-outlined", 
    showOnlyMany: true, 
    groups: [1,2],
    click: ( selected , table ) => {
        parent.$confirm.require({
            message: 'Você deseja excluir o andamento selecionado?',
            header: 'Excluir',
            icon: 'pi pi-exclamation-triangle',
            acceptClass: 'p-button-danger',
            accept: () => {
                let requests = selected.map( andamento => {
                    return service.fichas_andamentos.destroy( andamento.id );
                });

                Promise.all( requests ).then( () => {
                    table.pesquisar();
                    parent.$toast.add({severity:'info', summary:'Excluído', detail:`O andamento foi excluído com sucesso!`, life: 3000})
                });
            },
            reject: () => {
                parent.$toast.add({severity:'error', summary:'Cancelado', detail:'Você cancelou a exclusão!', life: 3000});
            }
        });
    }
}
]};