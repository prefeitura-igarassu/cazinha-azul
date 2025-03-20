<template>
  <div class="flex h-screen bg-gray-200 font-roboto">
    <div class="flex" style="background: #111827;">
      <div :class="[ 'fixed inset-0 z-20 transition-opacity bg-black opacity-50' , ( menuMostrar ? '' : 'hidden' )]" @click="menuMostrar = false"></div>
      <div :class="[ 'ease-in fixed inset-y-0 left-0 z-30 w-70 overflow-y-auto transition duration-300 transform bg-gray-900 lg:translate-x-0 lg:static lg:inset-0' , ( menuMostrar ? '' : '-translate-x-full hidden lg:block' ) ]" style="background: #111827 !important;">
        
        <div class="flex items-center justify-center mt-8">
          <div class="flex items-center">
            <span class="mx-2 text-2xl font-semibold text-white">Casinha Azul</span>
          </div>
        </div>

        <nav class="mt-10">
           <template v-for="(item,index) in items" :key="index">
            <div v-if="isVisible( item )"
              @click="setMenu( $event , item )" 
              :class="[ 'cursor-pointer flex items-center px-6 py-2 mt-4 duration-200 border-l-4' , (isSelected( item ) ? 'nav-active' : 'border-gray-900 text-gray-500 hover:nav-hover' ) ]">
              <span :class="item.icon"></span>
              <span class="mx-4">{{ item.label }}</span>
            </div>
           </template>

           <div class="flex justify-around mt-5">
              <template v-for="(item,index) in menuUsuario" :key="index">
                <div @click="setMenu( $event , item )" class="cursor-pointer duration-200 text-gray-500 m-2" :title="item.label">
                  <span :class="item.icon"></span>
                </div>
              </template>
           </div>
        </nav>
      </div>
    </div>

    <div class="flex-1 flex flex-col">
      <main class="flex-1 overflow-y-auto p-4" style="background-color: #f2f2f2 !important;">
        <button class="text-gray-500 focus:outline-none lg:hidden" @click="menuMostrar = true">
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        </button>

        <router-view></router-view>
      </main>
    </div>
  </div>

  <ConfirmDialog></ConfirmDialog>
  <TrocarSenha v-model:visible="senhaVisible"></TrocarSenha>
  <DadosPessoais v-model:visible="dadosVisible"></DadosPessoais>
  <Toast />
</template>

<script>
import TrocarSenha from "@/pages/minha_conta/TrocarSenhaDialog.vue";
import DadosPessoais from "@/pages/minha_conta/DadosPessoaisDialog.vue";
import service from "./services/Service.js";
import InputText from "primevue/inputtext";

import ConfirmDialog from "primevue/confirmdialog";
import Menubar from "primevue/menubar";
import Avatar from "primevue/avatar";
import Toast from "primevue/toast";
import Button from "primevue/button";

import InputGroup from "primevue/inputgroup";
import InputGroupAddon from "primevue/inputgroupaddon";

import menu from "./menu.js";

export default {
  components: {
    TrocarSenha,
    DadosPessoais,
    Menubar,
    Avatar,
    Toast,
    InputText,
    InputGroup, InputGroupAddon,
    ConfirmDialog,
    Button
  },

  data() {
    return {
      items: menu,

      menuUsuario: [
        { label: "Alterar dados", icon: "pi pi-fw pi-book"      , command: () => (this.dadosVisible = true)     },
        { label: "Trocar Senha" , icon: "pi pi-fw pi-key"       , command: () => (this.senhaVisible = true)     },
        { label: "Sair"         , icon: "pi pi-fw pi-power-off" , command: () => this.logout() },
      ],

      dadosVisible: false,
      senhaVisible: false,
      protocoloId: null,
      menuMostrar: false,
    };
  },

  methods: {
    isSelected( item ){
      if( !item.path ) return false;
      if( this.$route.path.startsWith( item.path ) && item.path != "/"  ) return true;
      return this.$route.path == item.path;
    },

    setMenu( event , item ){
      event.preventDefault();
      
      if( item.to ) this.$router.push( item.to );
      else          item.command();
    },

    logout(){
      service.minhaConta.logout().then( r => this.$router.push({ name: "login" }) );
    },

    isVisible( item ){
      if( !item.groups ) return true;
      return item.groups.includes( service.usuarioLogado?.grupo_id );
    }
  },
};
</script>

<style>
@media print {
  /* https://stackoverflow.com/questions/585254/how-to-remove-the-url-from-the-printing-page */
  @page {
    size: auto; /* auto is the initial value */
    margin: 0; /* this affects the margin in the printer settings */
  }

  .no-print,
  .no-print * {
    display: none !important;
  }
}

.nav-active {
  background: rgba(75, 85, 99, 0.25) !important;
  border-left-color: rgb(243, 244, 246) !important;
  color: white !important;
}

.nav-hover {
  background: rgba(75, 85, 99, 0.25) !important;
  color: white !important;
}

.border-gray-900 { border-color: #111827 !important; }
.text-gray-500   { color: #515867 !important; }

.bg-gray-200 {
  background-color: #f2f2f2; /* oklch(0.928 0.006 264.531) */
}

</style>