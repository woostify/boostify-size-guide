<?php

namespace Boostify_Size_Guide\Widgets;

use Boostify_Size_Guide\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Typography;

/**
 * Site Search Widget
 *
 * Elementor widget for Site Search.
 */
class Site_Search extends Base_Widget {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ht-site-search';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Site Search', 'boostify' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-search';
	}

    public function get_categories() {
        return array( 'ht_bfsg_builder' );
    }

	public function get_script_depends() {
		return array( 'ht-site-search' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Search', 'boostify' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'boostify' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'icon' => 'Icon',
					'form' => 'Form',
				),
				'default' => 'icon',
			)
		);

		$this->add_control(
			'button_type',
			array(
				'label'   => esc_html__( 'Button Type', 'boostify' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'icon' => 'Icon',
					'text' => 'Text',
				),
				'default' => 'icon',
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'     => esc_html__( 'Select Icon', 'boostify' ),
				'type'      => Controls_Manager::ICON,
				'include'   => array(
					'ion-ios-search',
					'ion-ios-search-strong',
					'fa fa-search',
					'ion-ios-arrow-thin-right',
				),
				'default'   => 'ion-ios-search',
				'condition' => array(
					'button_type' => 'icon',
				),

			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => __( 'Label', 'boostify' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Label', 'boostify' ),
				'condition'   => array(
					'button_type' => 'text',
				),
			)
		);

		$this->add_control(
			'placeholder',
			array(
				'label'       => __( 'Placeholder', 'boostify' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Placeholder', 'boostify' ),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'     => esc_html__( 'Align', 'boostify' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .boostify-site-search-toggle' => 'text-align: {{VALUE}};',
				),
				'options'   => array(
					'left'   => array(
						'icon'  => 'eicon-h-align-left',
						'title' => 'Left',
					),
					'center' => array(
						'icon'  => 'eicon-h-align-center',
						'title' => 'Center',
					),
					'right'  => array(
						'icon'  => 'eicon-h-align-right',
						'title' => 'Right',
					),
				),
				'condition' => array(
					'layout' => 'icon',
				),
			)
		);

		$this->add_control(
			'height',
			array(
				'label'      => __( 'Height', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 30,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 45,
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-search-form-header .site-search-field' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .boostify-search-form-header .btn-boostify-search-form' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => 'form',
				),
			)
		);

		$this->add_control(
			'padding',
			array(
				'label'      => __( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-search-icon--toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => 'icon',
				),
			)
		);

		$this->add_control(
			'padding_form',
			array(
				'label'              => __( 'Padding', 'boostify' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px' ),
				'allowed_dimensions' => array( 'right', 'left' ),
				'default'            => array(
					'top'    => 0,
					'bottom' => 0,
					'left'   => 10,
					'right'  => 10,
				),
				'selectors'          => array(
					'{{WRAPPER}} .boostify-search-form-header .site-search-field' => 'padding: 0{{UNIT}} {{RIGHT}}{{UNIT}} 0{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'          => array(
					'layout' => 'form',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_icon',
			array(
				'label'     => __( 'Icon Layout', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .boostify-search-icon--toggle' => 'color: {{VALUE}}',
					'{{WRAPPER}} .btn-boostify-search-form'     => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .boostify-search-icon--toggle, {{WRAPPER}} .btn-boostify-search-form',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'background',
				'label'    => __( 'Background', 'boostify' ),
				'types'    => array( 'classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .boostify-search-icon--toggle, {{WRAPPER}} .btn-boostify-search-form',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border',
				'label'    => __( 'Border', 'boostify' ),
				'selector' => '{{WRAPPER}} .boostify-search-icon--toggle, {{WRAPPER}} .site-search-form',
			)
		);

		$this->add_control(
			'bdrs',
			array(
				'label'      => __( 'Border Radius', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-search-icon--toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .site-search-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'placeholder_header',
			array(
				'label'     => __( 'Placeholder', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'layout' => 'form',
				),
			)
		);

		$this->add_control(
			'placeholder_color',
			array(
				'label'     => __( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .site-search-form ::placeholder ' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'layout' => 'form',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'placeholder_typography',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .site-search-form ::placeholder',
				'condition' => array(
					'layout' => 'form',
				),
			)
		);

		$this->add_control(
			'input_header',
			array(
				'label'     => __( 'Input', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'layout' => 'form',
				),
			)
		);

		$this->add_control(
			'input_color',
			array(
				'label'     => __( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .site-search-field' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'layout' => 'form',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .site-search-field',
				'condition' => array(
					'layout' => 'form',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings    = $this->get_settings_for_display();
		$icon        = $settings['icon'];
		$text        = $settings['text'];
		$placeholder = $settings['placeholder'];
		if ( empty( $text ) ) {
			$text = null;
		}

		if ( 'icon' == $settings['layout'] ) { // phpcs:ignore
			?>
			<div class="boostify-site-search-toggle">
				<button class="boostify-search-icon--toggle <?php echo esc_attr( $settings['icon'] ); ?>" aria-expanded="false">
					<span class="screen-reader-text"><?php echo esc_html__( 'Enter Keyword', 'boostify' ); ?></span>
				</button>
			</div>

			<div class="boostify-search--toggle">
				<div class="boostify-search-toggle--wrapper">
					<?php do_action( 'boostify_hf_seach_form', $icon, $placeholder, $text ); ?>
				</div>
				<button class="boostify--site-search-close ion-android-close">
					<span class="screen-reader-text"><?php echo esc_html__( 'Close', 'boostify' ); ?></span>
				</button>
			</div>
			<?php
		} else {
			?>
			<div class="boostify-search-form-header">
				<div class="boostify-search-form--wrapper">
					<?php do_action( 'boostify_hf_seach_form', $icon, $placeholder, $text ); ?>
				</div>
			</div>
			<?php
		}
	}
}
