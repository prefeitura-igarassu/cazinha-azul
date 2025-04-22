import service from "@/services/Service";

export default ( parent ) => { return [{ 
    label:"Adicionar", 
    icon: "pi pi-plus", 
    click: () => parent.$router.push( "/usuarios/adicionar" )
} , { 
    label:"Visualizar", 
    icon: "pi pi-pencil" , 
    class: "p-button-outlined", 
    showOnlyOne: true, 
    click: function ( selected ){ parent.$router.push( "/usuarios/" + selected[0].id ); }
} , { 
    label:"Editar", 
    icon: "pi pi-pencil" , 
    class: "p-button-outlined", 
    showOnlyOne: true, 
    click: function ( selected ){ parent.$router.push( "/usuarios/" + selected[0].id + "/editar" ); }
} , { 
    label:"Gerar Token", 
    icon: "pi pi-key" , 
    class: "p-button-outlined", 
    showOnlyOne: true, 
    click: function ( selected ){ parent.generateToken( selected[0].id ); }
} , { 
    label:"Excluir", 
    icon: "pi pi-trash", 
    class: "p-button-danger p-button-outlined", 
    showOnlyMany: true, 
    click: ( selected , table ) => {
        parent.$confirm.require({
            message: 'Você deseja excluir os usuários selecionados?',
            header: 'Excluir',
            icon: 'pi pi-exclamation-triangle',
            acceptClass: 'p-button-danger',
            accept: () => {
                let requests = selected.map( servico => {
                    return service.usuarios.destroy( servico.id );
                });

                Promise.all( requests ).then( () => {
                    table.pesquisar();
                    parent.$toast.add({severity:'info', summary:'Excluído', detail:`Os usuários foram excluídos com sucesso!`, life: 3000})
                });
            },
            reject: () => {
                parent.$toast.add({severity:'error', summary:'Cancelado', detail:'Você cancelou a exclusão!', life: 3000});
            }
        });
    }
} , { 
    label:"Gerar Nova Senha", 
    icon: "pi pi-key", 
    class: "p-button-outlined", 
    showOnlyMany: true, 
    click: ( selected , table ) => {
        parent.$confirm.require({
            message: 'Você deseja gerar uma nova senha selecionados?',
            header: 'Nova Senha',
            icon: 'pi pi-exclamation-triangle',
            acceptClass: 'p-button-danger',
            accept: () => {
                let requests = selected.map( servico => {
                    return service.usuarios.gerarNovaSenha( servico.id );
                });

                Promise.all( requests ).then( () => {
                    parent.$toast.add({severity:'info', summary:'Nova Senha', detail:`Uma nova senha foi gerada com sucesso!`, life: 3000})
                });
            },
            reject: () => {
                parent.$toast.add({severity:'error', summary:'Cancelado', detail:'Você cancelou a exclusão!', life: 3000});
            }
        });
    }
}]};