<template>
	<div class="bg-white p-4 rounded-lg">
		<div class="flex justify-between content-center">
			<div v-if="title" class="flex">
				<h3 class="text-2xl font-medium">{{ title }}</h3>
			</div>

			<div>
				<slot name="menu"></slot>
			</div>
		</div>

		<Errors :errors="errors"></Errors>
		<slot></slot>
	</div>
</template>

<script>
import Errors from "./Errors.vue";

export default {
	components: { Errors },
	props: {
		"title" : { default: null },
		"errors": { default: null }
	},

	data() {
		return {
			
		}
	},
	
	created() {
		
	},

	mounted() {
		
	},

	methods: {
		
	}
}
</script>

<style scoped>

</style>