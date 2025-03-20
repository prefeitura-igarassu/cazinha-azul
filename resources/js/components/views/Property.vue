<template>

<div :class="col">
    <b class="block">{{ column.title }}</b>
    
    <div v-if="column.html"                 v-html="column.html( object , this )"                            :class="getClass( column , object )" @click="click( column , object )"></div>
    <div v-else-if="getType( column ).html" v-html="getType( column ).html( object )"                        :class="getClass( column , object )" @click="click( column , object )"></div>
    <div v-else                             v-html="format( column , object , getValue( column , object ) )" :class="getClass( column , object )" @click="click( column , object )"></div>
</div>

</template>

<script>
import { getType, getClass, getValue, format, getFooterClass, getFooterValue, click } from '@/components/table/ColumnFunctions';

export default {
    props: {
        col    : { default: 'col' }, 
        column : { required: true }, 
        object : { required: true }
    },

    data() {
        return {
            
        };
    },

    methods: {
        getType, getClass, getValue, format, getFooterClass, getFooterValue, click
    },
}
</script>