<template>

<Dialog :visible="visible" position="top" :modal="true" @hide="cancelar" header="Solicitar" @update:visible="updatedVisible" style="min-width: 50%">
    <Errors :errors="errors"></Errors>
    
    <div class="formgrid grid">
        <Select   col="col-12"  id="ficha_id"       campo="Ficha"           v-model:valor="novo.ficha_id"      :error="errors.ficha_id"       :options="fichas"  v-if="!solicitacao.ficha_id" />

        <Field    col="col-2"   id="solicitado_em"  campo="Solicitado em"   v-model:valor="novo.solicitado_em" :error="errors.solicitado_em"  type="datetime-local" />
        <Select   col="col-2"   id="status"         campo="Situação"        v-model:valor="novo.status"        :error="errors.status"         :options="situacoes"  @changed="pesquisarTerapeutas" />
        <Select   col="col-8"   id="servico_id"     campo="Serviço"         v-model:valor="novo.servico_id"    :error="errors.servico_id"     :options="servicos"   @changed="pesquisarTerapeutas" />

        <template v-if="novo.status == 1">
            <Select   col="col-2"   id="terapeuta_id"   campo="Terapeuta"       v-model:valor="novo.terapeuta_id"    :error="errors.terapeuta_id"     :options="terapeutas" label="nome" value="id" @changed="pesquisarHorarios" />
            <Select   col="col-2"   id="dia"            campo="Dia"             v-model:valor="novo.dia"             :error="errors.dia"              :options="dias"       @changed="verificarSessoes" />
            <Select   col="col-1"   id="horario"        campo="Horáro"          v-model:valor="novo.horario"         :error="errors.dia"              :options="horarios"   @changed="verificarSessoes" />
            <Field    col="col-2"   id="inicial"        campo="Período Inicial" v-model:valor="novo.periodo_inicial" :error="errors.periodo_inicial"  type="date"           @changed="verificarSessoes" />
            <Field    col="col-2"   id="final"          campo="Período Final"   v-model:valor="novo.periodo_final"   :error="errors.periodo_final"    type="date"           @changed="verificarSessoes" />
            
            <div class="col">
                <b class="block mt-1">Sessões</b>

                <div class="mt-2">
                    <Message class="w-full" v-if="sessoesLoading"      severity="secondary">Verificando disponibilidade...</Message>
                    <Message class="w-full" v-if="sessoes.length == 0" severity="success">
                        <template v-if     ="datas.length == 0">Nenhuma sessão será criada!</template>
                        <template v-else-if="datas.length == 1">Apenas 1 sessão será criada!</template>
                        <template v-else                       >{{ datas.length }} sessões serão criadas!!</template>
                    </Message>
                    <Message class="w-full" v-else                     severity="error">Esse horário já está reservado!</Message>
                </div>
            </div>
        </template>
    </div>

    <template #footer>
        <Button label="Salvar"   icon="pi pi-check" :loading="loading"     @click="salvar"   />
        <Button label="Cancelar" icon="pi pi-times"  class="p-button-text" @click="cancelar" />
    </template>
</Dialog>

</template>
        
<script>
import service  from "@/services/Service";
import Field    from "@/components/forms/Field.vue";
import Select   from "@/components/forms/Select.vue";
import Errors   from "@/components/Errors.vue";

import Dialog   from "primevue/dialog";
import Button   from "primevue/button";
import Message  from "primevue/message";

import situacoes from './situacoes.js';
import dias  from './dias.js';
import dayjs from 'dayjs';

