<template>

    <div class="flex justify-content-between flex-wrap">
        <div class="flex text-4xl font-bold">{{ terapeuta.nome }}</div>
        <div class="flex">
            <Actions :actions="actions" :selected="[ this.terapeuta ]" @pesquisar="voltar" />
        </div>
    </div>
        
    <div class="flex gap-4 flex-wrap my-4">
        <property col="" title="Unidade"     :value="terapeuta.unidade?.nome"></property>
        <property col="" title="Serviço"     :value="terapeuta.servico?.nome"></property>
    </div>

    <div class="rounded-lg p-4 bg-white">
        <Tabs value="0">
            <TabList>
                <Tab value="0">Horários</Tab>
                <Tab value="1">Lista de Espera</Tab>
            </TabList>
            
            <TabPanels>
                <TabPanel value="0">
                    <ul>
                        <template v-for="(dia,index) in terapeuta.agenda" :key="index">
                            <li class="font-bold mt-5">{{ dia.label }}</li>

                            <li v-if="dia.horarios.length == 0" class="m-4">
                                <Message severity="warn">sem atendimento neste dia!</Message>
                            </li>
                            
                            <template v-for="(horario,index) in dia.horarios" :key="index">
                                <li class="m-4">
                                    <Message v-if="horario.reserva" severity="error">
                                        {{ horario.hora }} - reservado para {{ horario.reserva?.ficha?.nome }}
                                        (período: {{ periodoFormat( horario.reserva ) }})
                                    </Message>

                                    <Message v-else severity="info" class="cursor-pointer" @click="reservar( dia.value , horario.hora )" >{{ horario.hora }} - livre</Message>
                                </li>
                            </template>
                        </template>
                    </ul>
                </TabPanel>

                <TabPanel value="1">
                    <ListaDeEsperaTable :terapeuta_id="id"></ListaDeEsperaTable>
                </TabPanel>
            </TabPanels>
        </Tabs>
    </div>

    <FichaServicoDialog v-model:visible="dialog" :solicitacao="solicitacao" @salvou="salvou" @cancelar="cancelar"></FichaServicoDialog>
</template>

<script>
import View     from "@/components/views/View.vue";
import service  from "@/services/Service";
import Property from "../fichas/Property.vue";
import acoes    from "./acoes";
import colunas  from "./tabela";
import dayjs    from "dayjs";
import dias     from "../fichas_servicos/dias";
import Message  from "primevue/message";

import Actions from "@/components/table/Actions.vue";
import FichaServicoDialog from "../fichas_servicos/FichaServicoDialog.vue";

import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import ListaDeEsperaTable from "../fichas_servicos/ListaDeEsperaTable.vue";

export default {
    components: { 
        View, Property, Actions, Message, FichaServicoDialog, 
        Tabs, TabList, Tab, TabPanels, TabPanel,
        ListaDeEsperaTable
    },
    props: [ "id" ] ,

    data() {
        return {
            terapeuta: {},
            actions: acoes( this ),
            columns: colunas,

            solicitacao: {},
            dialog: false,
        };
    },

    watch: {
        id( value ){
            this.init();
        },
    },

    methods: {
        voltar(){
            this.$router.push( "/terapeutas" );
        },

        init(){
            service.terapeutas.get( this.id , { with: [ 'unidade' , 'servico' ]} ).then( r => {
                this.terapeuta = { ...r , agenda: [] };
                this.getHorarios();
            });
        },

        getHorarios(){
            Promise.all([
                service.terapeutas.getGeneric( `/terapeutas/${this.id}/horarios` ),
                service.fichas_servicos.search( { terapeuta_id: this.id , with: [ 'ficha' ] , orderBy: "dia,asc" } )
            ]).then( results => {
                dias.forEach( (dia,index) => {
                    this.terapeuta.agenda.push({
                        ... dia,
                        horarios: this.getHorariosFromDia( index , results ),
                    });
                });
            } );
        },
        
        getHorariosFromDia( dia , results ){
            let horarios = [];

            results[ 0 ][ dia ].forEach( hora => {
                let encontrou = null;

                for( let i = 0 ; i < results[1].length; i++ ){
                    const reserva = results[1][i];

                    if( reserva.dia >  dia ) break ;     // não precisa continuar
                    if( reserva.dia != dia ) continue ;
                    
                    if( reserva.horario == hora )
                    {
                        encontrou = reserva;            // seleciona
                        results[1].splice( i , 1 );     // remover, já que foi encontrado
                        break ;
                    }
                }

                horarios.push({
                    hora: hora,
                    reserva: encontrou,
                });
            });

            return horarios;
        },

        periodoFormat( reserva ){
            const d = dayjs( reserva.periodo_inicial ).format( "DD/MM/YYYY" );
            const f = dayjs( reserva.periodo_final   ).format( "DD/MM/YYYY" );

            return `${d} a ${f}`;
        },

        reservar( dia , horario ){
            this.solicitacao = {
                unidade_id: this.terapeuta.unidade_id,
                servico_id: this.terapeuta.servico_id,
                terapeuta_id: this.terapeuta.id,
                dia, horario,

                usuario_id: service.usuarioLogado.id,
                status: 1,
                solicitado_em: dayjs().format( "YYYY-MM-DD HH:mm:ss" ),
            };

            this.dialog = true;
        },

        cancelar(){
            this.solicitacao = {};
            this.dialog = false;
        },

        salvou(){
            this.getHorarios();
        }
    },

    mounted(){
        if( this.id ){
            this.init();
        }
    }
}
</script>