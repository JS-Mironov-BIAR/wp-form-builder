/**
 * * * * Working with settings and integration with Totalcontroller
 * @parameter {Event} e
 * form @param {HTMLFormElement}
 */
export default async function formEventsClick(e, form) {
    e.preventDefault()

    if (typeof wtn_ajax === 'undefined') {
        // eslint-disable-next-line
        console.warn('wtn_ajax не определён')
        return
    }
    const wrapper = form.closest('.wtn-form-wrapper')
    if (wrapper) wrapper.classList.add('loading')

    const data = new FormData(form)
    data.append('action', 'wtn_send_form')
    data.append('_ajax_nonce', wtn_ajax.nonce)

    try {
        const response = await fetch(wtn_ajax.url, {
            method: 'POST',
            body: data,
        })

        const result = await response.json()

        if (result.success) {
            ModalControllers?.Status?.setSuccess?.()
            form.reset()
        } else {
            ModalControllers?.Status?.setError?.()
        }
    } catch (error) {
        ModalControllers?.Status?.setError?.()
    } finally {
        if (wrapper) wrapper.classList.remove('loading')
    }
}
