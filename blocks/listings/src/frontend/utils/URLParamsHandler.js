/**
 * Loader
 */
 const URLParamsHandler = () => {
    // Onload
    const urlParams = new URLSearchParams(window.location.search);
    let onLoadParams = {};

    const simepleParams = [
        'search',
        'commissionType',
        'productGroup',
        'listingType',
        'searchOption',
        'orderBy',
        "filterRealtors",
    ];

    const boolArrays = [
        'businessListingType'
    ];

    const numericalParams = [
        'priceMin',
        'priceMax',
        'rentMin',
        'rentMax',
        'areaMin',
        'areaMax'
    ]

    let specifications = [
        'sauna', 
        'hasBalcony', 
        'housingCooperativeElevator'
    ];

    // Parse simple values
    simepleParams.map(function(param) {
        if( typeof urlParams.get(param) !== 'undefined' && urlParams.get(param) ){
            onLoadParams[param] = urlParams.get(param);
        }
    });

    numericalParams.map(function(param) {
        if( typeof urlParams.get(param) !== 'undefined' && urlParams.get(param) ){
            onLoadParams[param] = parseInt( urlParams.get(param) );
        }
    });

    // Parse roomCount
    if( typeof urlParams.get('roomCount') !== 'undefined' && urlParams.get('roomCount') ){
        let splitRooms = (urlParams.get('roomCount').split(',')).map(Number);

        let cleanSplitRooms = Array.from(Array(6)).map((value, index) => {
            if( splitRooms.includes( index + 1 ) ){
                return true;
            }

            return false;
        });

        onLoadParams['roomCount'] = cleanSplitRooms;
    }

    // Parse specifications
    specifications.map(function(param) {
        if( typeof urlParams.get(param) !== 'undefined' && urlParams.get(param) ){
            if( urlParams.get(param) === 'true' ){
                onLoadParams[param] = [true];
            }
        }
    });

    // Parse business listing type
    boolArrays.map(function(param) {
        if( typeof urlParams.get(param) !== 'undefined' && urlParams.get(param) ){
            if( urlParams.get(param) ){
                onLoadParams[param] = urlParams.get(param).split(',').map(val => val === 'true' ? true : false);
            }
        }
    });

    return onLoadParams;
};

export default URLParamsHandler;
