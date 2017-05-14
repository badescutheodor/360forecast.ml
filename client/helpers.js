import _ from 'lodash'
import Noty from 'noty';

/**
 * Returnz weather icon from id
 * @param id
 * @deprecated
 */
export const iconize = (id) => {
    //
};

/**
 *
 * @param type
 * @param messaage
 */
export const notify = (type, message) => {
    new Noty({
        type,
        killer: true,
        text: message,
        timeout: 400
    }).show();
};