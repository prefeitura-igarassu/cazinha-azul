import service from "@/services/Service";

export default ( parent ) => { return [{ 
    label:"Adicionar", 
    icon: "pi pi-plus",
    groups: [1,2],
    multiple: false,
    upload: true,
    click: function ( files ){ parent.setDialogNovo( files[0] ); }
} , { 
    label:"Editar", 
    icon: "pi pi-pencil" , 
    class: "p-button-outlined", 
    showOnlyOne: true, 
    groups: [1,2],
    click: function ( selected ){ parent.setDialog( selected[0] ); }
} , { 
    label:"Download", 
    icon: "pi pi-download" , 
    class: "p-button-outlined", 
    showOnlyOne: true, 
    groups: [1,2],
    click: function ( selected ){ parent.download( selected[0] ); }
} , { 
    label:"Excluir", 
    icon: "pi pi-trash", 
    class: "p-button-danger p-button-outlined", 
    showOnlyMany: true, 
    groups: [1,2],
    click: ( selected , table ) => {
        parent.$confirm.require({
            message: 'Você deseja excluir o arquivo selecionado?',
            header: 'Excluir',
            icon: 'pi pi-exclamation-triangle',
            acceptClass: 'p-button-danger',
            accept: () => {
                let requests = selected.map( arquivo => {
                    return service.fichas_arquivos.destroy( arquivo.id );
                });

                Promise.all( requests ).then( () => {
                    table.pesquisar();
                    parent.$toast.add({severity:'info', summary:'Excluído', detail:`O arquivo foi excluído com sucesso!`, life: 3000})
                });
            },
            reject: () => {
                parent.$toast.add({severity:'error', summary:'Cancelado', detail:'Você cancelou a exclusão!', life: 3000});
            }
        });
    }
}
]};