<template>
    <div class="grid w-full">
        <div class="col-10 grid">
            <Select col="col-2" id="Modelo"     campo="Modelo"  v-model:valor="modelo"     :error="null" :options="modelos"   />
            <Select col="col-4" id="empresa_id" campo="Empresa" v-model:valor="empresa_id" :error="null" :options="empresas"  />
        </div>

        <div class="col-2">
            <ButtonGroup>
                <Button label="Atualizar" text     icon="pi pi-refresh" @click="atualizar" />
                <Button label="Adicionar" outlined icon="pi pi-plus"    @click="$router.push( '/chamados/adicionar' )" />
            </ButtonGroup>
        </div>

        <div class="col" v-for="(coluna,index) in colunas[ modelo ]" :key="index">
            <ChamadoAberto :coluna="coluna" :chamados="getChamado( coluna )" :loading="loading" @visualizar="visualizar"></ChamadoAberto>
        </div>
    </div>

    <ChamadoDialog v-if="dialogVisible" v-model:visible="dialogVisible" :chamado="chamado" @cancelar="dialogVisible = 0"></ChamadoDialog>
</template>

<script>
import Select from "@/components/forms/Select.vue";

import service       from "@/services/Service";
import ChamadoAberto from "./ChamadoAberto.vue";
import ChamadoDialog from "../chamados/ChamadoDialog.vue";
import Button        from "primevue/button";
import ButtonGroup   from "primevue/buttongroup";

export default {
    components: { ChamadoAberto, ChamadoDialog, Button, ButtonGroup, Select },

    data() {
        return {
            modelos : [ 
                { value: 0, label: "Por Prioridade" }, 
                { value: 1, label: "Por Situação"   } 
            ],

            empresas: [],
            chamados: [],

            colunas: [[ 
                { title: "Prioridade Baixa", property: "prioridade", value: 0 },
                { title: "Prioridade Média", property: "prioridade", value: 1 },
                { title: "Prioridade Alta" , property: "prioridade", value: 2 },
            ], [
                { title: "Aberto"          , property: "status"    , value: 0 },
                { title: "Em Andamento"    , property: "status"    , value: 1 },
            ]],

            modelo: 0, 
            empresa_id: null,
            loading: false,

            dialogVisible: false,
            chamado: {},
        };
    },

    watch: {
        empresa_id(){
            this.atualizar();
        },
    },

    methods: {
        init(){
            service.empresas.getOptions().then( r => this.empresas = r );
            this.atualizar();
        },

        visualizar( chamado ){
            this.dialogVisible = true;
            this.chamado = chamado;
        },
        
        atualizar(){
            const p = {
                status: [0,1],                  // abertos e em andamento
                with: [ 'empresa' ],
                orderBy: "requerido_em,asc",
            };

            if( this.empresa_id ){
                p.empresa_id = this.empresa_id;
            }

            this.loading = true;

            service.chamados.search( p ).then( resultado => {
                this.chamados = resultado;
                this.loading = false;
            });
        },

        getChamado( coluna ){
            return this.chamados.filter( chamado => chamado[ coluna.property ] === coluna.value );
        },
    },

    mounted(){
        this.init();
    }
};
</script>
