import "./bootstrap";
import service from "./services/Service";

import "tailwindcss-primeui";
import "primeflex/primeflex.css";                             //utils
import "primeicons/primeicons.css";                           //icons
// import "primevue/resources/primevue.min.css";                 //core css
// import "primevue/resources/themes/tailwind-light/theme.css";  //theme

// ----------------------------------------------------------------------
// ----------------------------------------------------------------------
// ----------------------------------------------------------------------

import { createApp, h } from 'vue';

import PrimeVue from 'primevue/config';
import ConfirmationService from 'primevue/confirmationservice';
import ToastService from 'primevue/toastservice';

import dayjs from 'dayjs';
import 'dayjs/locale/pt-br';
import relativeTime from 'dayjs/plugin/relativeTime';
import updateLocale from 'dayjs/plugin/updateLocale';

dayjs.locale( 'pt-br' );
dayjs.extend( relativeTime );
dayjs.extend( updateLocale );

import router from './router';
import App    from './App.vue';
import Noar   from './Noar.js';

import Access   from '@/pages/Access.vue';
import Error    from '@/pages/Error.vue';
import Login    from '@/pages/Login.vue';
import NotFound from '@/pages/NotFound.vue';
import Register from '@/pages/Register.vue';
import PasswordReset from '@/pages/PasswordReset.vue';

const app = createApp({
    computed: {
        ViewComponent(){
            switch ( this.$route.path ) {
				case '/login'   : return Login;
				case '/error'   : return Error;
				case '/access'  : return Access;
				case '/notfound': return NotFound;
				case '/register': return Register;
				case '/password/reset': return PasswordReset;
				default: return this.isLogged() ? App : null;
			}
		}
    },

    render(){
        service.view = this;
		return h( this.ViewComponent );
	},

    methods: {
        isLogged() {
        return service.usuarioLogado?.id;
        },
    },

    beforeMount(){
        if( !this.isLogged() ){
            this.$router.push({ name: "login" });
        }
    }
});

app.use( router );

app.use( PrimeVue , { 
    ripple: true , 
    locale: {
        aria: '',
        startsWith: 'Começar com',
        contains: 'Contém',
        notContains: 'Não contém',
        endsWith: 'Rerminar com',
        equals: 'Igual à',
        notEquals: 'Diferente de',
        noFilter: 'Sem filtro',
        lt: 'Menor do que ',
        lte: 'Menor ou igual à',
        gt: 'Maior do que',
        gte: 'Maior ou igual à',
        dateIs: 'Data igual à',
        dateIsNot: 'Data diferente de',
        dateBefore: 'Data antes de',
        dateAfter: 'Data depois de',
        clear: 'Limpar',
        apply: 'Aplicar',
        matchAll: 'Todas' ,
        matchAny: 'Qualquer uma' ,
        addRule: 'Adicionar Regra' ,
        removeRule: 'Remover Regra',
        accept: 'Sim',
        reject: 'Não',
        choose: 'Escolher',
        upload: 'Enviar',
        cancel: 'Cancelar',
        dayNames: ["Domingo", "Segunda", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"],
        dayNamesShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
        dayNamesMin: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
        monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novemvro","Dezembro"],
        monthNamesShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun","Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        today: 'Hoje',
        weekHeader: 'Semana',
        firstDayOfWeek: 0,
        dateFormat: 'dd/mm/yy',
        weak: 'Fraco',
        medium: 'Médio',
        strong: 'Forte',
        passwordPrompt: 'Digite a senha',
        emptyFilterMessage: 'Nenhum resultado foi encontrado',
        emptyMessage: 'Nenhuma opção disponível'
    },
    
    // Default theme configuration
    theme: {
        unstyled: true,
        preset: Noar,
        options: {
            darkModeSelector: false,
            cssLayer: {
                name: 'primevue',
                order: 'tailwind-base, primevue, tailwind-utilities'
            }
        }
    }
} );

app.use( ToastService );
app.use( ConfirmationService );

service.minhaConta.getAtual()
.finally( r => app.mount('#app') );
