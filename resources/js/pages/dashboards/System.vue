<template>
    <div class="grid">
        <div class="col-3">Memória: {{ status.memory }}</div>
        <div class="col-3">CPU: {{ status.cpu }}%</div>
        <div class="col-3">Disco: {{ status.disc }}%</div>
        <div class="col-3">
            <Button label="Mostrar Log" @click="get"></Button>
            <Button label="Limpar Log"  @click="limpar"></Button>
        </div>
    </div>

    <div class="col-12">
        <textarea v-model="log"></textarea>
    </div>
</template>

<script>
import dayjs   from "dayjs";
import service from "@/services/Service";

import Button from "primevue/button";

export default {
    components: { Resume, GraphicTextarea, Button },
    props: [ "empresa_id" , "periodo" ],

    data() {
        return {
            active: 0,
            timeout: null,
            loading: false,

            log: null,
            status: {},
        };
    },

    watch: {
		active( value ){
			clearTimeout( this.timeout );
			this.pesquisar( value , this.query( this.grupos[ value ].groupBy ) );
		},

        empresa_id( value ){
            clearTimeout( this.timeout );
			this.pesquisar( this.active , this.query( this.grupos[ this.active ].groupBy ) );
        },

        periodo( value ){
            clearTimeout( this.timeout );
			this.pesquisar( this.active , this.query( this.grupos[ this.active ].groupBy ) );
        }
	},

	methods: {
		pesquisar( index , query ){
            this.loading = true;

            this.timeout = setTimeout( () => {
                service.pessoas.getDashboardGroup( query )
                    .then ( r  => this.setData( index , r ) )
                    .then ( () => this.loading = false )
                    .catch( () => this.loading = false );
            } , 100 );
        },

		setData( index , r ){
			this.grupos[ index ].data = Array.isArray( r ) 
				? r 
				: [ r ];
		},

		query( groupBy ){
            let p = {  groupBy: groupBy , op: 'count' };

            if( this.periodo ){
                p.andamentos = {
                    created_at: this.dateFormat(),
                };
            }

            if( this.empresa_id )
                p.empresa_id = this.empresa_id;

            return p;
        },

		dateFormat(){
            if( !this.periodo ) return null;
            
            if( Array.isArray( this.periodo ) ){
                return {
                    'gt': dayjs( this.periodo[0] ).format( 'YYYY-MM-DD' ) + " 00:00:00",
                    'lt': dayjs( this.periodo[1] ).format( 'YYYY-MM-DD' ) + " 23:59:59",
                };
            }
            else{
                return {
                    'gt': dayjs( this.periodo ).format( 'YYYY-MM-DD' ) + " 00:00:00",
                    'lt': dayjs( this.periodo ).format( 'YYYY-MM-DD' ) + " 23:59:59",
                };
            }
        }
	},

    mounted(){
        this.active = 0;
    }
}
</script>