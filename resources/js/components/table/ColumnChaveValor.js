import globalService from "@/services/Service";

function columnsMounted(){
    this.chaveValor = {
        id: null,
        usuario_id: globalService.usuarioLogado?.id,
        chave: this.tableId,
        valor: {
            selecionadas: [],
            agrupar: null,
        },
    };

    globalService.chaves.getMyByName( this.tableId ).then( r => {
        if( r == null ){
            this.columnsSave();
        }
        else{
            r.valor = JSON.parse( r.valor );
            this.chaveValor = r;

            if( r.valor.selecionadas.length == 0 ){
                this.columnDefaultVisible();
            }
            else{
                this.agrupar = r.valor.agrupar;
                this.colunas_selecionadas = [];

                this.columns.forEach( coluna => {
                    coluna.visible = r.valor.selecionadas.includes( coluna.title );
                    if( coluna.visible ) this.colunas_selecionadas.push( coluna );
                });
            }
        }
    });
}

function columnDefaultVisible(){
    this.colunas_selecionadas = this.columns?.filter( c => c.visible );
    this.columnsSave();
}

function columnsSave(){
    let selecionadas = this.colunas_selecionadas.map( column => {
        return column.title;
    });

    globalService.chaves.save({
        id: this.chaveValor?.id,
        usuario_id: globalService.usuarioLogado?.id,
        chave: this.tableId,
        valor: JSON.stringify({
            selecionadas: selecionadas,
            agrupar: this.agrupar,
        })
    }).then( r => {
        this.chaveValor = r;
        this.chaveValor.valor = JSON.parse( r.valor );
    });
}

export { columnsMounted , columnsSave , columnDefaultVisible };