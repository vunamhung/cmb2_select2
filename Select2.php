<?php

namespace vunamhung\cmb2;

class Select2 {
	const SELECT2_VERSION = '4.1.13';

	public function __construct() {
		add_action('admin_enqueue_scripts', [$this, 'enqueue']);

		add_filter('cmb2_render_select2', [$this, 'render_select2'], 10, 5);
		add_filter('cmb2_render_multiselect2', [$this, 'render_multiselect2'], 10, 5);
		add_filter('cmb2_sanitize_multiselect2', [$this, 'multiselect2_sanitize'], 10, 4);
		add_filter('cmb2_types_esc_multiselect2', [$this, 'multiselect2_escaped_value'], 10, 3);
		add_filter('cmb2_repeat_table_row_types', [$this, 'multiselect2_table_row_class'], 10, 1);
	}

	public function enqueue() {
		wp_enqueue_style('select2', $this->dir_url('libs/select2/select2.min.css'), [], self::SELECT2_VERSION);
		wp_register_script('select2', $this->dir_url('libs/select2/select2.full.min.js'), [], self::SELECT2_VERSION);

		wp_enqueue_style('cmb2-select2', $this->dir_url('css/style.css'), [], '1.0.0');
		wp_enqueue_script('cmb2-select2', $this->dir_url('js/script.js'), ['jquery', 'select2'], '1.0.0');
	}

	public function render_select2($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
		if (version_compare(CMB2_VERSION, '2.2.2', '>=')) {
			$field_type_object->type = new \CMB2_Type_Select($field_type_object);
		}

		echo $field_type_object->select([
			'class' => 'vnh_select2 vnh_select',
			'desc' => $field_type_object->_desc(true),
			'options' => sprintf('<option>%s</option>%s', __('Select and option', 'vnh_textdomain'), $field_type_object->concat_items()),
			'data-placeholder' => $field->args('attributes', 'placeholder') ?: $field->args('description'),
		]);
	}

	public function render_multiselect2($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
		if (version_compare(CMB2_VERSION, '2.2.2', '>=')) {
			$field_type_object->type = new \CMB2_Type_Select($field_type_object);
		}

		$a = $field_type_object->parse_args('vnh_multiselect', [
			'multiple' => 'multiple',
			'style' => 'width: 99%',
			'class' => 'vnh_select2 vnh_multiselect',
			'name' => $field_type_object->_name() . '[]',
			'id' => $field_type_object->_id(),
			'desc' => $field_type_object->_desc(true),
			'options' => $this->get_multiselect_options($field_escaped_value, $field_type_object),
			'data-placeholder' => $field->args('attributes', 'placeholder') ?: $field->args('description'),
		]);

		$attrs = $field_type_object->concat_attrs($a, ['desc', 'options']);

		printf('<select %s>%s</select>%s', $attrs, $a['options'], $a['desc']);
	}

	public function get_multiselect_options($field_escaped_value = [], $field_type_object) {
		$options = (array) $field_type_object->field->options();

		// If we have selected items, we need to preserve their order
		if (!empty($field_escaped_value)) {
			$options = $this->sort_array_by_array($options, $field_escaped_value);
		}

		$selected_items = '';
		$other_items = '';

		foreach ($options as $option_value => $option_label) {
			// Clone args & modify for just this item
			$option = [
				'value' => $option_value,
				'label' => $option_label,
			];

			// Split options into those which are selected and the rest
			if (in_array($option_value, (array) $field_escaped_value)) {
				$option['checked'] = true;
				$selected_items .= $field_type_object->select_option($option);
			} else {
				$other_items .= $field_type_object->select_option($option);
			}
		}

		return $selected_items . $other_items;
	}

	protected function sort_array_by_array(array $array, array $order_array) {
		$ordered = [];

		foreach ($order_array as $key) {
			if (array_key_exists($key, $array)) {
				$ordered[$key] = $array[$key];
				unset($array[$key]);
			}
		}

		return $ordered + $array;
	}

	public function multiselect2_sanitize($check, $meta_value, $object_id, $field_args) {
		if (!is_array($meta_value) || !$field_args['repeatable']) {
			return $check;
		}

		foreach ($meta_value as $key => $val) {
			$meta_value[$key] = array_map('sanitize_text_field', $val);
		}

		return $meta_value;
	}

	public function multiselect2_escaped_value($check, $meta_value, $field_args) {
		if (!is_array($meta_value) || !$field_args['repeatable']) {
			return $check;
		}

		foreach ($meta_value as $key => $val) {
			$meta_value[$key] = array_map('esc_attr', $val);
		}

		return $meta_value;
	}

	public function multiselect2_table_row_class($check) {
		$check[] = 'vnh_multiselect';

		return $check;
	}

	protected function dir_url($path) {
		return plugin_dir_url(__FILE__) . $path;
	}
}

new Select2();
