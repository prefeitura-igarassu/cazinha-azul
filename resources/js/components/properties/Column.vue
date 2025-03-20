<template>
    <div :class="[ col , 'p-3' ]">
        <div class="text-surface-500 dark:text-surface-300 font-medium mb-2">{{ title }}</div>
        <div class="text-surface-900 dark:text-surface-0">{{ field }}</div>
    </div>
</template>

<script>
//from: https://www.primefaces.org/primeblocks-vue/#/application/descriptionlist
export default {
    props: {
        "title" : { type: String , default: '' } , 
        "value" : { default : '' } , 
        "col"   : { type: String , default: 'col' } ,
    } ,
}
</script>