/**
 * Restricts the input in the phone field
 * @param {HTMLInputElement} input
 */
export function restrictPhoneInput(input) {
    input.addEventListener('input', () => {
        input.value = input.value
            .replace(/[^\d+\-\s()]/g, '') // delete everything except numbers, spaces, (), -
            .replace(/(\+)(?=.*\+)/g, '') // we allow only one +
    })
}

/**
 * Restricts name input in the field
 * @param {HTMLInputElement} input
 */
export function restrictNameInput(input) {
    input.addEventListener('input', () => {
        input.value = input.value
            .replace(/[^a-zA-Zа-яА-ЯёЁ\s\-]/g, '') // only letters, spaces, dashes
            .substring(0, 30) // length limitation
    })
}

/**
 * Restricts required select fields — blocks empty submission
 * @param {NodeListOf<HTMLSelectElement>} selects
 */
export function restrictSelectField(selects) {
    selects.forEach((select) => {
        select.addEventListener('invalid', (e) => {
            if (!select.value.trim()) {
                // e.preventDefault()
            }
        })
    })
}
