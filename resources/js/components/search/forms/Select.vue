<template>
    <Field :col="col" :id="id" :title="title" :error="error" :description="description" 
           :labelClass="labelClass" :required="required" :show="show" @update:show="value => $emit( 'update:show' , value )"
    >
        <Select 
            :id="id" 
            :modelValue="modelValue" 
            :options="optionsInternal"
            :filter="filter"
            optionLabel="label"
            optionValue="value"
            :class="[ 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
            @update:modelValue="updatedModelValue"
        />
    </Field>
</template>

<script>
import Field from "./Field.vue";
import Select from 'primevue/select';

export default {
    components: { Field, Select },

    props: {
        'options' : {} ,
        
        'col'   : { type: String , default: 'col' },
        'id'    : { type: String , required: true },
        'title' : { type: String , required: true },
        'modelValue' : {},
        'inputClass' : {},
        'labelClass' : {},
        'description': {},
        'error'    : {},
        'required' : { type: Boolean , default: false },
        'show'     : { type: Boolean , default: false },
    } ,

    data(){
        return {
            optionsInternal: [],
        };
    },

    watch: {
        options( value ){
            this.createOptionsInternal( value );
        },
    },

    computed: {
        filter(){
            //return this.options.length > 5;
            return true;
        },
    },

    methods: {
        updatedModelValue( value ){
            this.$emit( "update:modelValue" , value );
            this.$emit( "changed" , value );
        },

        createOptionsInternal( obj ){
            if( Array.isArray( obj ) ){
                this.optionsInternal = obj;
            }
            else if( typeof obj === 'undefined' || obj === null ){
                this.optionsInternal = [];
            }
            else if( typeof obj === 'function' ){
                this.createOptionsInternal( obj() );
            }
            else if( typeof obj === 'object' ){
                Promise.resolve( obj ).then( value => {
                    this.createOptionsInternal( value );
                });
            }
            else{
                this.optionsInternal = [];
            }
        }
    },

    mounted(){
        this.createOptionsInternal( this.options );
    }
}
</script>