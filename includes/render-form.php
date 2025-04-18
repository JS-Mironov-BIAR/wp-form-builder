<?php

/**
 * Преобразует шаблон формы в HTML
 *
 * @param string $template
 * @return string
 */
function wfb_render_form_template(string $template): string {
	$wrap_with_label = static function (string $tag, array $matches, bool $isTextarea = false): string {
		$attrs = $matches[1];

		preg_match('/label="([^"]+)"/', $attrs, $labelMatch);
		$label = $labelMatch[1] ?? null;

		$isRequired = str_contains($attrs, 'required') || str_contains($attrs, 'required="true"');
		$requiredMark = $isRequired ? ' <span class="wfb-required">*</span>' : '';

		$attrs = preg_replace('/label="[^"]*"/', '', $attrs);

		$input = $isTextarea
			? "<textarea $attrs></textarea>"
			: "<input $tag $attrs>";

		return $label
			? "<label><span>$label$requiredMark</span>$input</label>"
			: $input;
	};

	$template = preg_replace_callback('/\[text (.*?)\]/', fn($m) => $wrap_with_label('type="text"', $m), $template);
	$template = preg_replace_callback('/\[tel (.*?)\]/', fn($m) => $wrap_with_label('type="tel"', $m), $template);
	$template = preg_replace_callback('/\[email (.*?)\]/', fn($m) => $wrap_with_label('type="email"', $m), $template);
	$template = preg_replace_callback('/\[textarea (.*?)\]/', fn($m) => $wrap_with_label('', $m, true), $template);

	$template = preg_replace_callback('/\[checkbox (.*?)\]/', static function ($matches) {
		$attrs = $matches[1];

		// 1. Забираем label
		preg_match('/label="([^"]*)"/', $attrs, $labelMatch);
		$label = $labelMatch[1] ?? '';

		// 2. Забираем href
		preg_match('/href="([^"]*)"/', $attrs, $hrefMatch);
		$href = $hrefMatch[1] ?? '';

		$isRequired = str_contains($attrs, 'required') || str_contains($attrs, 'required="true"');
		$requiredMark = $isRequired ? ' <span class="wfb-required">*</span>' : '';

		// Убираем label и href из атрибутов
		$attrs = preg_replace('/(label|href)="[^"]*"/', '', $attrs);

		// Если ссылка есть — оборачиваем label в <a>
		if ($href) {
			$labelHtml = '<a href="' . esc_url($href) . '" target="_blank" rel="noopener noreferrer">' . esc_html($label) . '</a>';
		} else {
			$labelHtml = esc_html($label);
		}

		return $label
			? "<label><input type=\"checkbox\" $attrs><span>$labelHtml$requiredMark</span></label>"
			: "<input type=\"checkbox\" $attrs>";
	}, $template);

	$template = preg_replace_callback('/\[select (.*?)\]/', static function ($matches) {
		$attrs = $matches[1];

		preg_match('/items="([^"]+)"/', $attrs, $itemsMatch);
		preg_match('/label="([^"]+)"/', $attrs, $labelMatch);
		preg_match('/default="([^"]+)"/', $attrs, $defaultMatch);
		preg_match('/name="([^"]+)"/', $attrs, $nameMatch);

		$label = $labelMatch[1] ?? '';
		$defaultOption = $defaultMatch[1] ?? 'Выберите вариант';
		$name = $nameMatch[1] ?? 'select'; // <-- если имя не указано, ставим "select"

		$optionsHtml = '<option value="">' . esc_html($defaultOption) . '</option>'; // первая пустая всегда

		if (!empty($itemsMatch[1])) {
			$items = explode(',', $itemsMatch[1]);
			foreach ($items as $item) {
				$item = trim($item);
				$optionsHtml .= '<option value="' . esc_attr($item) . '">' . esc_html($item) . '</option>';
			}
		}

		// Убираем items, label и default из атрибутов
		$attrs = preg_replace('/(items|label|default)="[^"]*"/', '', $attrs);

		// Формируем select + скрытое поле hidden
		$selectHtml = '<select ' . $attrs . ' class="wfb-select" data-target-hidden="' . esc_attr($name) . '">' . $optionsHtml . '</select>';
		$hiddenHtml = '<input type="hidden" name="' . esc_attr($name) . '" value="">';
		$extraContentHtml = '<div class="wfb-select-extra-content" style="display:none;" data-content=""></div>';

		// Собираем вывод
		return $label
			? "<label class=\"wfb-label\"><span>$label</span>$selectHtml$hiddenHtml$extraContentHtml</label>"
			: $selectHtml . $hiddenHtml . $extraContentHtml;
	}, $template);


	$template = preg_replace_callback('/\[send (.*?)\]/', static function ($matches) {
		preg_match('/text="(.*?)"/', $matches[1], $textMatch);
		$text = $textMatch[1] ?? 'Отправить';
		return '<button ' . $matches[1] . '>' . esc_html($text) . '</button>';
	}, $template);

	return $template;
}
