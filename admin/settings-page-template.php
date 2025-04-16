<?php
$chat_sync_success = isset($_GET['chat_sync']) && $_GET['chat_sync'] === 'success';
$test_success = isset($_GET['test_send']) && $_GET['test_send'] === 'success';

?>

<div class="wrap">
	<h1>
		<img src="<?php echo plugin_dir_url(__DIR__) . 'assets/img/telegram-logo.svg'; ?>" alt="Telegram" style="height: 32px; vertical-align: middle; margin-right: 10px;">
		Настройки уведомлений Telegram
	</h1>

	<div class="notice notice-info">
		<p><strong>1.</strong> Создайте бота в <a href="https://t.me/BotFather" target="_blank">@BotFather</a> → команда <code>/newbot</code> → получите токен.</p>
		<p><strong>2.</strong> Напишите что-нибудь своему боту в Telegram (например, <code>/start</code>).</p>
		<p><strong>3.</strong> Нажмите кнопку «Обновить Chat ID из Telegram» — бот подхватит ID.</p>
	</div>

	<?php if ($chat_sync_success): ?>
		<div class="notice notice-success is-dismissible"><p>✅ Chat ID успешно обновлён из Telegram!</p></div>
	<?php endif; ?>

	<?php if ($test_success): ?>
		<div class="notice notice-success is-dismissible"><p>✅ Тестовое сообщение успешно отправлено в Telegram!</p></div>
	<?php endif; ?>

	<div class="metabox-holder">
		<div class="postbox-container" style="width: 70%;">
			<div class="meta-box-sortables">
				<div class="postbox">
					<h2 class="hndle">Управление ID и отправкой</h2>
					<div class="inside">
						<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="margin-bottom: 16px;">
							<input type="hidden" name="action" value="wtn_chat_sync">
							<?php submit_button('Обновить Chat ID из Telegram', 'primary', 'submit', false); ?>
						</form>

						<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
							<input type="hidden" name="action" value="wtn_test_send">
							<?php submit_button('Отправить тестовое сообщение', 'secondary', 'submit', false); ?>
						</form>
					</div>
				</div>

				<div class="postbox">
					<h2 class="hndle">Настройки подключения</h2>
					<div class="inside">
                        <form method="post" action="options.php">
							<?php
							settings_fields('wtn_connection_group');
							$bot_token = get_option('wtn_bot_token', '');
							$chat_id = get_option('wtn_chat_id', '');
							?>
                            <table class="form-table">
                                <tr>
                                    <th><label for="wtn_bot_token">Bot Token</label></th>
                                    <td><input type="text" name="wtn_bot_token" id="wtn_bot_token" class="regular-text" value="<?php echo esc_attr($bot_token); ?>"></td>
                                </tr>
                                <tr>
                                    <th><label for="wtn_chat_id">Chat ID</label></th>
                                    <td><input type="text" name="wtn_chat_id" id="wtn_chat_id" class="regular-text" value="<?php echo esc_attr($chat_id); ?>"></td>
                                </tr>
                            </table>
							<?php submit_button('Сохранить настройки подключения'); ?>
                        </form>
					</div>
				</div>

                <div class="postbox">
                    <h2 class="hndle">HTML-шаблон формы</h2>
                    <div class="inside">
                        <p>Ниже вы можете отредактировать HTML формы. Используйте шаблоны-подсказки и создайте свою форму.</p>

                        <div style="display: flex; gap: 20px; align-items: flex-start;">
                            <div style="flex: 1;">
                                <div style="margin-bottom: 12px;">
                                    <strong>Вставить элемент:</strong><br>
                                    <button type="button" class="wtn-insert-tag" data-tag='[text name="" placeholder="Ваше имя"]' title='Текстовое поле. Атрибуты: required, label, name, placeholder, class'>[text]</button>
                                    <button type="button" class="wtn-insert-tag" data-tag='[tel name="" placeholder="Ваш телефон"]' title='Телефон. Атрибуты: required, label, name, placeholder, class'>[tel]</button>
                                    <button type="button" class="wtn-insert-tag" data-tag='[email name="" placeholder="Email"]' title='Email. Атрибуты: required, label, name, placeholder, class'>[email]</button>
                                    <button type="button" class="wtn-insert-tag" data-tag='[textarea name="" placeholder="Ваше сообщение"]' title='Многострочное поле. Атрибуты: required, label, name, placeholder, class'>[textarea]</button>
                                    <button type="button" class="wtn-insert-tag" data-tag='[send text="Отправить" class="btn"]' title='Кнопка отправки. Атрибуты: text, class'>[send]</button>
                                    <button type="button" id="wtn-undo-btn" style="margin-left: 16px;">↩️ Отменить</button>
                                    <button type="button" id="wtn-redo-btn">↪️ Повторить</button>

                                </div>

                                <form method="post" action="options.php">
		                            <?php
		                            settings_fields('wtn_form_group');
		                            $form_markup = get_option('wtn_custom_form_markup');
		                            if ($form_markup === false || trim($form_markup) === '') {
			                            $form_markup = '[text name="name" placeholder="Ваше имя"]' . "\n" .
			                                           '[tel name="phone" placeholder="Ваш телефон"]' . "\n" .
			                                           '[textarea name="message" placeholder="Ваше сообщение"]' . "\n" .
			                                           '[send text="Отправить"]';
		                            }
		                            ?>
                                    <textarea id="wtn-form-editor" name="wtn_custom_form_markup" rows="10" style="width:100%; font-family: monospace;"><?php echo esc_textarea($form_markup); ?></textarea>
		                            <?php submit_button('Сохранить форму'); ?>
                                </form>
                            </div>

                            <div style="flex: 1;">
                                <strong>Предпросмотр:</strong>
                                <div id="wtn-form-preview" style="border: 1px solid #ddd; padding: 15px; background: #fafafa; min-height: 160px;"></div>

                                <small style="color:#666; display:block; margin-top:8px;">
                                    ⚠️ Предпросмотр не отражает стили на сайте. Это только визуальная подсказка структуры.
                                </small>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="postbox">
	                <?php
	                $message_template = get_option('wtn_custom_message_template');

	                if ($message_template === false || trim($message_template) === '') {
		                $message_template = "📩 *Новое сообщение с сайта*\n\n👤 Имя: [name]\n📱 Телефон: [phone]\n💬 Сообщение: [message]";
	                }
	                ?>
                    <h2 class="hndle">Шаблон сообщения в Telegram</h2>
                    <div class="inside">
                        <p>Вы можете настроить, как будет выглядеть сообщение в Telegram. Используйте переменные, соответствующие полям формы.</p>

                        <div style="margin-bottom: 12px;">
                            <strong>Добавить переменную:</strong><br>
                            <button type="button" class="wtn-insert-message-tag" data-tag="[name]" title="Имя отправителя">[name]</button>
                            <button type="button" class="wtn-insert-message-tag" data-tag="[phone]" title="Телефон отправителя">[phone]</button>
                            <button type="button" class="wtn-insert-message-tag" data-tag="[email]" title="Email.">[email]</button>
                            <button type="button" class="wtn-insert-message-tag" data-tag="[message]" title="Сообщение">[message]</button>
                            <button type="button" class="wtn-insert-message-tag" data-tag="[consent]" title="Чекбокс, Согласие с политикой">[consent]</button>
                            <button type="button" id="wtn-msg-undo-btn" style="margin-left: 16px;">↩️ Отменить</button>
                            <button type="button" id="wtn-msg-redo-btn">↪️ Повторить</button>
                        </div>


                        <form method="post" action="options.php">
		                    <?php
		                    settings_fields('wtn_message_group');
		                    $message_template = get_option('wtn_custom_message_template');
		                    if ($message_template === false || trim($message_template) === '') {
			                    $message_template = "📩 *Новое сообщение с сайта*\n\n👤 Имя: [name]\n📱 Телефон: [phone]\n💬 Сообщение: [message]";
		                    }
		                    ?>
                            <textarea id="wtn-message-editor" name="wtn_custom_message_template" rows="10" style="width:100%; font-family: monospace;"><?php echo esc_textarea($message_template); ?></textarea>
		                    <?php submit_button('Сохранить шаблон сообщения'); ?>
                        </form>
                    </div>
                </div>
            </div>
		</div>

		<div class="postbox-container" style="width: 28%;">
			<div class="meta-box-sortables">
				<div class="postbox">
					<h2 class="hndle">Информация</h2>
					<div class="inside">
						<p><strong>Плагин:</strong> WP Telegram Notification</p>
						<p><a href=" https://github.com/JS-Mironov-BIAR/wp-telegram-notification.git" target="_blank">Исходный код на GitHub</a></p>
						<p><a href=" https://github.com/JS-Mironov-BIAR/wp-custom-modal.git" target="_blank">Исходный код плагина модальных окон</a></p>
						<p>Поддержка множественных chat ID</p>
					</div>
				</div>

				<div class="postbox">
					<h2 class="hndle">Памятка</h2>
					<div class="inside">
						<ul>
							<li>Установите <strong>Custom Modal Controller</strong></li>
							<li>Укажите токен и chat_id</li>
							<li>Вставьте шорткод: <code>[wtn_feedback_form]</code></li>
						</ul>
					</div>
				</div>

                <div class="postbox">
                    <h2 class="hndle">🧠 Памятка по редакторам</h2>
                    <div class="inside">
                        <strong>Редактор HTML-формы:</strong>
                        <ul style="margin-top: 4px; padding-left: 20px; list-style: disc;">
                            <li><kbd>Ctrl</kbd> + <kbd>Z</kbd> — отменить</li>
                            <li><kbd>Ctrl</kbd> + <kbd>Y</kbd> или <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>Z</kbd> — повторить</li>
                            <li>Кнопки ↩️ и ↪️ = Undo / Redo</li>
                            <li>Кликайте по [text], [tel] и т.д. — вставка полей</li>
                            <li>Предпросмотр — только структура, не стили</li>
                        </ul>

                        <hr style="margin: 12px 0;">

                        <strong>Шаблон сообщения:</strong>
                        <ul style="margin-top: 4px; padding-left: 20px; list-style: disc;">
                            <li>Переменные: <code>[name]</code>, <code>[phone]</code>, <code>[message]</code></li>
                            <li>Кликайте по переменным для вставки</li>
                            <li><kbd>Ctrl</kbd> + <kbd>Z</kbd> / <kbd>Y</kbd> также работают</li>
                            <li>Вы можете менять порядок и добавлять свои поля</li>
                        </ul>
                    </div>
                </div>

            </div>
		</div>
	</div>

	<script>
        document.addEventListener('DOMContentLoaded', () => {
            const notices = document.querySelectorAll('.notice.is-dismissible')
            notices.forEach((notice) => {
                setTimeout(() => {
                    notice.style.transition = 'opacity 0.5s ease'
                    notice.style.opacity = '0'
                    setTimeout(() => notice.remove(), 500)
                }, 4000)
            })
        })
	</script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const textarea = document.getElementById('wtn-form-editor')
            const preview = document.getElementById('wtn-form-preview')
            const insertButtons = document.querySelectorAll('.wtn-insert-tag')
            const undoBtn = document.getElementById('wtn-undo-btn')
            const redoBtn = document.getElementById('wtn-redo-btn')

            let historyStack = []
            let redoStack = []

            /**
             * Парсит текст шаблона и преобразует кастомные теги в HTML с поддержкой label и checkbox
             * @param {string} text
             * @returns {string}
             */
            const parseTemplate = (text) => {
                const wrapWithLabel = (tag, attrs, isTextarea = false, isCheckbox = false) => {
                    const labelMatch = attrs.match(/label="([^"]+)"/)
                    const label = labelMatch ? labelMatch[1] : null

                    const isRequired = /required(?:\s*=\s*"?(true|1)?"?)?/.test(attrs)
                    const requiredMark = isRequired ? '<span style="color:#e11d48; margin-left:4px;">*</span>' : ''
                    const requiredAttr = isRequired ? ' aria-required="true"' : ''

                    const cleanAttrs = attrs.replace(/label="[^"]*"/, '').trim()

                    let inputHtml
                    if (isTextarea) {
                        inputHtml = `<textarea ${cleanAttrs}${requiredAttr}></textarea>`
                    } else if (isCheckbox) {
                        inputHtml = `<input type="checkbox" ${cleanAttrs}${requiredAttr}>`
                    } else {
                        inputHtml = `<input ${tag} ${cleanAttrs}${requiredAttr}>`
                    }

                    return label
                        ? `<div style="margin-bottom:10px;"><label style="display:flex;flex-direction:column;gap:4px;"><span>${label}${requiredMark}</span>${inputHtml}</label></div>`
                        : `<div style="margin-bottom:10px;">${inputHtml}</div>`
                }

                return text
                    .replace(/\[text (.*?)\]/g, (_, attrs) => wrapWithLabel('type="text"', attrs))
                    .replace(/\[tel (.*?)\]/g, (_, attrs) => wrapWithLabel('type="tel"', attrs))
                    .replace(/\[email (.*?)\]/g, (_, attrs) => wrapWithLabel('type="email"', attrs))
                    .replace(/\[textarea (.*?)\]/g, (_, attrs) => wrapWithLabel('', attrs, true))
                    .replace(/\[checkbox (.*?)\]/g, (_, attrs) => wrapWithLabel('', attrs, false, true))
                    .replace(/\[send (.*?)\]/g, (_, attrs) => {
                        const textMatch = attrs.match(/text="([^"]+)"/)
                        const btnText = textMatch ? textMatch[1] : 'Отправить'
                        return `<div style="margin-bottom:10px;"><button ${attrs}>${btnText}</button></div>`
                    })
            }

            const updatePreview = () => {
                preview.innerHTML = parseTemplate(textarea.value)
            }

            const saveToHistory = () => {
                const current = textarea.value
                if (historyStack.length === 0 || historyStack[historyStack.length - 1] !== current) {
                    historyStack.push(current)
                    redoStack = []
                }
            }

            textarea.addEventListener('input', () => {
                saveToHistory()
                updatePreview()
            })

            insertButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault()
                    saveToHistory()
                    const tag = btn.dataset.tag
                    const start = textarea.selectionStart
                    const end = textarea.selectionEnd
                    const current = textarea.value

                    textarea.value = current.slice(0, start) + tag + current.slice(end)
                    textarea.focus()
                    textarea.setSelectionRange(start + tag.length, start + tag.length)
                    textarea.dispatchEvent(new Event('input'))
                })
            })

            if (undoBtn) {
                undoBtn.addEventListener('click', (e) => {
                    e.preventDefault()
                    if (historyStack.length > 1) {
                        redoStack.push(historyStack.pop())
                        textarea.value = historyStack[historyStack.length - 1]
                        textarea.dispatchEvent(new Event('input'))
                    }
                })
            }

            if (redoBtn) {
                redoBtn.addEventListener('click', (e) => {
                    e.preventDefault()
                    if (redoStack.length > 0) {
                        const next = redoStack.pop()
                        historyStack.push(next)
                        textarea.value = next
                        textarea.dispatchEvent(new Event('input'))
                    }
                })
            }

            // Ctrl+Z / Ctrl+Y горячие клавиши
            textarea.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
                    e.preventDefault()
                    undoBtn?.click()
                }
                if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.shiftKey && e.key === 'Z'))) {
                    e.preventDefault()
                    redoBtn?.click()
                }
            })

            // Init
            saveToHistory()
            updatePreview()
        })
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const msgTextarea = document.getElementById('wtn-message-editor')
            const msgInsertButtons = document.querySelectorAll('.wtn-insert-message-tag')
            const msgUndoBtn = document.getElementById('wtn-msg-undo-btn')
            const msgRedoBtn = document.getElementById('wtn-msg-redo-btn')

            let msgHistory = []
            let msgRedoStack = []

            const saveMsgHistory = () => {
                const val = msgTextarea.value
                if (msgHistory.length === 0 || msgHistory[msgHistory.length - 1] !== val) {
                    msgHistory.push(val)
                    msgRedoStack = []
                }
            }

            const updateMsg = () => {
                saveMsgHistory()
            }

            msgTextarea.addEventListener('input', updateMsg)

            msgInsertButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault()
                    saveMsgHistory()
                    const tag = btn.dataset.tag
                    const start = msgTextarea.selectionStart
                    const end = msgTextarea.selectionEnd
                    const current = msgTextarea.value
                    msgTextarea.value = current.slice(0, start) + tag + current.slice(end)
                    msgTextarea.focus()
                    msgTextarea.setSelectionRange(start + tag.length, start + tag.length)
                    msgTextarea.dispatchEvent(new Event('input'))
                })
            })

            if (msgUndoBtn) {
                msgUndoBtn.addEventListener('click', (e) => {
                    e.preventDefault()
                    if (msgHistory.length > 1) {
                        msgRedoStack.push(msgHistory.pop())
                        msgTextarea.value = msgHistory[msgHistory.length - 1]
                        msgTextarea.dispatchEvent(new Event('input'))
                    }
                })
            }

            if (msgRedoBtn) {
                msgRedoBtn.addEventListener('click', (e) => {
                    e.preventDefault()
                    if (msgRedoStack.length > 0) {
                        const next = msgRedoStack.pop()
                        msgHistory.push(next)
                        msgTextarea.value = next
                        msgTextarea.dispatchEvent(new Event('input'))
                    }
                })
            }

            // Горячие клавиши
            msgTextarea.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
                    e.preventDefault()
                    msgUndoBtn?.click()
                }
                if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.shiftKey && e.key === 'Z'))) {
                    e.preventDefault()
                    msgRedoBtn?.click()
                }
            })

            // Инициализация
            saveMsgHistory()
        })
    </script>


    <style>
        .metabox-holder {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .postbox-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
    </style>

</div>
