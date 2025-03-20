<template>
    <div :class="[ 'field' , col ]">
        <label :class="labelClass" :for="id">
            {{ campo }}{{ required ? '*' : '' }}
        </label>
        
        <div class="p-inputgroup">
            <AutoComplete 
                :id="id" 
                :multiple="multiple" 
                :suggestions="suggestions" 
                :optionLabel="label" 
                :disabled="readonly"
                :class="['block' , 'mt-1' , 'w-full' , inputClass , (error ? 'p-invalid' : '') ]" 
                :inputClass="['block' , 'w-full' , inputClass , (error ? 'p-invalid' : '') ].join( ' ' )" 
                :modelValue="valor" 
                :loading="loading"
                searchMessage="Pesquisando..."
                @complete="search( $event )" 
                @blur="$emit('blur', $event)" 
                @focus="$emit('focus', $event)"
                @change="$emit('change', $event)"
                @item-select="$emit('selected', $event.value)"
                @update:modelValue="updatedModelValue"
            >
                <template #item="slotProps">
                    <div class="flex items-center">
                        <img v-if="getImage" :src="getImage( slotProps.index , slotProps.item )" width="18" />
                        
                        <div class="ml-2">
                            <div :class="labelClass">{{ slotProps.item[ label ] }}</div>
                            <div class="flex flex-wrap block text-xs">
                                <div v-for="(col,index) in columnsSelectOrdered" :key="index" class="mr-4 flex">
                                    <div class="mr-1">{{ col.title }}:</div>

                                    <div v-if="col.html" v-html="col.html( slotProps.item )"></div>
                                    <div v-else-if="getType( col ).html" v-html="getType( col ).html( slotProps.item )"></div>
                                    <div v-else :class="getClass( col , slotProps.item )">
                                        {{ format( col , slotProps.item , getValue( col , slotProps.item ) ) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <template #footer>
                    <div class="m-2 text-right text-xs">Mostrando {{ suggestions.length }} de {{ total }}</div>
                </template>

            </AutoComplete>

            <Button v-if="adicionar" icon="pi pi-plus" class="mt-1" @click="$emit( 'adicionou' , valor )" />
        </div>

        <small v-if="error" class="p-error">{{ error.join( "," ) }}</small>
    </div>
</template>

<script>
import Button from "primevue/button";
import AutoComplete from "primevue/autocomplete";
import { getType, getClass, getValue, format, getColumnsSelected } from '../table/ColumnFunctions';

export default {
    components: { Button, AutoComplete },

    props: {
        'id'    : { type: String , required: true  } , 
        'col'   : { type: String , default: 'col'  } ,
        'campo' : { type: String , required: true  } ,
        'valor' : {} ,
        'error' : { default: '' } ,
        'inputClass' : {} ,
        'labelClass' : {} ,
        'required' : { type: Boolean , default: false },
        'readonly' : { type: Boolean , default: false },

        'adicionar' : { type: Boolean , default: false },
        'label' : { type: String , default: "nome" },
        'value' : { type: String , default: "id"   },
        'columns' : { type: Array , default: []    },
        'columnSelected': { type: Array , default: [] },
        'getImage': { type: Function , default: null  },
        'multiple': { type: Boolean  , default: false },
        'service' : { type: Object   , required: true },
        'with'    : {},
        'filter'  : { type: Object   , required: false , default: {} },
    },

    data(){
        return {
            loading: false,
            suggestions: [],
            total: 0,
        };
    },

    computed: {
        columnsSelectOrdered(){
            return this.columnSelected 
                ? getColumnsSelected( this.columns , this.columnSelected )
                : this.columns;
        },
    },

    methods: {
        search( event ){
            let params = {
                global: event.query,
                ... this.filter,
                perPage: 5,
            };

            if( this.with ) params.with = this.with;

            this.loading = true;
            this.total = 0;

            this.service.search( params ).then( r => {
                this.suggestions = r.data;
                this.total = r.total;

                this.loading = false;
            });
        },

        updatedModelValue( newValue ){
            this.$emit( "update:valor" , newValue );
        },

        getType, getClass, getValue, format,
    },
}
</script>

<style>

.p-autocomplete-items .p-focus {
    color: #495057 !important;
    background: #dee2e6 !important;
}

</style>