/**
 * @param {MouseEvent} event
 * @param {HTMLElement} wrapper
 * @param {HTMLElement} tab
 */
export default function tabEventAction(event, wrapper, tab) {
    event.preventDefault()
    const target = tab.getAttribute('href')

    // active button
    wrapper.querySelectorAll('.nav-tab').forEach((t) => t.classList.toggle('nav-tab-active', t === tab))

    // showing the necessary content
    wrapper.querySelectorAll('.wpfb-tab-content').forEach((c) => {
        c.style.display = c.matches(target) ? 'block' : 'none'
    })
}
