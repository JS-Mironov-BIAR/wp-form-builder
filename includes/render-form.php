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
		preg_match('/label="([^"]+)"/', $attrs, $labelMatch);
		$label = $labelMatch[1] ?? '';

		$isRequired = str_contains($attrs, 'required') || str_contains($attrs, 'required="true"');
		$requiredMark = $isRequired ? ' <span class="wfb-required">*</span>' : '';

		$attrs = preg_replace('/label="[^"]*"/', '', $attrs);

		return $label
			? "<label><input type=\"checkbox\" $attrs><span>$label$requiredMark</span></label>"
			: "<input type=\"checkbox\" $attrs>";
	}, $template);

	$template = preg_replace_callback('/\[select (.*?)\]/', static function ($matches) {
		$attrs = $matches[1];

		preg_match('/items="([^"]+)"/', $attrs, $itemsMatch);
		preg_match('/label="([^"]+)"/', $attrs, $labelMatch);
		preg_match('/default="([^"]+)"/', $attrs, $defaultMatch);

		$label = $labelMatch[1] ?? '';
		$defaultOption = $defaultMatch[1] ?? 'Выберите вариант';

		$optionsHtml = '<option value="">' . esc_html($defaultOption) . '</option>'; // ➔ всегда первая опция пустая

		if (!empty($itemsMatch[1])) {
			$items = explode(',', $itemsMatch[1]);
			foreach ($items as $item) {
				$item = trim($item);
				$optionsHtml .= '<option value="' . esc_attr($item) . '">' . esc_html($item) . '</option>';
			}
		}

		// Убираем items, label и default из атрибутов
		$attrs = preg_replace('/(items|label|default)="[^"]*"/', '', $attrs);

		// Строим сам <select> + пустой блок для дополнительного контента
		$selectHtml = '<select ' . $attrs . ' class="wfb-select">' . $optionsHtml . '</select>';
		$extraContentHtml = '<div class="wfb-select-extra-content" style="display:none;" data-content=""></div>';


		// Собираем итоговую разметку
		return $label
			? "<label class=\"wfb-label\"><span>$label</span>$selectHtml$extraContentHtml</label>"
			: $selectHtml . $extraContentHtml;
	}, $template);


	$template = preg_replace_callback('/\[send (.*?)\]/', static function ($matches) {
		preg_match('/text="(.*?)"/', $matches[1], $textMatch);
		$text = $textMatch[1] ?? 'Отправить';
		return '<button ' . $matches[1] . '>' . esc_html($text) . '</button>';
	}, $template);

	return $template;
}
