<template>

    <Page :title="title">
        <Errors :errors="errors"></Errors>

        <form @submit.prevent="salvar">
            <div class="formgrid grid">
                <Field   col="col-2"   id="feito_em"     campo="Feito em"     v-model:valor="devolutiva.feito_em"      :error="errors.feito_em"      type="datetime-local" />

                <template v-if="isAdmin">
                    <AutoComplete   col="col-5"   id="ficha_id"        campo="Ficha"        v-model:valor="ficha"     :service="service.fichas"                         @selected="setFicha"     />
                    <AutoComplete   col="col-5"   id="terapeuta_id"    campo="Terapeuta"    v-model:valor="terapeuta" :service="service.terapeutas" :with="['servico']" @selected="setTerapeuta" />
                </template>

                <template v-else>
                    <Field   col="col-3"   id="ficha"        campo="Ficha"        v-model:valor="ficha.nome"               :readonly="true" />
                    <Field   col="col-3"   id="terapeuta"    campo="Terapeuta"    v-model:valor="terapeuta.nome"           :readonly="true" />
                </template>
                
                <Editor  col="col-12"  id="evolucao"     campo="Evolução"     v-model:valor="devolutiva.evolucao"      :error="errors.evolucao"></Editor>
            </div>

            <!--
            'usuario_id'    => [ 'required' , 'integer'  ],
            'ficha_id'      => [ 'required' , 'integer'  ],
            'servico_id'    => [ 'required' , 'integer'  ],
            'terapeuta_id'  => [ 'required' , 'integer'  ],
            'feito_em'      => [ 'required' , 'datetime' ],
            'pontuacao'     => [ 'nullable' , 'array'    ],
            'evolucao'      => [ 'nullable' , 'string'   ],
            -->
        
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
import Field    from "@/components/forms/Field.vue";
import Textarea from "@/components/forms/Textarea.vue";
import AutoComplete from "@/components/forms/AutoComplete.vue";
import Editor   from "@/components/forms/Editor.vue";
import Button   from "primevue/button";
import service  from "@/services/Service";
import dayjs    from "dayjs";

export default {
    components: { Page, Field, Editor, Button, Textarea, AutoComplete, Errors },
    props: [ "id" ],

    data() {
        return {
            devolutiva: {
                feito_em: dayjs().format( 'YYYY-MM-DD HH:mm:ss' )
            },

            loading: false,
            errors : {},

            service: service,
            ficha: {},
            terapeuta: {},
        };
    },

    watch: {
        id(){
            this.init();
        },
    },

    computed: {
        title(){
            return this.id ? `Devolutiva Nº ${this.id}` : "Devolutiva";
        },
        
        isAdmin(){
            return service.usuarioLogado.grupo_id == 1;
        },
    },

    methods: {
        init(){
            this.devolutiva = {
                feito_em: dayjs().format( 'YYYY-MM-DD HH:mm:ss' )
            };

            this.ficha      = {};
            this.terapeuta  = {};

            if( !this.isAdmin )
            {
                Promise.all(
                    service.fichas    .get( this.$route.query.ficha_id     ),
                    service.terapeutas.get( this.$route.query.terapeuta_id ),
                ).then( results => {
                    this.ficha     = results[0];
                    this.terapeuta = results[1];
                }).catch( err => {
                    setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );
                    this.voltar();
                });
            }

            if( this.id ){
                service.devolutivas.get( this.id , { with: [ 'ficha' , 'terapeuta' ] } ).then( r => {
                    this.devolutiva = r;
                    this.ficha = r.ficha;
                    this.terapeuta = r.terapeuta;
                }).catch( r => console.log( r ) );
            }
        },

        salvar(){
            this.loading = true;

            this.devolutiva.usuario_id = service.usuarioLogado.id;

            let request = this.id 
                ? service.devolutivas.update( this.devolutiva )
                : service.devolutivas.insert( this.devolutiva );
            
            request.then( r =>{
                this.loading = false;
                
                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`A devolutiva foi salva com sucesso!`, life: 3000}) , 10 );

                this.voltar();
            }).catch( err => {
                this.loading = false;
                this.errors = {};

                setTimeout( () => this.$toast.add({severity:'error', summary:'Ops!', detail:`Ocorreu um erro!`, life: 3000}) , 10 );

                if( err.status == 422 ) this.errors = err.data.errors ?? {};
            });
        },

        voltar(){
            this.$router.push( "/devolutivas" );
        },

        setFicha( ficha ){
            this.devolutiva.ficha_id = ficha.id;
            this.ficha = ficha;
        },

        setTerapeuta( terapeuta ){
            this.devolutiva.terapeuta_id = terapeuta.id;
            this.devolutiva.servico_id   = terapeuta.servico_id;

            this.terapeuta = terapeuta;
        }
    },

    mounted(){
        this.init();
    }
}
</script>