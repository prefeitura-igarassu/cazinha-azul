import service from "./Service.js";

export default class Basic {
    
    then( request ){
        // from: https://jasonwatmore.com/post/2021/10/09/fetch-error-handling-for-failed-http-responses-and-network-errors
        return this.catch( request.then( response => {
            const isJson = response.headers.get('content-type')?.includes( 'application/json' );
            const data   = isJson ? response.json() : response.text();

            if( response.status === 302 )
            {
                // redireciona a página para a login
                window.location.href = response.headers.has( 'location' ) 
                    ? response.headers.get( 'location' )
                    : "/#/login";
            }
            else if( response.ok ){
                return data;
            }
            else{
                return data.then( value => Promise.reject( {
                    status : response.status ,
                    data : value
                } ) );
            }
        } ) );
    }

    catch( request ){
        return request.catch( result => {
            if( result.status === 401 && service.view ){     // não autorizado
                if( result.data?.message == 'Unauthenticated.' ){
                    return [];
                }

                service.view.$toast.add({
                    severity:'error', 
                    summary:'Negado', 
                    detail: 'Você não tem permissão a esta funcionalidade!', 
                    life: 3000
                });

                return [];
            }
            else if( result.status === 419 ){
                service.minhaConta.getCSRF();
                return Promise.reject( result );    
            }

            return Promise.reject( result );
        });
    }

    getGeneric( url ) {
        return this.then( this.fetchCSRF( url ) );
    }

    fetchCSRF( url ) {
        return fetch( url , { 
            headers: {
                "Content-Type": "application/json" ,
                "Accept": "application/json" ,
                "X-Requested-With": "XMLHttpRequest" ,
                "X-CSRF-Token": service.csrfToken
            } ,
            credentials: "same-origin" ,
        } );
    }

    getFormData( item ){
        let form = new FormData();

        Object.keys( item ).forEach( key => {
            form.append( key , item[key] );
        });

        return form;
    }

    //from: https://developer.mozilla.org/en-US/docs/Web/API/Request/cache
    cache( url , params ){
        // Naive stale-while-revalidate client-level implementation.
        // Prefer a cached albeit stale response; but update if it's too old.
        // AbortController and signal to allow better memory cleaning.
        // In reality; this would be a function that takes a path and a
        // reference to the controller since it would need to change the value
        let controller = new AbortController();

        return fetch( url , {
            cache: "force-cache",
            mode: "same-origin",
            signal: controller.signal,
            ...params
        })
        .catch((e) =>
            e instanceof TypeError && e.message === "Failed to fetch"
            ? { status: 504 } // Workaround for chrome; which fails with a TypeError
            : Promise.reject(e),
        )
        .then((res) => {
            if ( res.status === 504 ){
                controller.abort();
                controller = new AbortController();
                return fetch( url , { cache: "no-cache", mode: "same-origin", ...params });
            }
            
            const date = res.headers.get( "date" );
            const dt   = date ? new Date( date ).getTime() : 0;

            // 86_400_000 = if older than 24 hours
            
            // if it's older than 5 minutes
            if ( dt < Date.now() - 300_000 ){
                // no cancellation or return value
                return fetch( url , { cache: "no-cache", mode: "same-origin" , ...params });
            }
            
            return res;
        });
    }

}