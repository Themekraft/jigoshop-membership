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
 * Adds some membership meta data to a user
 * 
 * For a membership to be added to a user a product has to have a custom
 * attribute called Length. The value of the custom attribute can be 
 * anything that strtotime() can handdle.
 * 
 * The Length attribute should probably be hidden, so language translations
 * won't matter.
 * 
 * Attached to the <code>order_status_completed</code> action hook.
 *
 * @param	$order_id	int		Any valid order_id
 * 
 * @since 	1.0
 * @uses	class jigoshop_order
 * @uses	update_user_meta
 */
function jigomem_add_membership( $order_id )
{
	$order = &new jigoshop_order( $order_id );
	
	// no point in going forward if we don't have an order
	if( ! $order->id )
		return false;
	
	$has_length = false;
	$length		= false;

	foreach( $order->items as $item ) :
		$product = &new jigoshop_product( $item['id'] );
		
		foreach( $product->attributes as $attribute ) :
			if( $attribute['name'] == 'Length' ):
				$has_length = true;
				$length = $attribute['value'];
				break;
			endif;
		endforeach;
		
		if( $has_length )
			break;
	endforeach;
	
	// only proceed if the current product has a custom length attribute
	if( ! $has_length || ! $length )
		return false;
	
	update_user_meta( $order->user_id, 'jigomem_membership', strtotime( '+'. $length ) );
}
add_action( 'order_status_completed', 'jigomem_add_membership' );

/**
 * Check if a user is entitled to support
 * 
 * The current user is used if no user_id is provided.
 * get_current_user_id() is used instead of BP functions
 * to retain BP independence
 *
 * @param	$user_id	int		Any valid user_id, default is false
 * 
 * @since 	1.0
 * @uses	get_user_meta
 * @uses	get_current_user_id
 */
function jigomem_user_gets_support( $user_id = false )
{
	if( ! $user_id ) :
		$user_id = get_current_user_id();
	endif;
		
	$length = get_user_meta( $user_id, 'jigomem_membership', true );
	
	if( ! $length )
		return false;
	
	if( $length >= strtotime( 'now' ) )
		return true;
	
	return false;	
}

/**
 * Check if a user had support already
 * 
 * The current user is used if no user_id is provided.
 * get_current_user_id() is used instead of BP functions
 * to retain BP independence
 * 
 * For this function to do its work, the 'jigomem_membership'
 * user meta should never be deleted.
 *
 * @param	$user_id	int		Any valid user_id, default is false
 * 
 * @since 	1.0
 * @uses	get_user_meta
 * @uses	get_current_user_id
 */
function jigomem_user_had_support( $user_id = false )
{
	if( ! $user_id ) :
		$user_id = get_current_user_id();
	endif;
		
	$length = get_user_meta( $user_id, 'jigomem_membership', true );
	
	if( ! $length )
		return false;
	
	if( $length < strtotime( 'now' ) )
		return true;
	
	return false;
}

/**
 * Get the date when a support ends or has ended.
 * 
 * Default output can be easily modified with
 * <code>mysql2date( $dateformatstring, $mysqlstring, $translate )</code>
 * 
 * @param	$user_id	int		Any valid user_id, default is false
 * @param	$format		string	Can be any valid date() format
 * 
 * @since 	1.0
 */
function jigomem_get_support_date( $user_id = false, $format = 'Y-m-d H:i:s' )
{
	if( ! $user_id ) :
		$user_id = get_current_user_id();
	endif;
		
	$length = get_user_meta( $user_id, 'jigomem_membership', true );

	if( ! $length )
		return false;

	return date( $format, $length );
}
?>