<template>
    <div :class="[ 'field' , col ]">
        <label :class="labelClass" :for="id">
            {{ campo }}{{ required ? '*' : '' }}
        </label>
        
        <slot>
            <DatePicker 
                :id="id" 
                :modelValue="value" 
                :showTime="showTime" 
                :showSeconds="showSeconds"
                :showButtonBar="true"
                :selectionMode="selectionMode"
                :class="[ 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
                @update:modelValue="updatedModelValue"
            />
        </slot>

        <small v-if="error" class="p-error">{{ error.join( "," ) }}</small>
    </div>
</template>

<script>
import DatePicker from 'primevue/datepicker';
import dayjs from 'dayjs';

export default {
    components: { DatePicker },

    props: {
        'col'   : { type: String , default: 'col'  } ,
        'id'    : { type: String , required: true  } , 
        'campo' : { type: String , default: ''     } ,
        'valor' : {} ,
        'error' : { default: '' } ,
        'inputClass' : {} ,
        'labelClass' : {} ,
        'showTime'     : { type: Boolean , default: true  }, 
        'showSeconds'  : { type: Boolean , default: true  },
        'required'     : { type: Boolean , default: false },
        'selectionMode': { type: String  , default: "single" },
    } ,

    data(){
        return {
            value: null ,
        }
    } ,

    watch: {
        valor( newValue ){
            this.setValue( newValue );
        },
    } ,

    methods:{
        isDate( date ){
            return date && typeof date.getMonth === 'function';
        },

        updatedModelValue( newValue ){
            this.$emit( 'update:valor' , newValue 
                ? dayjs( newValue ).format( 'YYYY-MM-DD HH:mm:ss' ) 
                : null 
            );
        },

        setValue( newValue ){
            if( newValue == null )
            {
                this.value = null;
            }
            else if( this.isDate( newValue ) )
            {
                this.value = newValue;
            }
            else
            {
                let date = dayjs( newValue , 'YYYY-MM-DD HH:mm:ss' );

                this.value = date.isValid() 
                    ? date.toDate() 
                    : new Date();
            }
        }
    } ,

    mounted(){
        this.setValue( this.valor );
    }
}
</script>