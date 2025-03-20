<template>
    <div class="flex justify-content-between flex-wrap mb-3">
        <div class="flex text-4xl font-bold">Distribuição</div>

        <div class="flex">
            <Button icon="pi pi-refresh" label="Atualizar" @click="atualizar()" />
        </div>
    </div>

    <DataTable :value="arquivos" :loading="loading" class="w-full" tableStyle="min-width: 50rem" size="small">
        <Column field="nome"           header="Arquivo"       ></Column>
        <Column field="distribuido_em" header="Distribuido em"><template #body="{ data , field }">{{ date_format( data[ field ] ) }}</template></Column>
        <Column field="baixado_em"     header="Baixado em"    ><template #body="{ data , field }">{{ date_format( data[ field ] ) }}</template></Column>

        <Column header="Ações">
            <template #body="{ data }">
                <Button icon="pi pi-download" label="download" @click="download( data )" size="small" severity="secondary" />
            </template>
        </Column>

        <template #empty>Nenhum arquivo foi encontrado.</template>
        <template #loading> Estamos pesquisando pelos arquivos. Por favor, aguarde! </template>
    </DataTable>
</template>

<script>
import service   from "@/services/Service";

import DataTable from 'primevue/datatable';
import Column    from 'primevue/column';
import Button    from "primevue/button";
import dayjs     from "dayjs";

export default {
    components: { DataTable, Column, Button },

    data() {
        return {
            arquivos: [],
            loading: false,
        };
    },
    
    methods: {
        atualizar(){
            let empresa_id = service.usuarioLogado?.empresa_id;
            if( !empresa_id ) return ;

            this.loading   = true;

            service.generic.getGeneric( `/antigos/distribuicao/${empresa_id}` ).then( resultado => {
                this.arquivos = resultado;
                this.loading = false;
            });
        },

        download( arquivo ){
            if( !arquivo ) return ;

            let empresa_id = service.usuarioLogado.empresa_id;
            window.open( `/antigos/distribuicao/${empresa_id}/${arquivo.nome}` , "_blank" );
        },

        date_format( data ){
            return data ? dayjs( data ).format( "DD/MM/YYYY HH:mm:ss" ) : null;
        }
    },

    mounted(){
        this.atualizar();
    }
};
</script>
