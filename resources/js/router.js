import {createRouter, createWebHashHistory} from 'vue-router';

function add_rotas( plural , singular , titulo , conf ){
    if( !titulo ) titulo = plural;
    if( !conf   ) conf   = {};

    let rotas = [{
            path: `/${plural}`,
            name: plural,
            component: () => import( `./pages/${plural}/${singular}Table.vue` ) ,
            meta: {
                breadcrumb: [{ label: titulo }],
            }
        }, {
            path: `/${plural}/adicionar`,
            name: `${plural}.adicionar`,
            component: () => conf.add 
                ? import( `./pages/${plural}/${singular}Add.vue`  ) 
                : import( `./pages/${plural}/${singular}Edit.vue` ),
            meta: {
                breadcrumb: [{ parent: titulo , label: 'Adicionar' }],
            }
        }, {
            path: `/${plural}/:id` + ( conf.view ? '/editar' : '' ),
            name: `${plural}.editar`,
            props: true,
            component: () => import( `./pages/${plural}/${singular}Edit.vue` ) ,
            meta: {
                breadcrumb: [{ parent: titulo , label: 'Editar' }],
            }
        },
    ];
    
    if( conf.view ){
        rotas.push({
            path: `/${plural}/:id`,
            name: `${plural}.visualizar`,
            props: true,
            component: () => import( `./pages/${plural}/${singular}View.vue` ) ,
            meta: {
                breadcrumb: [{ parent: titulo , label: 'Visualizar' }],
            }
        });
    }

    return rotas;
}

const rotas = [
    { path: '/', name: 'dashboard', exact: true, component: () => import('./pages/Dashboard.vue'),
        meta: {
            breadcrumb: [{ label: 'Dashboard' }],
        }
    },
    { path: '/login'   , name: 'login'   , component: () => import( './pages/Login.vue'    ) },
    { path: '/error'   , name: 'error'   , component: () => import( './pages/Error.vue'    ) },
    { path: '/notfound', name: 'notfound', component: () => import( './pages/NotFound.vue' ) },
    { path: '/access'  , name: 'access'  , component: () => import( './pages/Access.vue'   ) },
    { path: '/password/reset' , name: 'password.reset' , component: () => import( './pages/PasswordReset.vue' ) },
    
    {
        // and finally the default route, when none of the above matches:
        path: '/:pathMatch(.*)*', name: '404',
        component: () => import('./pages/NotFound.vue')
    },

    // usuarios
    { path: '/usuarios'            , name: 'usuarios'           , component: () => import( './pages/usuarios/UsuarioMain.vue' ) },
    { path: '/usuarios/adicionar'  , name: 'usuarios.adicionar' , component: () => import( './pages/usuarios/UsuarioAdd.vue'  ) },
    { path: '/usuarios/:id'        , name: 'usuarios.view'      , component: () => import( './pages/usuarios/UsuarioView.vue' ) , props: true, },
    { path: '/usuarios/:id/editar' , name: 'usuarios.editar'    , component: () => import( './pages/usuarios/UsuarioEdit.vue' ) , props: true, },
];

const routes = rotas.concat(
    add_rotas( "fichas"      , "Ficha"      , "Ficha"      , { view: true } ),
    add_rotas( "servicos"    , "Servico"    , "Serviço"                     ),
    add_rotas( "unidades"    , "Unidade"    , "Unidades"   , { view: true } ),
    add_rotas( "sessoes"     , "Sessao"     , "Sessão"     , { view: true } ),
    add_rotas( "devolutivas" , "Devolutiva" , "Devolutiva" , { view: true } ),
    add_rotas( "terapeutas"  , "Terapeuta"  , "Terapeuta"  , { view: true } ),

    add_rotas( "usuarios_grupos" , "Grupo" ),
    add_rotas( "usuarios_logs"   , "Log"   ),
);

const router = createRouter({
    history: createWebHashHistory(),
    routes,
    scrollBehavior () {
        return { left: 0, top: 0 };
    }
});

/*
//from: https://router.vuejs.org/guide/advanced/navigation-guards.html

router.beforeEach( async (to, from, next ) => {
    next();
});

router.afterEach((to, from, failure) => {
    if (!failure) sendToAnalytics(to.fullPath)
});
*/

export default router;