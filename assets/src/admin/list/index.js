/* when loading the list page */
import { attachEditHandlers } from './formList'
import attachDeleteHandler from './deleteForm'

document.addEventListener('DOMContentLoaded', () => {
    attachEditHandlers()
    attachDeleteHandler()
})

/* if you need to update the list via WebSocket or Polling:
   await refreshvmlist(); */
