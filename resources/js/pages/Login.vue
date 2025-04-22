<template>

	<div v-if="visible" style="background: #e5e7eb; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999;">
		<div class="flex items-center justify-center h-screen px-6 bg-gray-200">
			<div class="w-full max-w-sm p-6 bg-white rounded-md shadow-md">
				<div class="flex items-center justify-center">
					<span class="text-2xl font-semibold text-gray-700">Casinha Azul</span>
				</div>
				
				<form @submit.prevent="login" class="login-content w-full">
					<p class="my-4">Seja bem-vindo! Por favor, entre com as suas credencias.</p>
					
					<InputGroup class="w-full">
						<InputText class="p-inputtext-md" placeholder="E-mail" v-model="email"/>
	
						<InputGroupAddon>
							<i class="pi pi-user m-3"></i>
						</InputGroupAddon>
					</InputGroup>
					
					<InputGroup class="mt-2 w-full">
						<Password class="p-inputtext-md" placeholder="Senha" v-model="password" toggleMask :feedback="false" />
	
						<InputGroupAddon>
							<i class="pi pi-lock m-3"></i>
						</InputGroupAddon>
					</InputGroup>
					
					<div class="mt-2">
						<Button label="Esqueci a Senha" class="p-button-lg p-button-text w-full" @click="esqueci" />
					</div>
	
					<div class="mt-2">
						<Button label="Entrar" class="p-button-lg w-full" type="submit" @click.prevent="login" />
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<Toast />
</template>
<script>
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import InputGroup from "primevue/inputgroup";
import InputGroupAddon from "primevue/inputgroupaddon";

import Toast from "primevue/toast";

import service from "@/services/Service.js";

export default {
	components: { Dialog, Button, InputText, InputGroup, InputGroupAddon, Toast, Password },

	data(){
		return {
			visible: true,
			email: null,
			password: null,
		};
	},

	methods: {
		login(){
			service.minhaConta.login( this.email , this.password )
			.then( r => service.minhaConta.getAtual() )
			.then( r => {
				if( r.ativo )
				{
					service.usuarioLogado = r;
					this.$router.push( this.$route.params.redirect || "/" );
				
					this.$toast.add({
						severity:'success', 
						summary: 'Logado!', 
						detail: "Seja bem-vindo!" , 
						life: 3000
					});
				}
				else
				{
					alert( "A conta está desativada!" );

					service.usuarioLogado = null;
					service.minhaConta.logout()
						.then ( () => this.$router.push( '/login' ) );
				}
			})
			.catch( err =>{
				this.$toast.add({
					severity:'error', 
					summary: 'Error no Login', 
					detail: err.data?.message ?? 'Erro desconhecido' , 
					life: 3000
				});
			});
		},

		esqueci(){
			if( !this.email ){
				this.$toast.add({
					severity:'error', 
					summary: 'Ops!', 
					detail: 'Para recuperar a senha, é necessário preencher o e-mail.' , 
					life: 3000
				});
			}
			else{
				this.$toast.add({
					severity:'info', 
					summary: 'Enviando e-mail...', 
					detail: "Um e-mail está sendo enviando!" , 
					life: 3000
				});

				service.minhaConta.forgotPassword( this.email ).then( r => {
					this.$toast.add({
						severity:'success', 
						summary: 'Enviado!', 
						detail: "O e-mail foi enviado para você para resetar a senha!" , 
						life: 3000
					});
				}).catch( err => {
					this.$toast.add({
						severity:'error', 
						summary: 'Ops!', 
						detail: err.message , 
						life: 3000
					});
				});
			}
		},

		verificarSeLogado(){
			if( service.usuarioLogado?.id ){
				this.$router.push( this.$route.params.redirect || "/" );
			}
		}
	},

	mounted(){
		setTimeout( this.verificarSeLogado , 100 );
	}
}
</script>

<style scoped>

</style>