<template>
    <div>
        <Table 
            tableId="arquivos"
            :service="service" 
            :columns="columns"
            :actions="actions" 
            empty="Nenhum arquivo foi encontrado."
            selectionMode="multiple"
            @visualizar="download"
            @dialog="setDialog"
            :filters="filters"
            :hasChips="false"
            :hasGlobalFilter="false"
            :searchInline="[]"
            :with="['usuario']"
            ref="tabela"
        ></Table>
        
        <ArquivoDialog v-if="dialogVisible" v-model:visible="dialogVisible" :arquivo="arquivo" @cancelar="dialogVisible = false"  @salvou="pesquisar"></ArquivoDialog>
    </div>
</template>

<script>
import Page    from "@/components/Page.vue";
import Table   from "@/components/table/Table.vue";
import colunas from "./tabela.js";
import acoes   from "./acoes.js";
import service from "@/services/Service";
import ArquivoDialog from "./ArquivoDialog.vue";
import dayjs   from "dayjs";

export default {
    components: { Page, Table, ArquivoDialog },
    props: [ "ficha_id" ],

    data() {
        return {
            columns: colunas,
            actions: acoes( this ),
            service: service.fichas_arquivos,
            
            arquivo: {},
            dialogVisible: false,
        };
    },

    computed: {
        filters(){
            return { 
                ficha_id: this.ficha_id 
            };
        },
    },

    methods: {
        visualizar( event ){
            this.setDialog( event.data );
        },

        setDialog( arquivo ){
            this.dialogVisible = true;
            this.arquivo = arquivo;
        },

        download( arquivo ){
            let id = arquivo.id || arquivo.data.id;
            window.open( `/fichas/arquivos/${id}/download` , "_blank" );
        },

        setDialogNovo( file ){
            this.fileToDataURL( file ).then( ttt => {
                this.dialogVisible = true;

                this.arquivo = { 
                    ficha_id: this.ficha_id,
                    anexado_em: dayjs().format( 'YYYY-MM-DD HH:mm:ss' ),
                    
                    nome   : file.name,
                    tipo   : file.type,
                    tamanho: file.size,

                    arquivo: ttt.conteudo,
                };
            });
        },

        fileToDataURL( file ){
            var reader = new FileReader();

            return new Promise((resolve, reject ) => {
                reader.onload = event => {
                    resolve( {
                        nome    : file.name,
                        tamanho : file.size,
                        tipo    : file.type,
                        conteudo: event.target.result,
                    });
                };

                reader.readAsDataURL( file );
            });
        },

        pesquisar(){
            this.$refs.tabela.pesquisar();
        },
    },
}
</script>