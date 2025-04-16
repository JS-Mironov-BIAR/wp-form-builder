<?php

/**
 * Renders the form template by replacing custom shortcodes with HTML
 * and wraps supported elements with <label> if label="..." is set,
 * including required support.
 *
 * @param string $template
 * @return string
 */
function wtn_render_form_template(string $template): string {
	/**
	 * Wraps input/textarea in label with optional <span> and required support.
	 *
	 * @param string $tag Input type (e.g., type="text") or "" for textarea
	 * @param array $matches preg_match result
	 * @param bool $isTextarea
	 * @return string
	 */
	$wrap_with_label = static function (string $tag, array $matches, bool $isTextarea = false): string {
		$attrs = $matches[1];

		preg_match('/label="([^"]+)"/', $attrs, $labelMatch);
		$label = $labelMatch[1] ?? null;

		$isRequired = str_contains($attrs, 'required') || str_contains($attrs, 'required="true"');
		$requiredMark = $isRequired ? ' <span class="wtn-required">*</span>' : '';

		$attrs = preg_replace('/label="[^"]*"/', '', $attrs); // remove label from attributes

		$input = $isTextarea
			? "<textarea $attrs></textarea>"
			: "<input $tag $attrs>";

		return $label
			? "<label><span>$label$requiredMark</span>$input</label>"
			: ($isRequired
				? "<div><span class='wtn-label-inline'>* Обязательное поле</span>$input</div>"
				: $input);
	};

	$template = preg_replace_callback('/\[text (.*?)\]/', fn($m) => $wrap_with_label('type="text"', $m), $template);
	$template = preg_replace_callback('/\[tel (.*?)\]/', fn($m) => $wrap_with_label('type="tel"', $m), $template);
	$template = preg_replace_callback('/\[email (.*?)\]/', fn($m) => $wrap_with_label('type="email"', $m), $template);
	$template = preg_replace_callback('/\[textarea (.*?)\]/', fn($m) => $wrap_with_label('', $m, true), $template);

	// Checkbox — inside the label, but with the required asterisk
	$template = preg_replace_callback('/\[checkbox (.*?)\]/', static function ($matches) {
		$attrs = $matches[1];

		preg_match('/label="([^"]+)"/', $attrs, $labelMatch);
		$label = $labelMatch[1] ?? '';

		$isRequired = str_contains($attrs, 'required') || str_contains($attrs, 'required="true"');
		$requiredMark = $isRequired ? ' <span class="wtn-required">*</span>' : '';

		$attrs = preg_replace('/label="[^"]*"/', '', $attrs);

		return $label
			? "<label><input type=\"checkbox\" $attrs><span>$label$requiredMark</span></label>"
			: "<input type=\"checkbox\" $attrs>";
	}, $template);

	// Submit button
	$template = preg_replace_callback('/\[send (.*?)\]/', static function ($matches) {
		preg_match('/text="(.*?)"/', $matches[1], $textMatch);
		$text = $textMatch[1] ?? 'Отправить';
		return '<button ' . $matches[1] . '>' . esc_html($text) . '</button>';
	}, $template);

	return $template;
}
