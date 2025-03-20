<template>

<Dialog :visible="visible" position="top" :modal="true" @hide="cancelar" header="Andamento" @update:visible="updatedVisible">
    <div class="formgrid grid">
        <Field    col="col-8"   id="titulo"       campo="Título"        v-model:valor="novo.titulo"      :error="errors.titulo"    />
        <Field    col="col-4"   id="anexado_em"   campo="Anexado em"    v-model:valor="novo.anexado_em"  :error="errors.anexado_em"  type="datetime-local" />
        <Textarea col="col-12"  id="descricao"    campo="Descrição"     v-model:valor="novo.descricao"   :error="errors.descricao" ></Textarea>

        <div class="col-12">
            <div class="flex gap-3">
                <span><b>arquivo:</b> {{ novo.nome }}</span>
                <span><b>tipo:</b> {{ novo.tipo }}</span>
                <span><b>tamanho:</b> {{ tamanho }}</span>
            </div>
        </div>
    </div>

    <template #footer>
        <Button label="Salvar"   icon="pi pi-check"     :loading="loading"    @click="salvar"   />
        <Button label="Download" icon="pi pi-download"  class="p-button-text" @click="download" v-if="this.arquivo.id" />
        <Button label="Cancelar" icon="pi pi-times"     class="p-button-text" @click="cancelar" />
    </template>
</Dialog>

</template>
        
<script>
import service  from "@/services/Service";

import Field    from "@/components/forms/Field.vue";
import Textarea from "@/components/forms/Textarea.vue";

import Dialog   from "primevue/dialog";
import Button   from "primevue/button";

import { humanFileSize } from "./tabela";

export default {
    components: { 
        Dialog, Button, Field, Textarea 
    },
    
    props: {
        'visible'  : { type: Boolean , default: false },
        'arquivo': {},
    },
    
    data() {
        return {    
            novo: {},
            errors: {},
            loading: false,
        };
    },

    watch: {
        visible( value ){
            this.init();
        },

        arquivo(){
            this.init();
        },
    },

    computed: {
        tamanho(){
            return humanFileSize( this.novo.tamanho );
        },
    },

    methods: {
        init(){
            this.novo = {
                id         : this.arquivo.id,
                ficha_id   : this.arquivo.ficha_id,
                anexado_em : this.arquivo.anexado_em,
                titulo     : this.arquivo.titulo,
                descricao  : this.arquivo.descricao,

                nome       : this.arquivo.nome,
                tipo       : this.arquivo.tipo,
                tamanho    : this.arquivo.tamanho,
            };

            if( this.arquivo.arquivo )
                this.novo.arquivo = this.arquivo.arquivo;
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
                ? service.fichas_arquivos.update( this.novo )
                : service.fichas_arquivos.insert( this.novo );

            request.then( r =>{
                this.loading = false;

                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`O arquivo foi atualizado com sucesso!`, life: 3000}) , 10 );
                
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

        download(){
            let id = this.arquivo.id;
            window.open( `/fichas/arquivos/${id}/download` , "_blank" );
        },
    },

    mounted(){
        this.init();
    }
}

</script>

