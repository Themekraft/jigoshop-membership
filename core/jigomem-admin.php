<?php
/**
 * @package		WordPress
 * @subpackage	Jigoshop
 * @author		Boris Glumpler
 * @copyright	2011, Themekraft
 * @license		http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

// No direct access is allowed
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add a support column to the user table
 * Automatically supports a multisite environment
 * 
 * Attached to the <code>manage_users_columns</code> or
 * <code>wpmu_users_columns</code> filter depending on MS
 * being activated or not
 * 
 * @since 	1.0
 */
function jigomem_manage_columns( $columns )
{
	$columns['membership'] = __( 'Membership', 'jigomem' );
	
	return $columns;
}
if( is_multisite() )
	add_filter( 'wpmu_users_columns', 'jigomem_manage_columns' );
else
	add_filter( 'manage_users_columns', 'jigomem_manage_columns' );

/**
 * Add some content to the support column
 * 
 * Attached to the <code>manage_users_custom_column</code> filter
 * 
 * @since 	1.0
 */
function jigomem_support_column_content( $c, $column, $user_id )
{
	if( $column == 'membership' )
	{
		$date = jigomem_get_support_date( $user_id );
		
		if( jigomem_user_gets_support( $user_id ) === true ) :
			return sprintf( __( 'Until %s', 'jigomem' ), $date );
		else :
			if( jigomem_user_had_support( $user_id ) === true ) :
				return sprintf( __( 'Ended %s', 'jigomem' ), $date );
			else :
				return __( 'No', 'jigomem' );
			endif;
		endif;
	}
}
add_filter( 'manage_users_custom_column', 'jigomem_support_column_content', 10, 3 );
?>