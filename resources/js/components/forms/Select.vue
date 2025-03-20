<template>
    <div :class="[ 'field' , col ]">
        <label :class="[ 'w-full' , labelClass ]" :for="id">
            {{ campo }}{{ required ? '*' : '' }}
        </label>
        
        <MultiSelect v-if="multiple"
            :id="id" 
            :modelValue="valor" 
            :options="optionsInternal"
            :optionLabel="label"
            :optionValue="value"
            :filter="filter"
            :class="[ 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
            :disabled="disabled"
            @update:modelValue="updatedModelValue"
        >
            <template #option="slotProps">
                <div class="flex items-center">
                    <img v-if="getImage" :src="getImage( slotProps.index , slotProps.option )" width="18" />
                    
                    <div class="ml-2">
                        <div :class="labelClass">{{ slotProps.option[ label ] }}</div>
                        <div class="flex flex-wrap block text-xs">
                            <div v-for="(col,index) in columnsSelectOrdered" :key="index" class="mr-4 flex">
                                <div class="mr-1">{{ col.title }}:</div>

                                <div v-if="col.html" v-html="col.html( slotProps.option )"></div>
                                <div v-else-if="getType( col ).html" v-html="getType( col ).html( slotProps.option )"></div>
                                <div v-else :class="getClass( col , slotProps.option )">
                                    {{ format( col , slotProps.option , getValue( col , slotProps.option ) ) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </MultiSelect>

        <Select v-else
            :id="id" 
            :modelValue="valor" 
            :options="optionsInternal"
            :optionLabel="label"
            :optionValue="value"
            :filter="filter"
            :class="[ 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
            :disabled="disabled"
            @update:modelValue="updatedModelValue"
        >
            <template #option="slotProps">
                <div class="flex items-center">
                    <img v-if="getImage" :src="getImage( slotProps.index , slotProps.option )" width="18" />
                    
                    <div class="ml-2">
                        <div :class="labelClass">{{ slotProps.option[ label ] }}</div>
                        <div class="flex flex-wrap block text-xs">
                            <div v-for="(col,index) in columnsSelectOrdered" :key="index" class="mr-4 flex">
                                <div class="mr-1">{{ col.title }}:</div>

                                <div v-if="col.html" v-html="col.html( slotProps.option )"></div>
                                <div v-else-if="getType( col ).html" v-html="getType( col ).html( slotProps.option )"></div>
                                <div v-else :class="getClass( col , slotProps.option )">
                                    {{ format( col , slotProps.option , getValue( col , slotProps.option ) ) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </Select>
        
        <small v-if="error" class="p-error">{{ error.join( "," ) }}</small>
    </div>
</template>

<script>
import MultiSelect from "primevue/multiselect";
import Select from "primevue/select";

import { getType, getClass, getValue, format, getColumnsSelected } from '../table/ColumnFunctions';

export default {
    components: { MultiSelect, Select },
    emits: [ "changed" , "update:valor" ],

    props: {
        'col'   : { type: String , default: 'col'  } ,
        'id'    : { type: String , required: false } , 
        'campo' : { type: String , required: true  } ,
        'valor' : {} ,
        'error' : { default: '' } ,
        'options' : { type: [ Array, Promise ], default: [] } ,
        'inputClass' : {} ,
        'labelClass' : {} ,
        'required'   : { type: Boolean , default: false },
        'filter'     : { type: Boolean , default: true  },
        'disabled'   : { type: Boolean , default: false },

        'label' : { type: String , default: "label" },
        'value' : { type: String , default: "value" },
        'columns' : { type: Array , default: []     },
        'columnSelected': { type: Array , default: [] },
        'getImage': { type: Function , default: null  },
        'multiple': { type: Boolean  , default: false },
        'service'  : { type: Object },
        'with'     : {},
    },

    data(){
        return {
            optionsInternal: [],
        };
    },

    watch: {
        service( newValue ){
            if( newValue )
                setTimeout( () => this.search() , 100 );
        },

        options( newValue ){
            Promise.resolve( newValue ).then( value => {
                this.setOptionsInternal( value );
            });
        },
    },

    computed: {
        columnsSelectOrdered(){
            return this.columnSelected 
                ? getColumnsSelected( this.columns , this.columnSelected )
                : this.columns;
        },
    },

    methods: {
        search(){
            let params = {};
            if( this.with ) params.with = this.with;

            this.service.search( params ).then( r => {
                this.setOptionsInternal( r );
            });
        },

        updatedModelValue( newValue ){
            this.$emit( "update:valor" , newValue );
            this.$emit( "changed"      , newValue );
        },

        setOptionsInternal( r ){
            if( !r ) r = [];
            
            this.optionsInternal = r.map( r => {
                let t = {};
                t[ this.value ] = r[ this.value ];
                t[ this.label ] = r[ this.label ];

                if( !t[ this.label ] ) t[ this.label ] = "";

                return t;
            } );
        },

        getType, getClass, getValue, format,
    },

    mounted(){
        Promise.resolve( this.options ).then( value => {
            this.setOptionsInternal( value );
        });

        if( this.service ){
            setTimeout( () => this.search() , 100 );
        }
    }
}
</script>