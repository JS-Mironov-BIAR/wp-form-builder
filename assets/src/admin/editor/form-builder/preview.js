import parseTemplate from './templateParser'

/**
 * Updates the HTML preview based on the textarea content.
 * @param {HTMLTextAreaElement} textarea
 * @param {HTMLElement} preview
 */
export default function updatePreview(textarea, preview) {
    if (!preview) return
    preview.innerHTML = parseTemplate(textarea.value)
    preview.querySelectorAll('input,textarea,select').forEach((el) => {
        el.removeAttribute('required')
        el.removeAttribute('name')
        el.removeAttribute('disabled')
    })
}
