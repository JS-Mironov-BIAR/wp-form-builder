import eventClickEditButton from './controllersHandler'

export default function initControllersHandler() {
    const buttonsEdit = document.querySelectorAll('[data-wpfb-form-edit]')

    buttonsEdit.forEach((button) => {
        button.addEventListener('click', (e) => eventClickEditButton(e, button))
    })
}
