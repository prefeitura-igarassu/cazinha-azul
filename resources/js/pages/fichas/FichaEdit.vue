<template>

    <Page :title="title">
        <Errors :errors="errors"></Errors>

        <form @submit.prevent="salvar">
            <div class="formgrid grid mt-4">
                <div class="col-12 sm:col-2">
                    <File     col="w-full"          id="imagem"         campo="Imagem"               v-model:valor="ficha.imagem"        accept="image/*" />
                    <img v-if="ficha.imagem" :src="ficha.imagem.conteudo" />
                </div>

                <div class="col-12 sm:col-10 grid">
                    <Select   col="sm:col-2 col-12" id="unidade_id"     campo="Unidade"              v-model:valor="ficha.unidade_id"    :error="errors.unidade_id"    :options="unidades" />
                    <Field    col="sm:col-2 col-12" id="cadastrado_em"  campo="Cadastrado em"        v-model:valor="ficha.cadastrado_em" :error="errors.cadasrado_em"  type="datetime-local" />
                    <CpfCnpj  col="sm:col-2 col-12" id="cpf"            campo="CPF"                  v-model:valor="ficha.cpf"           :error="errors.cpf" :cpf="true" :cnpj="false" />
                    <Field    col="sm:col-6 col-12" id="nome"           campo="Nome"                 v-model:valor="ficha.nome"          :error="errors.nome"        />
                    
                    <Field    col="sm:col-4 col-12" id="mae_nome"       campo="Nome da Mãe"          v-model:valor="ficha.mae_nome"      :error="errors.mae_nome"    />
                    <Field    col="sm:col-4 col-12" id="pai_nome"       campo="Nome do Pai"          v-model:valor="ficha.pai_nome"      :error="errors.pai_nome"    />
                    <Field    col="sm:col-4 col-12" id="responsavel"    campo="Nome do Responsável"  v-model:valor="ficha.responsavel"   :error="errors.responsavel" />

                    <Field    col="sm:col-2 col-12" id="nascido_em"     campo="Data de Nascimento"   v-model:valor="ficha.nascido_em"    :error="errors.nascido_em"    type="date"/>
                    <Field    col="sm:col-2 col-12" id="sus"            campo="SUS Nº"               v-model:valor="ficha.sus"           :error="errors.sus"         />
                    <Field    col="sm:col-2 col-12" id="nis"            campo="NIS Nº"               v-model:valor="ficha.nis"           :error="errors.nis"         />
                    
                    <Field    col="sm:col-6 col-12" id="escola"         campo="Escola"               v-model:valor="ficha.escola"        :error="errors.escola"      />
                    <Field    col="sm:col-6 col-12" id="endereco"       campo="Endereço"             v-model:valor="ficha.endereco"      :error="errors.endereco"    />
                    <Field    col="sm:col-6 col-12" id="posto_saude"    campo="Posto de Saúde"       v-model:valor="ficha.posto_saude"   :error="errors.posto_saude" />
                    <Field    col="sm:col-4 col-12" id="telefone"       campo="Telefone"             v-model:valor="ficha.telefone"      :error="errors.telefone"    />
                    <Field    col="sm:col-4 col-12" id="email"          campo="E-mail"               v-model:valor="ficha.email"         :error="errors.email"       />

                    <Field    col="sm:col-4 col-12" id="cid"            campo="CID's"                v-model:valor="ficha.cid"           :error="errors.cid" descricao="Caso tenha mais de um, separar por virgulas." regex="[0-9|a-z|A-Z|.|,]" />

                    <Textarea col="col-12"          id="observacao"     campo="Observação"           v-model:valor="ficha.observacao"    :error="errors.observacao"  />
                </div>
            </div>
        
            <div class="flex gap-3">
                <Button label="Salvar" :loading="loading"    @click.prevent="salvar" type="submit" />
                <Button label="Cancelar" class="p-button-text" @click="voltar" />
            </div>
        </form>
    </Page>
    
</template>

<script>
import Page     from "@/components/Page.vue";
import Errors   from "@/components/Errors.vue";
import CpfCnpj  from "@/components/forms/CpfCnpj.vue";
import Field    from "@/components/forms/Field.vue";
import Textarea from "@/components/forms/Textarea.vue";
import Select   from "@/components/forms/Select.vue";
import File     from "@/components/forms/File.vue";
import Editor   from 'primevue/editor';
import Button   from "primevue/button";

import service  from "@/services/Service";
import dayjs    from "dayjs";

export default {
    components: { Page, Field, Editor, Button, Textarea, CpfCnpj, Select, File, Errors },
    props: [ "id" ],

    data() {
        return {
            ficha: {},
            loading: false,
            errors : {},

            unidades: [],
        };
    },

    watch: {
        id(){
            this.init();
        },
    },

    computed: {
        title(){
            return this.id ? `Ficha Nº ${this.id}` : "Ficha";
        },
    },

    methods: {
        init(){
            this.ficha = {
                cadastrado_em: dayjs().format( 'YYYY-MM-DD HH:mm:ss' ),
                unidade_id: 1,
            };

            service.unidades.getOptions().then( r => {
                this.unidades = r;
                //if( !this.ficha.unidade_id )this.ficha.unidade_id = r[0].value;
            });

            if( this.id ){
                service.fichas.get( this.id ).then( r => {
                    this.ficha = r;
                    if( this.ficha.cid ) this.ficha.cid = this.ficha.cid.join( "," );
                }).catch( r => console.log( r ) );
            }
        },

        salvar(){
            this.loading = true;

            if( this.ficha.cid ){
                this.ficha.cid = this.ficha.cid.split( "," );
            }

            let request = this.id 
                ? service.fichas.update( this.ficha )
                : service.fichas.insert( this.ficha );
            
            request.then( r =>{
                this.loading = false;
                
                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`A ficha foi salva com sucesso!`, life: 3000}) , 10 );

                this.voltar();
            }).catch( err => {
                this.loading = false;
                this.errors = {};

                setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );

                if( err.status == 422 ) this.errors = err.data.errors ?? {};
            });
        },

        voltar(){
            this.$router.push( "/fichas" );
        },

        upload( event ){
            console.log( "event" , event );
            this.ficha.imagem = event.files[0];
        }
    },

    mounted(){
        this.init();
    }
}
</script>