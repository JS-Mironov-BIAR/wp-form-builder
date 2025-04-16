import formEventsClick from './formEvents'
import { restrictNameInput, restrictPhoneInput } from './formValidation'

export default function initFormHandler() {
    const forms = document.querySelectorAll('.wtn-form')
    if (!forms.length) return

    forms.forEach((form) => {
        form.addEventListener('submit', async (e) => formEventsClick(e, form))

        const phoneInput = form.querySelector('input[type="tel"]')
        if (phoneInput) restrictPhoneInput(phoneInput)

        const nameInput = form.querySelector('input[name="name"]')
        if (nameInput) restrictNameInput(nameInput)
    })
}
