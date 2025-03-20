<template>
    <li class="flex items-center py-2 px-2 border-surface flex-wrap" v-bind="$attrs">
        <div :class="[ 'text-500 font-medium w-5'                      , titleClass ]" :style="titleStyle">{{ title }}</div>
        <div :class="[ 'text-900 md:order-none flex-order-1 font-bold' , valueClass ]" :style="valueStyle">{{ value }}</div>
        <!-- <div class="w-6/12 md:w-2/12 flex justify-end"></div> -->
    </li>
</template>

<script>
// from: https://www.primefaces.org/primeblocks-vue/#/application/descriptionlist
export default {
    props: [ 
        "title" , "titleClass" , "titleStyle" ,
        "value" , "valueClass" , "valueStyle"
    ] ,
}
</script>