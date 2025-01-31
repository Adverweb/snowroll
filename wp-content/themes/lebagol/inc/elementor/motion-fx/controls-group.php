<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;
use \Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Lebagol_Elementor_Control_MotionFX extends Group_Control_Base {

	protected static $fields;

	/**
	 * Get group control type.
	 *
	 * Retrieve the group control type.
	 *
	 * @since  2.5.0
	 * @access public
	 * @static
	 */
	public static function get_type() {
		return 'motion_fx';
	}

	/**
	 * Init fields.
	 *
	 * Initialize group control fields.
	 *
	 * @since  2.5.0
	 * @access protected
	 */
	protected function init_fields() {
		$fields = [
			'motion_fx_scrolling' => [
				'label' => esc_html__( 'Scrolling Effects', 'lebagol' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'lebagol' ),
				'label_on' => esc_html__( 'On', 'lebagol' ),
				'render_type' => 'ui',
				'frontend_available' => true,
			],
		];

		$this->prepare_effects( 'scrolling', $fields );

		$transform_origin_conditions = [
			'terms' => [
				[
					'name' => 'motion_fx_scrolling',
					'value' => 'yes',
				],
				[
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'rotateZ_effect',
							'value' => 'yes',
						],
						[
							'name' => 'scale_effect',
							'value' => 'yes',
						],
					],
				],
			],
		];

		if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {

			$css_transform_controls = [
				[
					'name' => 'transform_x_anchor_point',
					'value' => '',
				],
				[
					'name' => 'transform_y_anchor_point',
					'value' => '',
				],
			];

			$transform_origin_conditions['terms'] = array_merge( $transform_origin_conditions['terms'], $css_transform_controls );
		}

		$fields['transform_origin_x'] = [
			'label' => esc_html__( 'X Anchor Point', 'lebagol' ),
			'type' => Controls_Manager::CHOOSE,
			'default' => 'center',
			'options' => [
				'left' => [
					'title' => esc_html__( 'Left', 'lebagol' ),
					'icon' => 'eicon-h-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'lebagol' ),
					'icon' => 'eicon-h-align-center',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'lebagol' ),
					'icon' => 'eicon-h-align-right',
				],
			],
			'conditions' => $transform_origin_conditions,
			'toggle' => false,
			'render_type' => 'ui',
			'selectors' => [
				'{{SELECTOR}}' => '--e-transform-origin-x: {{VALUE}}',
			],
		];

		$fields['transform_origin_y'] = [
			'label' => esc_html__( 'Y Anchor Point', 'lebagol' ),
			'type' => Controls_Manager::CHOOSE,
			'default' => 'center',
			'options' => [
				'top' => [
					'title' => esc_html__( 'Top', 'lebagol' ),
					'icon' => 'eicon-v-align-top',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'lebagol' ),
					'icon' => 'eicon-v-align-middle',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'lebagol' ),
					'icon' => 'eicon-v-align-bottom',
				],
			],
			'conditions' => $transform_origin_conditions,
			'selectors' => [
				'{{SELECTOR}}' => '--e-transform-origin-y: {{VALUE}}',
			],
			'toggle' => false,
		];

		// TODO: Once Core 3.4.0 is out, get the active devices using Breakpoints/Manager::get_active_devices_list().
		$active_breakpoint_instances = Plugin::instance()->breakpoints->get_active_breakpoints();
		// Devices need to be ordered from largest to smallest.
		$active_devices = array_reverse( array_keys( $active_breakpoint_instances ) );

		// Add desktop in the correct position.
		if ( in_array( 'widescreen', $active_devices, true ) ) {
			$active_devices = array_merge( array_slice( $active_devices, 0, 1 ), [ 'desktop' ], array_slice( $active_devices, 1 ) );
		} else {
			$active_devices = array_merge( [ 'desktop' ], $active_devices );
		}

		$devices_options = [];

		foreach ( $active_devices as $device_key ) {
			$device_label = 'desktop' === $device_key ? esc_html__( 'Desktop', 'lebagol' ) : $active_breakpoint_instances[ $device_key ]->get_label();

			$devices_options[ $device_key ] = $device_label;
		}

		$fields['devices'] = [
			'label' => esc_html__( 'Apply Effects On', 'lebagol' ),
			'type' => Controls_Manager::SELECT2,
			'multiple' => true,
			'label_block' => true,
			'default' => $active_devices,
			'options' => $devices_options,
			'condition' => [
				'motion_fx_scrolling' => 'yes',
			],
			'render_type' => 'none',
			'frontend_available' => true,
		];

		$fields['range'] = [
			'label' => esc_html__( 'Effects Relative To', 'lebagol' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'' => esc_html__( 'Default', 'lebagol' ),
				'viewport' => esc_html__( 'Viewport', 'lebagol' ),
				'page' => esc_html__( 'Entire Page', 'lebagol' ),
			],
			'condition' => [
				'motion_fx_scrolling' => 'yes',
			],
			'render_type' => 'none',
			'frontend_available' => true,
		];

		$fields['motion_fx_mouse'] = [
			'label' => esc_html__( 'Mouse Effects', 'lebagol' ),
			'type' => Controls_Manager::SWITCHER,
			'label_off' => esc_html__( 'Off', 'lebagol' ),
			'label_on' => esc_html__( 'On', 'lebagol' ),
			'separator' => 'before',
			'render_type' => 'none',
			'frontend_available' => true,
		];

		$this->prepare_effects( 'mouse', $fields );

		return $fields;
	}

	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}

	private function get_scrolling_effects() {
		return [
			'translateY' => [
				'label' => esc_html__( 'Vertical Scroll', 'lebagol' ),
				'fields' => [
					'direction' => [
						'label' => esc_html__( 'Direction', 'lebagol' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'' => esc_html__( 'Up', 'lebagol' ),
							'negative' => esc_html__( 'Down', 'lebagol' ),
						],
					],
					'speed' => [
						'label' => esc_html__( 'Speed', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 4,
						],
						'range' => [
							'px' => [
								'max' => 10,
								'step' => 0.1,
							],
						],
					],
					'affectedRange' => [
						'label' => esc_html__( 'Viewport', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'sizes' => [
								'start' => 0,
								'end' => 100,
							],
							'unit' => '%',
						],
						'labels' => [
							__( 'Bottom', 'lebagol' ),
							__( 'Top', 'lebagol' ),
						],
						'scales' => 1,
						'handles' => 'range',
					],
				],
			],
			'translateX' => [
				'label' => esc_html__( 'Horizontal Scroll', 'lebagol' ),
				'fields' => [
					'direction' => [
						'label' => esc_html__( 'Direction', 'lebagol' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'' => esc_html__( 'To Left', 'lebagol' ),
							'negative' => esc_html__( 'To Right', 'lebagol' ),
						],
					],
					'speed' => [
						'label' => esc_html__( 'Speed', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 4,
						],
						'range' => [
							'px' => [
								'max' => 10,
								'step' => 0.1,
							],
						],
					],
					'affectedRange' => [
						'label' => esc_html__( 'Viewport', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'sizes' => [
								'start' => 0,
								'end' => 100,
							],
							'unit' => '%',
						],
						'labels' => [
							__( 'Bottom', 'lebagol' ),
							__( 'Top', 'lebagol' ),
						],
						'scales' => 1,
						'handles' => 'range',
					],
				],
			],
			'opacity' => [
				'label' => esc_html__( 'Transparency', 'lebagol' ),
				'fields' => [
					'direction' => [
						'label' => esc_html__( 'Direction', 'lebagol' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'out-in',
						'options' => [
							'out-in' => 'Fade In',
							'in-out' => 'Fade Out',
							'in-out-in' => 'Fade Out In',
							'out-in-out' => 'Fade In Out',
						],
					],
					'level' => [
						'label' => esc_html__( 'Level', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 10,
						],
						'range' => [
							'px' => [
								'min' => 1,
								'max' => 10,
								'step' => 0.1,
							],
						],
					],
					'range' => [
						'label' => esc_html__( 'Viewport', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'sizes' => [
								'start' => 20,
								'end' => 80,
							],
							'unit' => '%',
						],
						'labels' => [
							__( 'Bottom', 'lebagol' ),
							__( 'Top', 'lebagol' ),
						],
						'scales' => 1,
						'handles' => 'range',
					],
				],
			],
			'blur' => [
				'label' => esc_html__( 'Blur', 'lebagol' ),
				'fields' => [
					'direction' => [
						'label' => esc_html__( 'Direction', 'lebagol' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'out-in',
						'options' => [
							'out-in' => 'Fade In',
							'in-out' => 'Fade Out',
							'in-out-in' => 'Fade Out In',
							'out-in-out' => 'Fade In Out',
						],
					],
					'level' => [
						'label' => esc_html__( 'Level', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 7,
						],
						'range' => [
							'px' => [
								'min' => 1,
								'max' => 15,
							],
						],
					],
					'range' => [
						'label' => esc_html__( 'Viewport', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'sizes' => [
								'start' => 20,
								'end' => 80,
							],
							'unit' => '%',
						],
						'labels' => [
							__( 'Bottom', 'lebagol' ),
							__( 'Top', 'lebagol' ),
						],
						'scales' => 1,
						'handles' => 'range',
					],
				],
			],
			'rotateZ' => [
				'label' => esc_html__( 'Rotate', 'lebagol' ),
				'fields' => [
					'direction' => [
						'label' => esc_html__( 'Direction', 'lebagol' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'' => esc_html__( 'To Left', 'lebagol' ),
							'negative' => esc_html__( 'To Right', 'lebagol' ),
						],
					],
					'speed' => [
						'label' => esc_html__( 'Speed', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 1,
						],
						'range' => [
							'px' => [
								'max' => 10,
								'step' => 0.1,
							],
						],
					],
					'affectedRange' => [
						'label' => esc_html__( 'Viewport', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'sizes' => [
								'start' => 0,
								'end' => 100,
							],
							'unit' => '%',
						],
						'labels' => [
							__( 'Bottom', 'lebagol' ),
							__( 'Top', 'lebagol' ),
						],
						'scales' => 1,
						'handles' => 'range',
					],
				],
			],
			'scale' => [
				'label' => esc_html__( 'Scale', 'lebagol' ),
				'fields' => [
					'direction' => [
						'label' => esc_html__( 'Direction', 'lebagol' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'out-in',
						'options' => [
							'out-in' => 'Scale Up',
							'in-out' => 'Scale Down',
							'in-out-in' => 'Scale Down Up',
							'out-in-out' => 'Scale Up Down',
						],
					],
					'speed' => [
						'label' => esc_html__( 'Speed', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 4,
						],
						'range' => [
							'px' => [
								'min' => -10,
								'max' => 10,
							],
						],
					],
					'range' => [
						'label' => esc_html__( 'Viewport', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'sizes' => [
								'start' => 20,
								'end' => 80,
							],
							'unit' => '%',
						],
						'labels' => [
							__( 'Bottom', 'lebagol' ),
							__( 'Top', 'lebagol' ),
						],
						'scales' => 1,
						'handles' => 'range',
					],
				],
			],
		];
	}

	private function get_mouse_effects() {
		return [
			'mouseTrack' => [
				'label' => esc_html__( 'Mouse Track', 'lebagol' ),
				'fields' => [
					'direction' => [
						'label' => esc_html__( 'Direction', 'lebagol' ),
						'type' => Controls_Manager::SELECT,
						'default' => '',
						'options' => [
							'' => esc_html__( 'Opposite', 'lebagol' ),
							'negative' => esc_html__( 'Direct', 'lebagol' ),
						],
					],
					'speed' => [
						'label' => esc_html__( 'Speed', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 1,
						],
						'range' => [
							'px' => [
								'max' => 10,
								'step' => 0.1,
							],
						],
					],
				],
			],
			'tilt' => [
				'label' => esc_html__( '3D Tilt', 'lebagol' ),
				'fields' => [
					'direction' => [
						'label' => esc_html__( 'Direction', 'lebagol' ),
						'type' => Controls_Manager::SELECT,
						'default' => '',
						'options' => [
							'' => esc_html__( 'Direct', 'lebagol' ),
							'negative' => esc_html__( 'Opposite', 'lebagol' ),
						],
					],
					'speed' => [
						'label' => esc_html__( 'Speed', 'lebagol' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 4,
						],
						'range' => [
							'px' => [
								'max' => 10,
								'step' => 0.1,
							],
						],
					],
				],
			],
		];
	}

	private function prepare_effects( $effects_group, array &$fields ) {
		$method_name = "get_{$effects_group}_effects";

		$effects = $this->$method_name();

		foreach ( $effects as $effect_name => $effect_args ) {
			$args = [
				'label' => $effect_args['label'],
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'condition' => [
					'motion_fx_' . $effects_group => 'yes',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			];

			if ( ! empty( $effect_args['separator'] ) ) {
				$args['separator'] = $effect_args['separator'];
			}

			$fields[ $effect_name . '_effect' ] = $args;

			$effect_fields = $effect_args['fields'];

			$first_field = & $effect_fields[ key( $effect_fields ) ];

			$first_field['popover']['start'] = true;

			end( $effect_fields );

			$last_field = & $effect_fields[ key( $effect_fields ) ];

			$last_field['popover']['end'] = true;

			reset( $effect_fields );

			foreach ( $effect_fields as $field_name => $field ) {
				$field = array_merge( $field, [
					'condition' => [
						'motion_fx_' . $effects_group => 'yes',
						$effect_name . '_effect' => 'yes',
					],
					'render_type' => 'none',
					'frontend_available' => true,
				] );

				$fields[ $effect_name . '_' . $field_name ] = $field;
			}
		}
	}
}
