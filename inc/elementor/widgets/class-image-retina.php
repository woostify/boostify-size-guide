<?php
/**
 * Widget Elementor
 *
 * @package Boostify_Size_Guide
 */

namespace Boostify_Size_Guide\Widgets;

use Boostify_Size_Guide\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Typography;

/**
 * Image Retina
 */
class Image_Retina extends Base_Widget {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ht-sg-retina';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Retina Image', 'boostify' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-eye';
	}

	protected function _register_controls() { //phpcs:ignore
		$this->retina();
		$this->image_style();
		$this->caption_style();
	}

	/**
	 * Register Retina Logo General Controls.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function retina() {
		$this->start_controls_section(
			'section_retina_image',
			array(
				'label' => __( 'Retina Image', 'boostify' ),
			)
		);
		$this->add_control(
			'retina_image',
			array(
				'label'   => __( 'Choose Default Image', 'boostify' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);
		$this->add_control(
			'real_retina',
			array(
				'label'   => __( 'Choose Retina Image', 'boostify' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'retina_image',
				'label'   => __( 'Image Size', 'boostify' ),
				'default' => 'medium',
			)
		);
		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'boostify' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'boostify' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'boostify' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'boostify' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .boostify-retina-image-container, {{WRAPPER}} .boostify-caption-width' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'caption',
			array(
				'label'       => __( 'Custom Caption', 'boostify' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Enter your image caption', 'boostify' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'boostify' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'boostify' ),
			)
		);
		$this->end_controls_section();
	}
	/**
	 * Register Retina Image Style Controls.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function image_style() {
		$this->start_controls_section(
			'section_style_retina_image',
			array(
				'label' => __( 'Retina Image', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'          => __( 'Width', 'boostify' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( '%', 'px', 'vw' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .boostify-retina-image img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .boostify-retina-image .wp-caption .widget-image-caption' => 'width: {{SIZE}}{{UNIT}}; display: inline-block;',
				),
			)
		);

		$this->add_responsive_control(
			'space',
			array(
				'label'          => __( 'Max Width', 'boostify' ) . ' (%)',
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( '%' ),
				'range'          => array(
					'%' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .boostify-retina-image img' => 'max-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wp-caption-text' => 'max-width: {{SIZE}}{{UNIT}}; display: inline-block; width: 100%;',
				),
			)
		);

		$this->add_control(
			'separator_panel_style',
			array(
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			)
		);

		$this->add_control(
			'retina_image_border',
			array(
				'label'       => __( 'Border Style', 'boostify' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'label_block' => false,
				'options'     => array(
					'none'   => __( 'None', 'boostify' ),
					'solid'  => __( 'Solid', 'boostify' ),
					'double' => __( 'Double', 'boostify' ),
					'dotted' => __( 'Dotted', 'boostify' ),
					'dashed' => __( 'Dashed', 'boostify' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .boostify-retina-image-container .boostify-retina-img' => 'border-style: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'retina_image_border_size',
			array(
				'label'      => __( 'Border Width', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'    => '1',
					'bottom' => '1',
					'left'   => '1',
					'right'  => '1',
					'unit'   => 'px',
				),
				'condition'  => array(
					'retina_image_border!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-retina-image-container .boostify-retina-img' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'retina_image_border_color',
			array(
				'label'     => __( 'Border Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'retina_image_border!' => 'none',
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .boostify-retina-image-container .boostify-retina-img' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-retina-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab(
			'normal',
			array(
				'label' => __( 'Normal', 'boostify' ),
			)
		);

		$this->add_control(
			'opacity',
			array(
				'label'     => __( 'Opacity', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-retina-image img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .boostify-retina-image img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label' => __( 'Hover', 'boostify' ),
			)
		);
		$this->add_control(
			'opacity_hover',
			array(
				'label'     => __( 'Opacity', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-retina-image:hover img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .boostify-retina-image:hover img',
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label' => __( 'Hover Animation', 'boostify' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);
		$this->add_control(
			'background_hover_transition',
			array(
				'label'     => __( 'Transition Duration', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-retina-image img' => 'transition-duration: {{SIZE}}s',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
	/**
	 * Register Caption style Controls.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function caption_style() {
		$this->start_controls_section(
			'section_style_caption',
			array(
				'label' => __( 'Caption', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .widget-image-caption' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'caption_background_color',
			array(
				'label'     => __( 'Background Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .widget-image-caption' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'caption_typography',
				'selector' => '{{WRAPPER}} .widget-image-caption',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);

		$this->add_responsive_control(
			'caption_padding',
			array(
				'label'      => __( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .widget-image-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'caption_space',
			array(
				'label'     => __( 'Caption Top Spacing', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .widget-image-caption' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: 0px;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Check if the current widget has caption
	 *
	 * @access private
	 * @since 1.2.0
	 *
	 * @param array $settings returns settings.
	 *
	 * @return boolean
	 */
	private function has_caption( $settings ) {
		return ( ! empty( $settings['caption_source'] ) && 'none' !== $settings['caption_source'] );
	}

	/**
	 * Get the caption for current widget.
	 *
	 * @access private
	 * @since 1.2.0
	 * @param array $settings returns the caption.
	 *
	 * @return string
	 */
	private function get_caption( $settings ) {
		$caption = '';
		if ( 'custom' === $settings['caption_source'] ) {
			$caption = ! empty( $settings['caption'] ) ? $settings['caption'] : '';
		}
		return $caption;
	}

	/**
	 * Render
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['retina_image']['url'] ) ) {
			return;
		}

		$has_caption = $this->has_caption( $settings );

		$this->add_render_attribute( 'wrapper', 'class', 'boostify-retina-image' );

		?>
		<div <?php echo esc_attr( $this->get_render_attribute_string( 'wrapper' ) ); ?>>
			<?php
			$size = $settings['retina_image_size'];
			$demo = '';

			if ( 'custom' !== $size ) {
				$image_size = $size;
			} else {
				require_once ELEMENTOR_PATH . 'includes/libraries/bfi-thumb/bfi-thumb.php';

				$image_dimension = $settings['retina_image_custom_dimension'];

				$image_size = array(
					// Defaults sizes.
					0           => null, // Width.
					1           => null, // Height.

					'bfi_thumb' => true,
					'crop'      => true,
				);

				$has_custom_size = false;
				if ( ! empty( $image_dimension['width'] ) ) {
					$has_custom_size = true;
					$image_size[0]   = $image_dimension['width'];
				}

				if ( ! empty( $image_dimension['height'] ) ) {
					$has_custom_size = true;
					$image_size[1]   = $image_dimension['height'];
				}

				if ( ! $has_custom_size ) {
					$image_size = 'full';
				}
			}
			$retina_image_url = $settings['real_retina']['url'];

			$image_url = $settings['retina_image']['url'];

			$image_data = wp_get_attachment_image_src( $settings['retina_image']['id'], $image_size, true );

			$retina_data = wp_get_attachment_image_src( $settings['real_retina']['id'], $image_size, true );

			$retina_image_class = 'elementor-animation-';

			if ( ! empty( $settings['hover_animation'] ) ) {
				$demo = $settings['hover_animation'];
			}
			if ( ! empty( $image_data ) ) {
				$image_url = $image_data[0];
			}
			if ( ! empty( $retina_data ) ) {
				$retina_image_url = $retina_data[0];
			}
			$class_animation = $retina_image_class . $demo;

			$image_unset         = site_url() . '/wp-includes/images/media/default.png';
			$placeholder_img_url = Utils::get_placeholder_image_src();

			if ( $image_unset === $retina_image_url ) {
				if ( $image_unset !== $image_url ) {
					$retina_image_url = $image_url;
				} else {
					$retina_image_url = $placeholder_img_url;
				}
			}

			if ( $image_unset === $image_url ) {
				$image_url = $placeholder_img_url;
			}

			$link = empty( $settings['link'] ) ? false : $settings['link'];

			?>
			<div class="boostify-retina-image-set">
				<div class="boostify-retina-image-container">
					<?php $this->link_open( $link ); ?>
					<img class="boostify-retina-img <?php echo esc_attr( $class_animation ); ?>" src="<?php echo esc_url( $image_url ); ?>" srcset="<?php echo esc_url( $image_url ) . esc_attr( ' 1x' ) . ',' . esc_url( $retina_image_url ) . esc_attr( ' 2x' ); ?>"/>
					<?php $this->link_close( $link ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * [link_open description]
	 *
	 * @param string $link type.
	 */
	public function link_open( $link ) {
		if ( $link['url'] ) {
			?>
			<a href="<?php echo esc_url( $link['url'] ); ?>">
			<?php
		}
	}

	/**
	 * [link_close description]
	 *
	 * @param string $link type.
	 */
	public function link_close( $link ) {
		if ( $link['url'] ) {
			?>
			</a>
			<?php
		}
	}

}
