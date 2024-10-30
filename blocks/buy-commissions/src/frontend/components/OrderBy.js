import React, { useContext } from 'react';
import { AppContext } from './../utils/Context';
import { AnimatePresence, motion } from 'framer-motion';
import { URLParamsPopulator } from './../utils';

const OrderBy = ({}) => {
	const { frontEndFilters, setFrontEndFilters, texts } = useContext(AppContext);

    const handleChange = (e) => {
        const { value } = e.target;

        setFrontEndFilters({
            ...frontEndFilters,
            'orderBy': value
        });

        URLParamsPopulator({
            ...frontEndFilters,
            'orderBy': value
        })
    }

    let currentValue = typeof frontEndFilters['orderBy'] !== 'undefined' ? frontEndFilters['orderBy'] : 'default';

	return (
		<>
            <AnimatePresence>
                <motion.div
                    animate={{ opacity: 1, y: 0 }}
                    initial={{ opacity: 0, y: 20 }}
                    exit={{ opacity: 0, y: 20 }}
                    transition={{
                        duration: 0.5,
                        delay: 0,
                        type: 'spring',
                    }}
                    className="linear-buy-commissions__orderby"
                >
                    <p>{texts.sort}:</p>
                    <select value={currentValue} onChange={(e) => handleChange(e)}>
                        <option value="default">{texts.latestFirst}</option>
                        <option value="reverse">{texts.oldestFirst}</option>
                    </select>
                </motion.div>
            </AnimatePresence>
		</>
	);
};

export default OrderBy;
