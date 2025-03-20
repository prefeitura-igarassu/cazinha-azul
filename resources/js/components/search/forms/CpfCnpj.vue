<template>
    <Field :col="col" :id="id" :title="title" :error="error" :description="description" 
           :labelClass="labelClass" :required="required" :show="show" @update:show="value => $emit( 'update:show' , value )"
    >
        <IMaskComponent 
            :id="id" 
            v-model="value" 
            :unmask="true"
            :mask="mask"
            radix="."
            :class="[ 'p-inputtext p-component block mt-1 w-full' , inputClass , error ? 'p-invalid' : '' ]" 
            @blur="$emit('blur')" 
            @focus="$emit('focus')"
        />
    </Field>
</template>

<script>
import Field from "./Field.vue";
import { IMaskComponent } from 'vue-imask';

export default {
    components: { Field , IMaskComponent } ,

    props: {
        'cpf'   : { type: Boolean , default: true },
        'cnpj'  : { type: Boolean , default: true },
        
        'col'   : { type: String , default: 'col'  },
        'id'    : { type: String , required: true  },
        'title' : { type: String , default: 'CPF/CNPJ Nº' },
        'modelValue' : {},
        'inputClass' : {},
        'labelClass' : {},
        'description': {},
        'error'    : {},
        'required' : { type: Boolean , default: false },
        'show'     : { type: Boolean , default: false },
    },

    data(){
        return {
            value: null,
        };
    },

    watch: {
        value( value ){
            let number = value ? value.replace(/\D/g, "") : null; // apenas números
            this.$emit( "update:modelValue" , { '.*' : number } );
            this.$emit( "changed" , number );
        },

        modelValue( value ){
            this.value = value ? value['.*'] : null;
        },

        show( value ){
            this.$emit( 'update:show' , value );
        }
    },

    computed: {
        mask(){
            let mask = [];

            if( this.cpf  ) mask.push({ mask: "000.000.000-00" });
            if( this.cnpj ) mask.push({ mask: "00.000.000/0000-00" });

            return mask;
        }
    },

    methods: {
        getDefaultTitle(){
            if( this.cpf && this.cnpj ) return "CPF/CNPJ Nº";
            else if( this.cpf  ) return "CPF Nº";
            else if( this.cnpj ) return "CNPJ Nº";
            else return "";
        },
    },

    mounted(){
        this.value = this.modelValue ? this.modelValue['.*'] : null;
    }
}
</script>