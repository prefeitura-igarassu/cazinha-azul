<template>

    <Page :title="title" :errors="errors">
        <form @submit.prevent="salvar">
            <div class="formgrid grid mt-4">
                <Select col="sm:col-4 col-12"  id="usuario_id"  campo="Terapeuta"  v-model:valor="terapeuta.usuario_id"  :error="errors.terapeuta_id"  :options="terapeutas" />
                <Select col="sm:col-4 col-12"  id="servico"     campo="Serviço"    v-model:valor="terapeuta.servico_id"  :error="errors.servico_ic"    :options="servicos"   />
                <Select col="sm:col-4 col-12"  id="unidade_id"  campo="Unidade"    v-model:valor="terapeuta.unidade_id"  :error="errors.unidade_id"    :options="unidades"   />
            </div>

            <DataTable :value="terapeuta.horarios" tableStyle="min-width: 50rem" size="small" class="mb-5">
                <template #header>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <span class="text-xl font-bold">Horários</span>
                        <Button label="Limpar" @click="terapeuta.horarios = createHorarios()" outlined></Button>
                    </div>
                </template>
                
                
                <Column header="Dia da Semana">
                    <template #body="{ data }">
                        {{ dias[ data.dia ]?.label }}
                    </template>
                </Column>

                <Column header="Horário Inicial">
                    <template #body="{ data }"><InputText type="time" v-model="data.horas[0]" class="w-full" size="small" /></template>
                </Column>

                <Column header="Horário Final">
                    <template #body="{ data }"><InputText type="time" v-model="data.horas[1]" class="w-full" size="small" /></template>
                </Column>

                <Column header="Horário Inicial">
                    <template #body="{ data }"><InputText type="time" v-model="data.horas[2]" class="w-full" size="small" /></template>
                </Column>

                <Column header="Horário Final">
                    <template #body="{ data }"><InputText type="time" v-model="data.horas[3]" class="w-full" size="small" /></template>
                </Column>
            </DataTable>
        
            <div class="flex gap-3">
                <Button label="Salvar" :loading="loading"    @click.prevent="salvar" type="submit" />
                <Button label="Cancelar" class="p-button-text" @click="voltar" />
            </div>
        </form>
    </Page>
    
</template>

<script>
import Page     from "@/components/Page.vue";
import Field    from "@/components/forms/Field.vue";
import Select   from "@/components/forms/Select.vue";
import Editor   from 'primevue/editor';
import Button   from "primevue/button";
import service  from "@/services/Service";

import dias      from "./dias";
import DataTable from 'primevue/datatable';
import Column    from 'primevue/column';
import PSelect   from 'primevue/select';
import InputText from 'primevue/inputtext';

export default {
    components: { Page, Field, Editor, Button, Select, DataTable, Column, PSelect, InputText },
    props: [ "id" ],

    data() {
        return {
            terapeuta: {},
            loading: false,
            errors : {},

            servicos: [],
            unidades: [],
            terapeutas: [],

            novo: {},
            dias: dias,
        };
    },

    watch: {
        id(){
            this.init();
        },
    },

    computed: {
        title(){
            return this.id ? `Terapeuta Nº ${this.id}` : "Terapeuta";
        },
    },

    methods: {
        init(){
            this.terapeuta = {
                usuario_id: null,
                unidade_id: null,
                servico_id: null,
                horarios: this.createHorarios(),
            };

            service.unidades.getOptions().then( r => this.unidades = r );
            service.servicos.getOptions().then( r => this.servicos = r );
            service.usuarios.getOptions().then( r => this.terapeutas = r );

            if( this.id ){
                service.terapeutas.get( this.id ).then( r => {
                    this.terapeuta = r;
                    if( !r.horarios ) this.terapeuta.horarios = this.createHorarios();
                }).catch( r => console.log( r ) );
            }
        },

        createHorarios(){
            return [
                { dia: 0, horas: [ "00:00" , "00:00" , "00:00" , "00:00" ] },
                { dia: 1, horas: [ "00:00" , "00:00" , "00:00" , "00:00" ] },
                { dia: 2, horas: [ "00:00" , "00:00" , "00:00" , "00:00" ] },
                { dia: 3, horas: [ "00:00" , "00:00" , "00:00" , "00:00" ] },
                { dia: 4, horas: [ "00:00" , "00:00" , "00:00" , "00:00" ] },
                { dia: 5, horas: [ "00:00" , "00:00" , "00:00" , "00:00" ] },
                { dia: 6, horas: [ "00:00" , "00:00" , "00:00" , "00:00" ] },
            ];
        },

        getNome(){
            return this.terapeutas.find( terapeuta => {
                return this.terapeuta.usuario_id == terapeuta.value
            } )?.label;
        },

        salvar(){
            this.loading = true;

            this.terapeuta.nome = this.getNome();

            let request = this.id 
                ? service.terapeutas.update( this.terapeuta )
                : service.terapeutas.insert( this.terapeuta );
            
            request.then( r =>{
                this.loading = false;
                
                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`O terapeuta foi salva com sucesso!`, life: 3000}) , 10 );

                this.voltar();
            }).catch( err => {
                this.loading = false;
                this.errors = {};

                setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );

                if( err.status == 422 ) this.errors = err.data.errors ?? {};
            });
        },

        voltar(){
            this.$router.push( "/terapeutas" );
        },

        adicionar(){
            this.terapeuta.horarios.push( this.novo );
            this.novo = {};
        }
    },

    mounted(){
        this.init();
    }
}
</script>