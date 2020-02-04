# CMB2 Field Type: Select2

## Description

[Select2](https://select2.github.io/) field type for [CMB2](https://github.com/WebDevStudios/CMB2 "Custom Metaboxes and Fields for WordPress 2").

This plugin gives you two additional field types based on Select2:

1. The `select2` field acts much like the default `select` field. However, it adds typeahead-style search allowing you to quickly make a selection from a large list
2. The `multiselect2` field allows you to select multiple values with typeahead-style search. The values can be dragged and dropped to reorder

## Usage

`select2` - Select box with with typeahead-style search. Example:
```php
$cmb->add_field( array(
	'name'    => 'Cooking time',
	'id'      => $prefix . 'cooking_time',
	'desc'    => 'Cooking time',
	'type'    => 'select2',
	'options' => array(
		'5'  => '5 minutes',
		'10' => '10 minutes',
		'30' => 'Half an hour',
		'60' => '1 hour',
	),
) );

```

`multiselect2` - Multi-value select box with drag and drop reordering. Example:
```php
$cmb->add_field( array(
	'name'    => 'Ingredients',
	'id'      => $prefix . 'ingredients',
	'desc'    => 'Select ingredients. Drag to reorder.',
	'type'    => 'multiselect2',
	'options' => array(
		'flour'  => 'Flour',
		'salt'   => 'Salt',
		'eggs'   => 'Eggs',
		'milk'   => 'Milk',
		'butter' => 'Butter',
	),
) );
```

### Placeholder

You can specify placeholder text through the attributes array. Example:
```php
$cmb->add_field( array(
	'name'    => 'Ingredients',
	'id'      => $prefix . 'ingredients',
	'desc'    => 'Select this recipes ingredients.',
	'type'    => 'multiselect2',
	'options' => array(
		'flour'  => 'Flour',
		'salt'   => 'Salt',
		'eggs'   => 'Eggs',
		'milk'   => 'Milk',
		'butter' => 'Butter',
	),
	'attributes' => array(
		'placeholder' => 'Select ingredients. Drag to reorder'
	),
) );
```

### Custom Select2 configuration and overriding default configuration options

You can define Select2 configuration options using HTML5 `data-*` attributes. It's worth reading up on the [available options](https://select2.github.io/options.html#data-attributes) over on the Select2 website. Example:
```php
$cmb->add_field( array(
	'name'    => 'Ingredients',
	'id'      => $prefix . 'ingredients',
	'desc'    => 'Select ingredients. Drag to reorder.',
	'type'    => 'multiselect2',
	'options' => array(
		'flour'  => 'Flour',
		'salt'   => 'Salt',
		'eggs'   => 'Eggs',
		'milk'   => 'Milk',
		'butter' => 'Butter',
	),
	'attributes' => array(
		'data-maximum-selection-length' => '2',
	),
) );
```
