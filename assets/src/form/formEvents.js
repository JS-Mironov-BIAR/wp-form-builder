/* global wpfb_front */
/**
 * Handles form submit via AJAX
 * @param {Event} e
 * @param {HTMLFormElement} form
 */
export default async function formEventsClick(e, form) {
    e.preventDefault()

    if (typeof wpfb_front === 'undefined') {
        console.warn('⚠️ wpfb_front is not defined')
        return
    }

    const wrapper = form.closest('.wfb-form-wrapper')
    if (wrapper) wrapper.classList.add('loading')

    const data = new FormData(form)

    // Проверяем и добавляем обязательные поля
    if (!data.has('action')) {
        data.append('action', 'wpfb_send_form')
    }

    if (!data.has('_ajax_nonce')) {
        data.append('_ajax_nonce', wpfb_front.nonce)
    }

    if (!data.has('form_id')) {
        const formIdInput = form.querySelector('input[name="form_id"]')
        if (formIdInput) {
            data.append('form_id', formIdInput.value)
        } else {
            console.error('⚠️ form_id missing in form')
            if (wrapper) wrapper.classList.remove('loading')
            return
        }
    }

    try {
        const response = await fetch(wpfb_front.ajaxUrl, {
            method: 'POST',
            body: data,
        })

        const result = await response.json()

        if (result.success) {
            ModalControllers?.Status?.setSuccess?.()
            form.reset()
        } else {
            ModalControllers?.Status?.setError?.()
            console.error('❌ Ошибка отправки формы:', result.data || result)
        }
    } catch (error) {
        ModalControllers?.Status?.setError?.()
        console.error('❌ Сетевая ошибка:', error)
    } finally {
        if (wrapper) wrapper.classList.remove('loading')
    }
}
