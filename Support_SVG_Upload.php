<?php

namespace vnh;

use vnh\contracts\Bootable;

class Support_SVG_Upload implements Bootable {
	public function boot() {
		add_filter('wp_check_filetype_and_ext', [$this, 'wp_check_filetype_and_ext'], 100, 4);
		add_filter('wp_prepare_attachment_for_js', [$this, 'response_for_svg'], 10, 3);
		add_action('admin_init', [$this, 'add_svg_support']);
	}

	public function wp_check_filetype_and_ext($filetype_ext_data, $file, $filename, $mimes) {
		if (substr($filename, -4) === '.svg') {
			$filetype_ext_data['ext'] = 'svg';
			$filetype_ext_data['type'] = 'image/svg+xml';
		}

		return $filetype_ext_data;
	}

	public function response_for_svg($response, $attachment, $meta) {
		if ($response['mime'] === 'image/svg+xml' && empty($response['sizes'])) {
			$svg_path = get_attached_file($attachment->ID);

			if (!file_exists($svg_path)) {
				// If SVG is external, use the URL instead of the path
				$svg_path = $response['url'];
			}

			$dimensions = $this->svgs_get_dimensions($svg_path);

			$response['sizes'] = [
				'full' => [
					'url' => $response['url'],
					'width' => $dimensions->width,
					'height' => $dimensions->height,
					'orientation' => $dimensions->width > $dimensions->height ? 'landscape' : 'portrait',
				],
			];
		}

		return $response;
	}

	public function add_svg_support() {
		ob_start();
		add_action('admin_head', [$this, 'svg_css_fix']);
		add_filter('upload_mimes', [$this, 'add_svg_mime']);
		add_action('shutdown', [$this, 'on_shutdown'], 0);
		add_filter('final_output', [$this, 'fix_template'], 99, 1);
	}

	public function svg_css_fix() {
		$style = '
		<style>
			table.media .column-title .media-icon {
				position: relative
			}

			table.media .column-title .media-icon img[src$=".svg"] {
				position: absolute;
				height: 100% !important;
				left: 0;
			}

			img[src$=".svg"] {
				width: 100%;
				height: 100%;
				object-fit: contain;
			}
		</style>
		';

		echo $style; //phpcs:disable
	}

	public function add_svg_mime($mimes = []) {
		if (current_user_can('administrator')) {
			$mimes['svg'] = 'image/svg+xml';
			$mimes['svgz'] = 'image/svg+xml';

			return $mimes;
		}

		return $mimes;
	}

	public function on_shutdown() {
		$final = '';
		$ob_levels = count((array) ob_get_level());
		for ($i = 0; $i < $ob_levels; $i++) {
			$final .= ob_get_clean();
		}
		echo $final; // WPCS: xss ok.
	}

	public function fix_template($content = '') {
		// Attachment window
		$content = str_replace(
			'<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
			'<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<img class="details-image" src="{{ data.url }}" draggable="false" alt="" />
			<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
			$content
		);
		// Grid View
		$content = str_replace(
			'<# } else if ( \'image\' === data.type && data.sizes ) { #>',
			'<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<div class="centered">
					<img src="{{ data.url }}" class="thumbnail" draggable="false" alt="" />
				</div>
			<# } else if ( \'image\' === data.type && data.sizes ) { #>',
			$content
		);
		// Attachment View (4.7)
		$content = str_replace(
			'<# } else if ( data.sizes && data.sizes.full ) { #>',
			'<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<img class="details-image" src="{{ data.url }}" draggable="false" alt="" />
			<# } else if ( data.sizes && data.sizes.full ) { #>',
			$content
		);

		return $content;
	}

	private function svgs_get_dimensions($svg) {
		$svg = simplexml_load_string(file_get_contents($svg));

		if ($svg === false) {
			$width = '0';
			$height = '0';
		} else {
			$attributes = $svg->attributes();
			$width = (string) $attributes->width;
			$height = (string) $attributes->height;
		}

		return (object) [
			'width' => $width,
			'height' => $height,
		];
	}
}
