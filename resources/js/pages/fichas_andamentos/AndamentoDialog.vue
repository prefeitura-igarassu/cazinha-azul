<template>

<Dialog :visible="visible" position="top" :modal="true" @hide="cancelar" header="Andamento" @update:visible="updatedVisible">
    <div class="formgrid grid">
        <Select   col="col-4"   id="status"         campo="Situação"      v-model:valor="novo.status"      :error="errors.status"       :options="situacoes"  />
        <Field    col="col-5"   id="ocorrido_em"    campo="Ocorrido em"   v-model:valor="novo.ocorrido_em" :error="errors.ocorrido_em"  type="datetime-local" />
        <Textarea col="col-12"  id="descricao"      campo="Descrição"     v-model:valor="novo.descricao"   :error="errors.descricao"    ></Textarea>
    </div>

    <template #footer>
        <Button label="Salvar"   icon="pi pi-check" :loading="loading"    @click="salvar"    />
        <Button label="Cancelar" icon="pi pi-times"  class="p-button-text" @click="cancelar" />
    </template>
</Dialog>

</template>
        
<script>
import service  from "@/services/Service";

import Field    from "@/components/forms/Field.vue";
import Textarea from "@/components/forms/Textarea.vue";
import Select   from "@/components/forms/Select.vue";
import Calendar from "@/components/forms/Calendar.vue";

import Dialog   from "primevue/dialog";
import Button   from "primevue/button";

import situacoes  from './situacoes.js';
import dayjs from 'dayjs';

export default {
    components: { 
        Dialog, Button, Field, Textarea, Select 
    },
    
    props: {
        'visible'  : { type: Boolean , default: false },
        'andamento': {},
    },
    
    data() {
        return {    
            novo: {},
            errors: {},
            loading: false,

            situacoes,
        };
    },

    watch: {
        visible( value ){
            this.init();
        },

        andamento(){
            this.init();
        },
    },

    methods: {
        init(){
            this.novo = {
                id         : this.andamento.id,
                ficha_id   : this.andamento.ficha_id,
                status     : this.andamento.status,
                ocorrido_em: this.andamento.ocorrido_em,
                descricao  : this.andamento.descricao,
            };
        },

        cancelar(){
            this.$emit( 'update:visible' , false );
        },

        updatedVisible( value ){
            this.$emit( 'update:visible' , value );
        },

        salvar(){
            this.loading = true;

            this.novo.usuario_id = service.usuarioLogado.id;

            let request = this.novo.id 
                ? service.fichas_andamentos.update( this.novo )
                : service.fichas_andamentos.insert( this.novo );

            request.then( r =>{
                this.loading = false;

                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`O chamado foi atualizado com sucesso!`, life: 3000}) , 10 );
                
                this.$emit( 'salvou'   , r );
                this.$emit( 'cancelar' , r );
            }).catch( err => {
                this.loading = false;
                this.errors = {};
                
                if( err.status == 422 ) this.errors = err.data.errors ?? {};

                if( err.message ) setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail: err.message, life: 3000}) , 10 );
                else              setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );
            });
        },
    },

    mounted(){
        this.init();
    }
}
</script>

