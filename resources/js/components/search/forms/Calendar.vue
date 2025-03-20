<template>
    <Field :col="col" :id="id" :title="title" :error="error" :description="description" 
           :labelClass="labelClass" :required="required" :show="show" @update:show="value => $emit( 'update:show' , value )"
    >
    <DatePicker 
        :id="id" 
        v-model="value" 
        :showTime="showTime" 
        :showSeconds="showSeconds"
        :showButtonBar="true"
        :selectionMode="selectionMode"
        :class="[ 'mt-1' , 'w-full' , inputClass , error ? 'p-invalid' : '' ]" 
    />
    </Field>
</template>

<script>
import Field from "./Field.vue";
import DatePicker from 'primevue/datepicker';
import dayjs from 'dayjs';

export default {
    components: { Field, DatePicker },

    props: {
        'showTime'    : { type: Boolean , default: false }, 
        'showSeconds' : { type: Boolean , default: false },

        'col'   : { type: String , default: 'col' },
        'id'    : { type: String , required: true },
        'title' : { type: String , required: true },
        'modelValue' : {},
        'inputClass' : {},
        'labelClass' : {},
        'description': {},
        'error'      : {},
        'required'   : { type: Boolean , default: false },
        'show'       : { type: Boolean , default: false },
        'type'       : { defaut: 'date' },
    },

    data(){
        return {
            value: null,
            selectionMode: 'range', //single, multiple, range
            modes: ['single', 'multiple', 'range'],
        }
    },

    watch: {
        modelValue( value ){
            //this.setValue( value );
        },

        value( value ){
            this.$emit( "changed" , value );
            
            if( !value ){
                this.$emit( 'update:modelValue' , null );
                return ;
            }

            if( Array.isArray( value ) ){
                this.$emit( 'update:modelValue' , { 
                    ge: this.format( value[0] , true  ), 
                    le: this.format( value[1] , false )
                });
            }
            else{
                this.$emit( 'update:modelValue' , this.format( value ) );
            }
        },
    } ,

    methods:{
        isDate( date ){
            return date && typeof date.getMonth === 'function';
        },

        format( value , isFirst ){
            let format = 'YYYY-MM-DD';
            
            let result = value ? dayjs( value ).format( format ) : null;

            if( !this.showTime && this.type == 'datetime' )
            {
                if( isFirst ) result += " 00:00:00";
                else          result += " 23:59:59";
            }

            return result;
        },

        decode( str ){
            let date = dayjs( str , this.showTime ? 'YYYY-MM-DD HH:mm:ss' : 'YYYY-MM-DD' );
            return date.isValid() ? date.toDate() : null;
        },

        setValue( newValue ){
            if( newValue == null ){
                this.value = null;
            }
            else if( newValue.hasOwnProperty('ge') || newValue.hasOwnProperty('le') ){
                this.value = [
                    this.decode( newValue.ge ) ,
                    this.decode( newValue.le ) ,
                ];
            }
            else if( this.isDate( newValue ) ){
                this.value = newValue;
            }
            else{
                let date = this.decode( newValue );
                
                this.value = date == null 
                    ? new Date()
                    : date;
            }
        }
    } ,

    mounted(){
        this.setValue( this.modelValue );
    }
}
</script>