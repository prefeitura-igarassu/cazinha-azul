<template>
    <div :class="col">
        <div class="map" :id="id"></div>
    </div>
</template>

<script>
import { makeid } from "../forms/Map.vue";

export default {
    props: [ 'col' , 'pontos' ],

    data() {
        return {
            latitude: 0, 
            longitude: 0,
            map: null,
            id: "dashboardMap",
            markers: [],
        };
    },


    watch: {
        pontos( value ){
            this.adicionarMarkers( value );
        },
    },

    methods: {
        initMap(){
            // https://www.cidade-brasil.com.br/municipio-igarassu.html#:~:text=Vizinho%20dos%20munic%C3%ADpios%20de%20Itapissuma,%C2%B0%2054'%2023''%20Oeste.
            // https://leafletjs.com/examples/quick-start/
            this.map = L.map( this.id );
            
            this.map.setView([ 
                this.latitude , 
                this.longitude 
            ] , 13 );

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo( this.map );

            this.adicionarMarkers( this.pontos );
        },

        adicionarMarkers( pontos ){
            //remove todos as marcações anteriores
            this.markers.forEach( marker => marker.remove() );
            
            //adiciona os novos pontos
            this.markers = pontos.map( ponto => {
                let marker = L.marker(
                    [ ponto.latitude , ponto.longitude ] , 
                    this.getConfig( ponto ) 
                ).addTo( this.map );

                if( ponto.title ){
                    marker.bindPopup( ponto.title );
                    marker.openPopup();
                }

                return marker;
            });
        },

        getConfig( ponto ){
            return ponto.icon ? L.divIcon({ className: ponto.icon }) : null;
        },
    },

    mounted(){
        // colocar no centro de Igarassu
        if( !this.longitude ) this.longitude = -34.9064;
        if( !this.latitude  ) this.latitude  = -7.83437;

        this.id = makeid( 10 );

        setTimeout( () => {
            this.initMap();
        }, 10 );
    }
}

</script>

<style scoped>

.map { 
    height: 400px; 
}

</style>