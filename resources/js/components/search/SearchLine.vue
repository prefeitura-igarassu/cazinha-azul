<template>

<div class="overflow-x-auto flex flex-row">
    <component
        v-for="field of fields" 
        :is="field.component"
        :key="field.field"
        :type="field.type"

        v-bind="field.props"
        v-model:modelValue="field.value"
        v-model:show="field.show"
        v-model:options="field.options"

        @changed="pesquisar"

        :col="'inline-block mr-2 ' + field.class"
        :style="field.style"
    />
</div>
    
</template>
    
<script>
import { createFields , load , save } from "./createFields.js";
    
export default {
    props: [ "id" , "search" ],
    emits: [ "pesquisar" , "loaded" ],

    data() {
        return {
            fields: [],
            timer : null,
        };
    },

    methods: {
        init(){
            this.fields = createFields( this.search );
            setTimeout( () => this.loading() , 100 );
        },

        loading(){
            if( load( this.id , this.fields ) )
                this.pesquisar();

            setTimeout( () => this.$emit( "loaded" ) , 150 );
        },

        pesquisar(){
            clearTimeout( this.timer );

            this.timer = setTimeout(() => {
                let params = {};
            
                Object.keys( this.fields ).forEach( key => {
                    let field = this.fields[ key ];
                    
                    if( field.value || field.value == 0 ) 
                        params[ field.field ] = field.value;
                });

                save( this.id , params );
                this.$emit( "pesquisar" , params );
            }, 100 );
        },
    },

    mounted(){
        this.init();
    }
}
</script>