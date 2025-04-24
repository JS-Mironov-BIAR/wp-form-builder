/**
 * Serializes FormData into a sorted JSON string array of [key, value] pairs.
 * @param {FormData} fd
 * @return {string}
 */
export default function serializeFormData(fd) {
    const entries = Array.from(fd.entries()).sort(([a], [b]) => a.localeCompare(b))
    return JSON.stringify(entries)
}

/**
 * Checks whether the shape has been changed relative to the original data.
 * @param {HTMLFormElement} form
 * @param {string} initial — saved string serializeFormData()
 */
export const isFormChanged = (form, initial) => serializeFormData(new FormData(form)) !== initial
