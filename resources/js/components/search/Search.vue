<template>
    
<Splitter>
    <SplitterPanel :size="80">
        <div class="formgrid grid grid-cols-12 gap-0">
            <component
                v-for="field of selected_order" 
                :is="field.component"
                :key="field.field"
                :type="field.type"
                v-bind="field.props"
                v-model:modelValue="field.value"
                v-model:show="field.show"
                v-model:options="field.options"
            />
        </div>
    </SplitterPanel>

    <SplitterPanel :size="20">
        <div class="ml-2">
            <h5>Campos</h5>

            <div>
                <div v-for="(field,index) in fields" 
                    :key="index" 
                    :class="{ 'font-bold' : isSelected(field) , 'cursor-pointer' : true , 'm-3' : true }"
                    @click="setSelected( field )"
                >{{ field.props.title }}</div>
            </div>
        </div>
    </SplitterPanel>
</Splitter>

</template>

<script>
import Splitter from 'primevue/splitter';
import SplitterPanel from 'primevue/splitterpanel';

import Field from "./forms/Field.vue";
import createFields from "./createFields.js";

export default {
    components: { Field, Splitter, SplitterPanel },

    props: [ "columns" , "fields" , "errors" ],

    data() {
        return {
            selected: [],
        };
    },

    computed: {
        selected_order(){
            let f = [];
            
            Object.keys( this.fields ).forEach( key => {
                if( this.selected.includes( this.fields[ key ] ) )
                    f.push( this.fields[ key ] );
            });

            return f;
        },
    },

    watch: {
        fields( newValue , oldValue ){
            if( Object.keys( oldValue ).length > 0 ) return ;
            this.initByVisible();
        }
    },

    methods: {
        init(){
            this.create();

            Object.keys( this.fields ).forEach( key => {
                if( this.fields[ key ].value )
                    this.selected.push( this.fields[ key ] );
            });

            if( this.selected.length == 0 )
                this.initByVisible();
        },

        initByVisible(){
            this.columns.forEach( column => {
                if( column.visible )
                    this.selected.push( this.fields[ column.field ] );
            });
        },

        create(){
            if( Object.keys( this.fields ).length == 0 ){
                this.$emit( "update:fields" , createFields( this.columns ) );
            }
        },

        isSelected( field ){
            return this.selected.includes( field );
        },

        setSelected( field ){
            if( this.isSelected( field ) ){
                field.value = null;
                this.selected = this.selected.filter( s => s != field );
            }
            else 
                this.selected.push( field );
        },
    },

    mounted(){
        this.init();
    }
}
</script>

<style>

.p-splitter{
    border: none;
}

</style>