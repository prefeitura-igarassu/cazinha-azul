<template>

    <div>
        <h2>{{ title }}</h2>

        <div class="grid grid-cols-12">
            <div class="col-6">
                <Chart :type="type" :data="chartData" :options="chartOptions"/>
            </div>

            <div class="col-6">
                <DataTable :value="dataWithPercentage" responsiveLayout="scroll">
                    <Column :field="label" header="Item" :sortable="true">
                        <template #body="slotProps">
                            {{ getLabel( slotProps.data[ label ] ) }}
                        </template>
                    </Column>
                    
                    <Column :field="value"      header="Valor" :sortable="true">
                        <template #body="slotProps">
                            <div class="text-right w-full">
                                {{ format( slotProps.data[ value ] , slotProps.data ) }}
                            </div>
                        </template>
                    </Column>

                    <Column field="Percentage"  header="%"     :sortable="true">
                        <template #body="slotProps">
                            <div class="text-right w-full">
                                {{ parseFloat( slotProps.data.percentage ).toFixed( 2 ) }}%
                            </div>
                        </template>
                    </Column>

                    <template #footer v-if="data && data.length > 1">
                        <slot name="footer" :data="data">A média é {{ format( media ) }}.</slot>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>

</template>

<script>
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Chart from "primevue/chart";

export default {
    components: { DataTable, Column, Chart },

    props: {
        'title'  : {},
        'type'   : { default: 'bar'   , type: String },
        'data'   : { required: true   , type: Array  },
        'label'  : { default: "label" , type: String },
        'value'  : { default: "total" , type: String },
        'labels' : { type: Array },
        'options': {},
        'format' : { type: Function , default: ( value , data ) => value },
        'colors' : { default: [], type: Array  },
    },

    data(){
        return {

        };
    },

    computed: {
        //from: https://primefaces.org/primevue/chart/pie
        chartData(){
            let data = {
                labels: [],
                datasets: [ { 
                    label: this.title,
                    data: [],
                    backgroundColor: this.colors,
                    hoverBackgroundColor: this.colors
                }],
            };

            this.data.forEach( e => {
                data.labels.push( this.getLabel( e[ this.label ] ) );
                data.datasets[0].data.push( e[ this.value ] );

                if( this.colors.length == 0 ){
                    let color = this.colorGeneratorHSL();
                    color.l = 30;
                    data.datasets[0].backgroundColor.push( color.toString() );

                    color.l = 90;
                    data.datasets[0].hoverBackgroundColor.push( color.toString() );
                }
            });

            return data;
        },

        chartOptions(){
            if( this.options ) return this.options;

            return {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                },
            };
        },

        media(){
            if( !this.data || this.data.length == 0 ) return 0;

            let total = this.data.reduce( ( total , e ) => total += e.total , 0 );

            return total / this.data.length;
        },

        dataWithPercentage(){
            if( !this.data || this.data.length == 0 ) return [];

            let total = this.data.reduce( ( total , e ) => total += e.total , 0 );

            return this.data.map( e => {
                return {
                    percentage: e.total / total * 100 ,
                ...e
            }});
        },
    },

    methods: {
        getLabel( value ){
            let str = Array.isArray( this.labels ) 
                ? this.labels.find( e => value == e.value )
                : { 'label' : value };

            return str == null || str[ 'label' ] == null
                ? "Não Especificado" 
                : str[ 'label' ];
        },

        colorGeneratorHEX(){
            return "#" + Math.floor( Math.random() * 16777215 ).toString( 16 );
        },

        //from: https://stackoverflow.com/questions/46432335/hex-to-hsl-convert-javascript
        colorGeneratorHSL() {
            let hex = null;
            let result = null;

            do
            {
                hex    = this.colorGeneratorHEX();
                result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec( hex );
            }
            while( result == null );

            var r = parseInt(result[1], 16);
            var g = parseInt(result[2], 16);
            var b = parseInt(result[3], 16);

            r /= 255, g /= 255, b /= 255;
            var max = Math.max(r, g, b), min = Math.min(r, g, b);
            var h, s, l = (max + min) / 2;

            if(max == min){
                h = s = 0; // achromatic
            } else {
                var d = max - min;
                s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                switch(max) {
                    case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                    case g: h = (b - r) / d + 2; break;
                    case b: h = (r - g) / d + 4; break;
                }
                h /= 6;
            }

            s = s * 100;
            s = Math.round( s );
            l = l * 100;
            l = Math.round( l );
            h = Math.round( 360 * h );

            return {
                h: h,
                s: s,
                l: l,
                toString(){ return 'hsl(' + this.h + ', ' + this.s + '%, ' + this.l + '%)'; }
            }
        }
    },

    mounted(){
        
    },

}
</script>
