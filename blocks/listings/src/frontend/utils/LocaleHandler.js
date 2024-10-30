/**
 * Loader
 */
const LocaleHandler = ( locale ) => {
    // const allowedLocales = ['fi'];
    const defaultLocale = 'fi';

    /*
    if( allowedLocales.includes( locale ) ){
        return locale;
    }
    */

    if( !locale ){
        return defaultLocale;
    }

    return locale;
};

export default LocaleHandler;
