import tabEventAction from './tabsEvents.js'

/**
 * Initializes tab navigation inside the passed container.
 * You can call it many times — already initialized wrappers will be skipped.
 *
 * @param {parentNode} [root=document] – where to look .wpfb-tab-wrapper
 */
export default function initTabsHandler(root = document) {
    /** @type {NodeListOf<HTMLElement>} */
    const wrappers = root.querySelectorAll('.wpfb-tab-wrapper')

    wrappers.forEach((wrapper) => {
        if (wrapper.dataset.tabsInit === '1') return
        wrapper.dataset.tabsInit = '1'

        // initial display of the first tab
        const first = wrapper.querySelector('.nav-tab')
        if (first) {
            tabEventAction(new Event('init'), wrapper, first)
        }

        // delegated click from wrapper‑a
        wrapper.addEventListener('click', (e) => {
            const tab = e.target.closest('.nav-tab')
            if (tab) tabEventAction(e, wrapper, tab)
        })
    })
}
