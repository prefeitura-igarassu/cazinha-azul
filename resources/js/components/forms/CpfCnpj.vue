<template>
    <div :class="[ 'field' , col ]">
        <label :class="labelClass" :for="id">
            {{ campo }}{{ required ? '*' : '' }}
        </label>
        
        <slot>
            <IMaskComponent 
                :id="id" 
                :modelValue="valor" 
                :unmask="true"
                :mask="mask"
                radix="."
                :class="[ 'p-inputtext p-component block mt-1 w-full' , inputClass , error ? 'p-invalid' : '' ]" 
                @blur="$emit('blur', valor)" 
                @focus="$emit('focus', valor)"
                @update:modelValue="updatedModelValue"
            />
        </slot>

        <small v-if="error" class="p-error">{{ error.join( "," ) }}</small>
    </div>
</template>

<script>
import { IMaskComponent } from 'vue-imask';

export default {
    components: { IMaskComponent } ,

    props: {
        'col'   : { type: String , default: 'col'  } ,
        'type'  : { type: String , default: 'text' } ,
        'id'    : { type: String , required: true } , 
        'cpf'   : { type: Boolean , default: true } , 
        'cnpj'  : { type: Boolean , default: true } , 
        'valor' : {} ,
        'error' : { default: '' } ,
        'inputClass' : {} ,
        'labelClass' : {} ,
        'required' : { type: Boolean , default: false  } ,
    } ,

    computed: {
        campo(){
            if( this.cpf && this.cnpj ) return "CPF/CNPJ Nº";
            else if( this.cpf  ) return "CPF Nº";
            else if( this.cnpj ) return "CNPJ Nº";
            else return "";
        },

        mask(){
            let mask = [];

            if( this.cpf  ) mask.push({ mask: "000.000.000-00" });
            if( this.cnpj ) mask.push({ mask: "00.000.000/0000-00" });

            return mask;
        }
    },

    methods: {
        updatedModelValue( newValue ){
            this.$emit( "update:valor" , newValue );
        },
    }
}
</script>