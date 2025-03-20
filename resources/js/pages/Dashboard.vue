<template>
	<div v-if="!isLogged"></div>
</template>

<script>
import Button from "primevue/button";

import service from "@/services/Service";

export default {
	components: { Button },

	computed: {
		isAdmin(){
			return service.usuarioLogado?.grupo_id == 1;
		},

		isLogged(){
			return service.usuarioLogado?.id;
		},
	},

	data() {
		return {
			
		}
	},

	methods: {
		
	},

	mounted(){
		this.usuario = service.usuarioLogado;
		if( !service.usuarioLogado ) return ;
	}
}
</script>
