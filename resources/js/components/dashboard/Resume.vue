<template>
    <div :class="col">
        <div class="w-full overview-box" :style="{ 'background': color }">
            <div class="flex">
                <span class="overview-title mr-4">{{ title }}</span>
                <i v-if="icon" :class="[ 'overview-icon', 'pi' , icon ]"></i>
            </div>
            
            <div class="overview-numbers">{{ getNumber }}</div>
            <div class="overview-subinfo">{{ subinfo }}</div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        col    : { default: 'col-12 lg:col-6 xl:col-3' },
        title  : {},
        number : {},
        subinfo: {},
        color  : {},
        icon   : {},
        service: { type: Object   , default: null },
        then   : { type: Function , default: ( r ) => r },
        params : {},
        format : {},
    },

    data(){
        return {
            numberInternal: 0,
        };
    },

    watch: {
        params( newValue ){
            this.pesquisar();
        }
    },

    computed: {
        getNumber(){
            let number = this.number ?? this.numberInternal;

            return parseFloat( number ) == number 
                ? new Intl.NumberFormat( 'pt-BR' , this.format ).format( number )
                : number;
        },
    },

    methods: {
        pesquisar(){
            if( !this.service ) return ;
            
            this.service.getDashboardResume( this.params )
                .then( r => this.numberInternal = this.then( r ) )
                .then( r => this.numberInternal = this.numberInternal ? this.numberInternal : 0 );
        },
    },

    mounted(){
        if( this.service && this.params ){
            this.pesquisar();
        }
    }
}
</script>

<style scoped>


</style>