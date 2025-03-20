<template>
    <div :class="col">
        <div class="map" :id="id"></div>
    </div>
</template>

<script>

export default {
    props: [ 'latitude' , 'longitude' , 'col' , "title" , "icon" ],

    data() {
        return {
            map: null,
            id: "map",
            marker: null,
        };
    },

    watch: {
        latitude( value ){
            if( !this.marker ) return ;
            
            this.marker.setLatLng( {
                lat: value,
                lng: this.longitude,
            } );
        },

        longitude( value ){
            if( !this.marker ) return ;

            this.marker.setLatLng( {
                lat: this.latitude,
                lng: value,
            } );
        },
    },

    computed: {
        markerConfig(){
            return this.icon ? L.divIcon({ className: this.icon }) : null;
        }
    },

    methods: {
        initMap(){
            // https://www.cidade-brasil.com.br/municipio-igarassu.html#:~:text=Vizinho%20dos%20munic%C3%ADpios%20de%20Itapissuma,%C2%B0%2054'%2023''%20Oeste.
            // https://leafletjs.com/examples/quick-start/
            this.map = L.map( this.id ).setView([ 
                this.latitude , 
                this.longitude 
                ] , 13 );

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo( this.map );

            this.marker = L.marker(
                [ this.latitude , this.longitude ] , 
                this.markerConfig 
            ).addTo( this.map );

            if( this.title ){
                this.marker
                    .bindPopup( this.title )
                    .openPopup();
            }
            
            this.map.on('click', (e) => {
                this.marker.setLatLng( e.latlng );

                this.$emit( "update:longitude" , e.latlng.lng );
                this.$emit( "update:latitude"  , e.latlng.lat );
            });
        },
    },

    mounted(){
        // colocar no centro de Igarassu
        if( !this.longitude ) this.$emit( "update:longitude" , -34.9064 );
        if( !this.latitude  ) this.$emit( "update:latitude"  , -7.83437 );

        this.id = makeid( 10 );

        setTimeout( () => {
            this.initMap();
        }, 10 );
    }
}

function ddToDms( valor , isLatitude ) {
    valor = parseFloat( valor );
    
    // Call to getDms(lng) function for the coordinates of Longitude in DMS.
    // The result is stored in lngResult variable.
    return getDms( valor ) + " " + (isLatitude 
        ? ((valor >= 0)? 'N' : 'S')
        : ((valor >= 0)? 'L' : 'O'));
}

function getDms( val ) {
    var valDeg, valMin, valSec, result;

    val = Math.abs(val);

    valDeg = Math.floor(val);
    result = valDeg + "º";

    valMin = Math.floor((val - valDeg) * 60);
    result += valMin + "'";

    valSec = Math.round((val - valDeg - valMin / 60) * 3600 * 1000) / 1000;
    result += valSec + '"';

    return result;
}

function makeid( length ){
    if( !length ) length = 10;

    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;
    
    for( let i = 0 ; i < length ; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }

    return result;
}

export { makeid , ddToDms , getDms };

</script>

<style scoped>

.map { 
    height: 400px; 
}

</style>