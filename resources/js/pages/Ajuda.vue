<template>

    <div>
        <div class="w-full p-7 text-center bg-white text-6xl">
            Central de Ajuda
        </div>

        <div v-if="ajuda" class="grid m-7">
            <div class="surface-card p-4 shadow-2 border-round col-8">
                <div class="text-3xl font-medium text-900 mb-3">{{ ajuda.titulo }}</div>
                <div class="font-medium text-500 mb-3" v-html="ajuda.descricao"></div>
            </div>

            <div class="col-3 ml-4">
                <template v-for="categoria in mesmaCategoria" :key="categoria.key">
                    <h4>{{ categoria.label }}</h4>

                    <div v-for="link in categoria.children" :key="link.key" class="my-2 cursor-pointer">
                        <a @click="setAjuda( link.url)">{{ link.label }}</a>
                    </div>
                </template>
            </div>
        </div>

        <div class="m-7">
            <h3>Pesquisar pelos tópicos</h3>
            <InputText class="w-full mb-4" />

            <div class="grid pt-4">
                <div v-for="categoria in categorias" :key="categoria.key" class="col-4">
                    <h5>{{ categoria.label }}</h5>

                    <div v-for="link in categoria.children" :key="link.key" class="my-2 cursor-pointer">
                        <a @click="setAjuda( link.url)">{{ link.label }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-7">
            <h2 class="text-center">Você ainda continua com dúvida?</h2>

             <div class="flex grap">
                <div class="flex m-2 bg-gray">Sobre o sistema, entre em contato com o DAGAI!</div>
                <div class="flex m-2 bg-gray">Sobre avaliação, entre em contato com o FERC!</div>
             </div>
        </div>
    </div>
</template>
    
<script>
import InputText from "primevue/inputtext";
import service from "@/services/Service";

export default {
    components: { InputText },
    
    data() {
        return {
            categorias: [],
            expandedKeys: {},

            ajuda: null,
            loading: false,
        };
    },

    computed: {
        mesmaCategoria(){
            return this.ajuda 
                ? this.categorias.filter( categoria => categoria.label == this.ajuda.categoria )
                : null;
        },
    },

    methods: {
        init(){
            this.loading = true;

            service.ajudas.getTitulos().then( r =>{
                this.categorias = this.separarCategorias( r );
                this.loading = false;
            });
            
            const params = this.getQueryParams( window.location.toString() );
            if( params.url ) this.setAjuda( params.url );
        },

        separarCategorias( titulos ){
            let categorias = {};

            titulos.forEach( titulo => {
                if( !categorias[ titulo.categoria ] ){
                    categorias[ titulo.categoria ] = {
                        key: this.getRandomKey(),
                        label: titulo.categoria,
                        children: [],
                    };
                }

                categorias[ titulo.categoria ].children.push({
                    key: titulo.id,
                    label: titulo.titulo,
                    url: titulo.url,
                    type: 'url',
                });

                this.expandedKeys[ categorias[ titulo.categoria ].key ] = true;
                this.expandedKeys[ titulo.id ] = true;
            });

            return Object.keys( categorias ).map( key => categorias[ key ] );
        },

        getRandomKey(){
            return Math.floor(Math.random() * 1000) + 100000;
        },

        setAjuda( url ){
            service.ajudas.getAjuda( url ).then( r => {
                this.ajuda = r;

                window.scrollTo( 0 , 0 );
                const params = this.getQueryParams( window.location.toString() );

                if( !params.url || params.url != url )
                    window.history.pushState( { url } , r.titulo, `?url=${url}` );
            });
        },

        getQueryParams( url ) {
            const paramArr = url.slice(url.indexOf('?') + 1).split('&');
            const params = {};
            
            paramArr.map( param => {
                const [key, val] = param.split('=');
                params[key] = decodeURIComponent(val);
            });

            return params;
        }
    },

    mounted(){
        this.init();
    }
}
</script>