/* normal entrance (post.php ) */
import initTabsHandler from '../tabs/tabsHandler'
import handleSave from './saveForm'
import initFormEditor from './form-builder'

document.addEventListener('DOMContentLoaded', () => {
    initTabsHandler()
    handleSave()
    initFormEditor()
})

/* arrival from drawer */
document.addEventListener('wpfb-drawer-loaded', (e) => {
    const root = e.detail.root
    initTabsHandler(root)
    handleSave(root)
    initFormEditor(root)
})
