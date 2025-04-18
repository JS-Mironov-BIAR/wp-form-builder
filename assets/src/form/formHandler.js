import formEventsClick from './formEvents'
import { restrictNameInput, restrictPhoneInput } from './formValidation'

export default function initFormHandler() {
    const forms = document.querySelectorAll('.wfb-form')
    if (!forms.length) return

    forms.forEach((form) => {
        form.addEventListener('submit', async (e) => formEventsClick(e, form))

        const phoneInput = form.querySelector('input[type="tel"]')
        if (phoneInput) restrictPhoneInput(phoneInput)

        const nameInput = form.querySelector('input[name="name"]')
        if (nameInput) restrictNameInput(nameInput)

        // const requiredSelects = form.querySelectorAll('select[required]')
        // if (requiredSelects.length) restrictSelectField(requiredSelects)
        // trash select
        // Теперь ищем все select'ы
        const selects = form.querySelectorAll('select')
        console.log('selects ->', selects)
        selects.forEach((select) => {
            const extraContentContainer = form.querySelector('.wfb-select-extra-content')
            console.log('extraContentContainer ->', extraContentContainer)
            if (!extraContentContainer) return

            const contentData = extraContentContainer.getAttribute('data-content') || ''

            select.addEventListener('change', () => {
                console.log('Выбор произошёл!')

                const selectedValue = select.value.trim()
                if (selectedValue) {
                    const safeValue = selectedValue.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
                    const regex = new RegExp(`\\[${safeValue}\\](.*?)($|\\[)`, 's')

                    const match = contentData.match(regex)
                    if (match && match[1]) {
                        extraContentContainer.innerHTML = match[1].trim()
                        extraContentContainer.style.display = 'block'
                    } else {
                        extraContentContainer.style.display = 'none'
                    }
                } else {
                    extraContentContainer.style.display = 'none'
                }
            })

            // Сразу обновим при загрузке, если что-то выбрано
            const selectedValue = select.value.trim()
            if (selectedValue) {
                const safeValue = selectedValue.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
                const regex = new RegExp(`\\[${safeValue}\\](.*?)($|\\[)`, 's')

                const match = contentData.match(regex)
                if (match && match[1]) {
                    extraContentContainer.innerHTML = match[1].trim()
                    extraContentContainer.style.display = 'block'
                } else {
                    extraContentContainer.style.display = 'none'
                }
            }

            const hiddenFieldName = select.getAttribute('data-target-hidden')
            const hiddenInput = form.querySelector(`input[type="hidden"][name="${hiddenFieldName}"]`)

            if (hiddenInput) {
                select.addEventListener('change', () => {
                    const selectedValueActive = select.value.trim()
                    hiddenInput.value = selectedValueActive
                })
            }
        })
        // trash select
    })
}
