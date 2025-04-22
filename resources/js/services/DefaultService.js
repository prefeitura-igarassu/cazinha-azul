import Basic from "./Basic.js";
import service from "./Service.js";

export default class DefaultService extends Basic {
    
    constructor( url ){
        super();
        this.url = url;
    }

    save( data ){
        return data.id 
            ? this.update( data ) 
            : this.insert( data );
    }

    insert( data ) {
        return this.then( fetch( `${this.url}` , {
                headers: {
                    "Content-Type": "application/json" ,
                    "Accept": "application/json" ,
                    "X-Requested-With": "XMLHttpRequest" ,
                    "X-CSRF-Token": service.csrfToken
                } ,
                method: 'POST' ,
                credentials: "same-origin" ,
                body: JSON.stringify( data )
            } ) );
    }

    update( data ) {
        return this.then( fetch( `${this.url}\\${data.id}` , {
            headers: {
                "Content-Type": "application/json" ,
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            method: 'PUT' ,
            credentials: "same-origin" ,
            body: JSON.stringify( data )
        } ) );
    }

    destroy( id ) {
        return this.then( fetch( `${this.url}\\${id}` , { 
            headers: {
                "Content-Type": "application/json" ,
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            method: 'DELETE' ,
            credentials: "same-origin" ,
        } ) );
    }

    get( id , params ) {
        if( !id ){
            return Promise.all( [ null ] );
        }

        const queryParams = this.getQueryString( params );
        return this.getGeneric( `${this.url}\\${id}?${queryParams}` );
    }

    getRegras( id ) {
        return this.getGeneric( `${this.url}\\regras` );
    }
    
    search( params ) {
        const queryParams = this.getQueryString( params );
        return this.then( fetch( `${this.url}?${queryParams}` ) );
    }

    getQueryString( params , field ){
        if( !params ){
            return "";
        }

        let query = [];
        
        Object.keys( params ).forEach( key => {
            let k = field ? `${field}[${key}]` : key;
            let value = params[ key ];
            
            if( value == null ){
                query.push( encodeURIComponent(k) + '=' );
            }
            else if( Array.isArray( value ) ){
                k += "[]";
                value.forEach( v => query.push( encodeURIComponent(k) + '=' + encodeURIComponent(v) ) );
            }
            else if( typeof value === 'object' ){
                query.push( this.getQueryString( value , k ) );
            }
            else{
                query.push( encodeURIComponent( k ) + '=' + encodeURIComponent( value ) );
            }
        });
        
        return query.join("&");
    }

    getOptions( params ){
        const queryParams = this.getQueryString( params );

        return this.then( this.cache( `${this.url}/options?${queryParams}` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            credentials: "same-origin" ,
        } ) );
    }

    // ------------------------ //
    // ------------------------ //
    // ------------------------ //

    /**
     * Retorna um resumo ou uma lista de algum campo. Segue o mesmo principal 
     * de pesquisa do search. Deve-se colocar o 'column' para especificar a coluna e
     * 'op' para especificar a operação (SUM, MAX, MIN, AVG, COUNT). Caso o 'op' não
     * seja especificado, uma lista será retornada.
     * 
     * @param {object} params 
     * @returns object
     */
     getDashboardResume( params ){
        const queryParams = this.getQueryString( params );

        return this.then( this.cache( `${this.url}/dashboard/resume?${queryParams}` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            credentials: "same-origin" ,
        } ) );
    }

    getDashboardGroup( params ){
        const queryParams = this.getQueryString( params );

        return this.then( this.cache( `${this.url}/dashboard/group?${queryParams}` , {
            headers: {
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            credentials: "same-origin" ,
        } ) );
    }
    
    imprimir( id ) {
        window.open( `${this.url}/imprimir/${id}` , "_blank" );
    }

}