<template>
    <div :class="[ 'field' , col ]">
        <label :class="labelClass" :for="id">
            {{ campo }}{{ required ? '*' : '' }}
        </label>
        
        <slot>
            <InputText 
                :id="id" 
                :type="type" 
                :modelValue="valor" 
                :readonly="readonly"
                :class="['block' , 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
                @blur="$emit('blur')" 
                @focus="$emit('focus')"
                @update:modelValue="updatedModelValue"
                @keyup="validate"
                :min="min"
                :max="max"
            />
        </slot>
        
        <small v-if="descricao" class="block">{{ descricao }}</small>
        <small v-if="error"     class="p-error block">{{ error.join( "," ) }}</small>
    </div>
</template>

<script>
import InputText from 'primevue/inputtext';

export default {
    components: { InputText },

    props: {
        'col'   : { type: String , default: 'col'  } ,
        'type'  : { type: String , default: 'text' } ,
        'id'    : { type: String }, 
        'campo' : { type: String , required: true  } ,
        'valor' : {} ,
        'error' : { default: '' } ,
        'min'   : {},
        'max'   : {},
        'inputClass' : {} ,
        'labelClass' : {} ,
        'required' : { type: Boolean , default: false  } ,
        'readonly' : { type: Boolean , default: false  } ,
        'descricao': {},
        'regex'    : {},
    },

    emits: [ 'changed' , "focus" , "blur" , "update:valor" ],

    methods: {
        updatedModelValue( newValue ){
            this.$emit( "update:valor" , newValue );
            this.$emit( "changed" , newValue );
        },

        validate( event ){
            if( !this.regex ) return ;

            const newValue = event.originalTarget.value
                .match( new RegExp( this.regex , 'g' ) );

            console.log( 
                "regex" , this.regex , 
                "value" , event.originalTarget.value , 
                "newValue" , newValue , 
                "validate" , event 
            );

            if( newValue )
            {
                event.originalTarget.value = newValue.join( '' );
                this.updatedModelValue( newValue.join( '' ) );
            }
            else
            {
                event.originalTarget.value = "";
                this.updatedModelValue( "" );
            }
        }
    },
}
</script>