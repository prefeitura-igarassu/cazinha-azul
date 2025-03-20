<template>
    <div class="border-solid border border-black" v-bind="$attrs">
        <div v-if="title" :class="['block', 'w-full', 'font-bold' , 'text-sm' , titleClass ]" :style="titleStyle">{{ title }}</div>
        <div v-if="value" :class="['block', 'w-full', align , valueClass ]"                   :style="valueStyle">{{ value }}</div>
    </div>
</template>

<script>
export default {
    props: {
        title: { type: String , default: null } ,
        titleClass: { type: String , default: null } ,
        titleStyle: { type: String , default: null } ,

        value: { type: String , default: null } ,
        valueClass: { type: String , default: null } ,
        valueStyle: { type: String , default: null } ,

        align: { type: String , default: 'text-left' } ,
    } ,
}
</script>