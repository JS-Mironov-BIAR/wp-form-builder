import serializeFormData from '../../shared/serialize'

let initialForm = ''

/**
 * Preserves the original state of the form.
 * @param {HTMLFormElement} form
 */
export const rememberInitial = (form) => {
    initialForm = serializeFormData(new FormData(form))
}

/**
 * True if the form has changed after remember Initial().
 * @param {HTMLFormElement} form
 */
export const changed = (form) => serializeFormData(new FormData(form)) !== initialForm
