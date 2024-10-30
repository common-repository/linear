/**
 * Populate
 */
const URLParamsPopulator = ( newFiltersState ) => {

    if( !newFiltersState ){
        return;
    }

    let simpleParams = [
        "productGroup",
        "commissionType",
        "businessCommissionType",
        "search",
        "orderBy"
    ];

    let rangeParams = [
        'priceMin',
        'priceMax',
        'rentMin'
    ];

    let specifications = [
        'sauna', 'balcony', 'elevator'
    ];

    if( !history.pushState ){
        return;
    }

    // Base params
    let searchParams = new URLSearchParams();

    Object.keys( newFiltersState ).forEach( key => {

        if( typeof newFiltersState[key] === 'object' ){
            if( newFiltersState[key].every(element => element === false) ){
                return;
            }
        }

        if( ['', 0, [], {}, typeof 'undefined'].includes( newFiltersState[key] ) ){
            return;
        }

        // Handle simple values
        if( simpleParams.includes( key ) ){
            searchParams.set( key, newFiltersState[key]);
        }

        // Handle pricing
        if( rangeParams.includes( key ) ){
            if( 'priceMin' === key ){
                if( parseInt(newFiltersState[key]) > 0 ){
                    searchParams.set( key, newFiltersState[key]);
                }
            }

            if( 'priceMax' === key ){
                if( parseInt(newFiltersState[key]) < 750000 ){
                    searchParams.set( key, newFiltersState[key]);
                }
            }

            if( 'rentMin' === key ){
                if( parseInt(newFiltersState[key]) > 0 ){
                    searchParams.set( key, newFiltersState[key]);
                }
            }

            if( 'rentMax' === key ){
                if( parseInt(newFiltersState[key]) < 1500 ){
                    searchParams.set( key, newFiltersState[key]);
                }
            }

            if( 'areaMin' === key ){
                if( parseInt(newFiltersState[key]) > 0 ){
                    searchParams.set( key, newFiltersState[key]);
                }
            }

            if( 'areaMax' === key ){
                if( parseInt(newFiltersState[key]) < 500 ){
                    searchParams.set( key, newFiltersState[key]);
                }
            }
        }

        // Handle more complex values
        if( key === "roomCount"){
            let cleanRoomCount = newFiltersState[key].map((roomCount, index) => {
                if( roomCount ){
                    return index + 1;
                } else {
                    return 0;
                }
            }).filter((roomCount) => {
                return roomCount !== 0
            });
    
            searchParams.set( key, cleanRoomCount.join(',') );
        }

        // Specifications
        if( specifications.includes( key ) ){
            specifications.forEach( spec => {
                if( spec === key ){
                    if( newFiltersState[key][0] === true ){
                        searchParams.set( key, "true" );
                    }
                }
            });
        }

    });

    let newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + searchParams.toString();

    if ( newurl.slice(-1) === '?' ) {
        newurl = newurl.slice(0, -1);
    }

    window.history.pushState({path: newurl}, '', newurl);
}

export default URLParamsPopulator;
