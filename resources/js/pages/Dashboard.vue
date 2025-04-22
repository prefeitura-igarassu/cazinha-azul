<template>
	<div v-if="!isLogged"></div>
	<TerapeutaDashboard v-else-if="isTerapeuta"></TerapeutaDashboard>
</template>

<script>
import Button from "primevue/button";

import service from "@/services/Service";
import TerapeutaDashboard from "./dashboards/TerapeutaDashboard.vue";

export default {
	components: { Button, TerapeutaDashboard },

	computed: {
		isAdmin(){
			return service.usuarioLogado?.grupo_id == 1;
		},

		isLogged(){
			return service.usuarioLogado?.id;
		},

		isTerapeuta(){
			return true; //service.usuarioLogado.grupo_id == 5;
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
