import ChaveValorService from "./ChaveValorService.js";
import MinhaContaService from "./MinhaContaService.js";
import UsuarioService from "./UsuarioService.js";
import UsuarioGrupoService from "./UsuarioGrupoService.js";
import UsuarioLogService from "./UsuarioLogService.js";
import UsuarioPermissaoService from "./UsuarioPermissaoService.js";
import DefaultService from "./DefaultService.js";
import Basic from "./Basic.js";

export default {
    servicos   : new DefaultService( "/servicos"    ),
    unidades   : new DefaultService( "/unidades"    ),
    terapeutas : new DefaultService( "/terapeutas"  ),
    devolutivas: new DefaultService( "/devolutivas" ),
    sessoes    : new DefaultService( "/sessoes"     ),
    
    fichas     : new DefaultService( "/fichas"      ),
    fichas_andamentos: new DefaultService( "/fichas/andamentos" ),
    fichas_arquivos  : new DefaultService( "/fichas/arquivos"   ),
    fichas_servicos  : new DefaultService( "/fichas/servicos"   ),
    
    // services padrões
    generic   : new Basic(),

    chaves    : new ChaveValorService(),
    usuarios  : new UsuarioService(),
    grupos    : new UsuarioGrupoService(),
    permissoes: new UsuarioPermissaoService(),
    logs      : new UsuarioLogService(),
    minhaConta: new MinhaContaService(),

    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute( 'content' ),
    usuarioLogado: null,
    view: null,

    setCsrfToken( value ){
        this.csrfToken = value;
    }
};