export default {
    components: { Dialog, Button, Field, Select, Errors, Message },
    
    props: {
        'visible': { type: Boolean , default: false },
        'solicitacao': {},
    },
    
    data() {
        return {    
            novo: {},
            errors: {},
            loading: false,

            situacoes, dias, dias_horarios: [],
            servicos: [], terapeutas: [], fichas: [],

            sessoesLoading: false,
            datas: [], sessoes: [],
        };
    },

    watch: {
        visible(){ this.init(); },
        solicitacao(){ this.init(); },
    },

    computed: {
        horarios(){
            let horarios = [];

            if( this.dias_horarios[ this.novo.dia ] )
            {
                this.dias_horarios[ this.novo.dia ].forEach( hora => horarios.push( { label: hora , value: hora }) );
            }

            return horarios;
        },
    },

    methods: {
        init(){
            this.novo = this.solicitacao ? {
                id           : this.solicitacao.id,
                unidade_id   : this.solicitacao.unidade_id,
                ficha_id     : this.solicitacao.ficha_id,
                usuario_id   : this.solicitacao.usuario_id,
                status       : this.solicitacao.status,
                solicitado_em: this.solicitacao.solicitado_em,
                servico_id   : this.solicitacao.servico_id,

                terapeuta_id    : this.solicitacao.terapeuta_id,
                dia             : this.solicitacao.dia,
                horario         : this.solicitacao.horario,
                periodo_inicial : this.solicitacao.periodo_inicial,
                periodo_final   : this.solicitacao.periodo_final,
            } : {};

            service.servicos.getOptions().then( r => this.servicos = r );

            if( !this.solicitacao.ficha_id )
            {
                service.fichas.getOptions().then( r => this.fichas = r );
            }

            if( this.novo.status == 1 )
            {
                this.pesquisarTerapeutas();
                this.pesquisarHorarios();
            }
        },

        cancelar(){
            this.$emit( 'update:visible' , false );
        },

        updatedVisible( value ){
            this.$emit( 'update:visible' , value );
        },

        salvar(){
            if( !this.isValido() ) return ;

            this.loading = true;

            let request = this.novo.id
                ? service.fichas_servicos.update( this.novo )
                : service.fichas_servicos.insert( this.novo );

            request.then( r =>{
                this.loading = false;

                setTimeout( () => this.$toast.add({severity:'info', summary:'Salvou', detail:`O serviço foi solicitado com sucesso!`, life: 3000}) , 10 );
                
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

        isValido(){
            this.errors = {};

            if( !this.novo.ficha_id ) this.errors[ "ficha_id" ] = [ "Nenhuma ficha foi especificada!" ];

            if( this.novo.status == 1 )
            {
                if( !this.novo.servico_id      ) this.errors[ "servico_id"   ] = [ "Nenhum serviço foi especificado!" ];
                if( !this.novo.terapeuta_id    ) this.errors[ "terapeuta_id" ] = [ "Nenhum terapeuta foi especificado!" ];
                if( this.novo.dia == null      ) this.errors[ "dia"          ] = [ "Nenhum dia foi especificado!" ];
                if( !this.novo.horario         ) this.errors[ "horario"      ] = [ "Nenhum horário foi especificado!" ];
                if( !this.novo.periodo_inicial ) this.errors[ "periodo_inicial" ] = [ "Nenhum período inicial foi especificado!" ];
                if( !this.novo.periodo_final   ) this.errors[ "periodo_final"   ] = [ "Nenhum período final foi especificado!" ];

                if( this.sessoes.length > 0 ) this.errors[ "sessões" ] = [ "Este dia e horário já está reservado!" ];
                if( this.datas.length == 0  ) this.errors[ "sessões" ] = [ "Nenhuma sessão será criada! Verifique o período!" ];
            }

            return Object.keys( this.errors ).length == 0;
        },

        // ------------ //
        // ------------ //
        // ------------ //

        pesquisarTerapeutas(){
            if( this.novo.status != 1 ){
                return ;
            }

            const params = {
                unidade_id: this.novo.unidade_id,
                servico_id: this.novo.servico_id,
            };

            return service.terapeutas.search( params ).then( r => {
                this.terapeutas = r;
            });
        },

        pesquisarHorarios(){
            return service.terapeutas.getGeneric( `/terapeutas/${this.novo.terapeuta_id}/horarios` ).then( r => {
                this.dias_horarios = r;
                this.dias = [];

                r.forEach( ( horarios , index ) => {
                    if( horarios.length == 0 ) return ;
                    this.dias.push( dias[ index ] );
                });
            });
        },

        verificarSessoes(){
            this.datas   = [];
            this.sessoes = [];

            if( this.novo.dia == null 
                || !this.novo.horario 
                || !this.novo.periodo_inicial 
                || !this.novo.periodo_final 
            ){
                return ;
            }

            let   atual = dayjs( this.novo.periodo_inicial );
            const fim   = dayjs( this.novo.periodo_final   );

            while ( atual.isBefore( fim ) || atual.isSame( fim , 'day' ) ) 
            {
                if ( atual.day() === this.novo.dia ) 
                    this.datas.push( atual.format( "YYYY-MM-DD" ) + ` ${this.novo.horario}:00` );

                atual = atual.add( 1 , 'day' );
            }

            this.pesquisarSessoes( this.datas );
        },

        pesquisarSessoes( sessoes ){
            const params = {
                terapeuta_id : this.novo.terapeuta_id,
                agendado_para: sessoes,
            };

            this.sessoesLoading = true;
            this.sessoes = [];

            service.sessoes.search( params ).then( r => {
                this.sessoesLoading = false;
                this.sessoes = r;
            } );
        },
    },

    mounted(){
        this.init();
    }
}
</script>

