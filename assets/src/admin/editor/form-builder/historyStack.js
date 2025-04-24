export default class HistoryStack {
    #stack = []

    #redo = []

    /**
     * @param {string} initial
     */
    constructor(initial = '') {
        this.push(initial)
    }

    /** @param {string} value */
    push(value) {
        if (this.#stack.at(-1) !== value) {
            this.#stack.push(value)
            this.#redo.length = 0
        }
    }

    undo() {
        if (this.#stack.length > 1) {
            this.#redo.push(this.#stack.pop())
            return this.#stack.at(-1)
        }
        return null
    }

    redo() {
        if (this.#redo.length) {
            const v = this.#redo.pop()
            this.#stack.push(v)
            return v
        }
        return null
    }
}
