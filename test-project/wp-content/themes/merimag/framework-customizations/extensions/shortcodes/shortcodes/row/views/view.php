<?php if (!defined('FW')) die('Forbidden');
$row_class  = ($row_class  = fw_ext('builder')->get_config('grid.row.class')) ? $row_class : 'fw-row';
$row_class .= isset( $atts['class'] ) ? ' ' . $atts['class'] . ' ' : '';
?>
<div class="<?php echo esc_attr($row_class); ?>">

	<?php echo do_shortcode($content); ?>
</div>

