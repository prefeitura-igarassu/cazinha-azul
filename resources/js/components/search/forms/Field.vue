<template>
    <div :class="[ 'field' , col ]">
        <label :class="labelClass" :for="id" @click="onShow">
            <i :class="['pi' , this.show ? 'pi-angle-down' : 'pi-angle-right' ]"></i>
            <span class="ml-2">{{ title }}{{ required ? '*' : '' }}</span>
        </label>
        
        <div v-if="show">
            <slot>
                <InputText 
                    :id="id" 
                    :type="type" 
                    v-model="value" 
                    :readonly="readonly"
                    :class="['block' , 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
                />
            </slot>
        </div>
        
        <small class="text-surface-500 dark:text-surface-300">{{ description }}</small>
        <small class="p-error">{{ error }}</small>
    </div>
</template>

<script>
import InputText from 'primevue/inputtext';

export default {
    components: { InputText },
    
    props: {
        'col'   : { type: String , default: 'col'  },
        'type'  : { type: String , default: 'text' },
        'id'    : { type: String , required: true  },
        'title' : { type: String , required: true  },
        'modelValue' : {},
        'inputClass' : {},
        'labelClass' : {},
        'description': {},
        'error'    : {},
        'readonly' : { type: Boolean , default: false },
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
            this.$emit( "update:modelValue" , value ? { '**' : value } : null );
            this.$emit( "changed" , value );
        },

        modelValue( value ){
            this.value = value ? value['**'] : null;
        },
    },

    methods:{
        onShow(){
            this.$emit( "update:show" , !this.show );
        },
    },

    mounted(){
        this.value = this.modelValue ? this.modelValue['**'] : null;
    }
}
</script>