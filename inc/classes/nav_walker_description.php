<?php
/**
 * Modify the menu on main page to display small descriptions of destination links.
 */
class nav_walker_description extends Walker_Nav_Menu
{
      function start_el(&$output, $item, $depth, $args)
      {
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "    ", $depth ) : '';
           $class_names = $value = '';
           $classes = empty( $item->classes ) ? array() : (array) $item->classes;
           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . '"';
           $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
				
			//assign home icon if home url
			rtrim( $item->url, "/" ) == get_home_url()? $icon = '<i class="icon-home"></i> ' : $icon = '';
			
			//gives us a chevron if a dropdown
			if(is_array($item->classes)){
			
			in_array( 'dropdown-toggle', $item->classes )? $icon_dropdown = ' <i class="icon-chevron-down"> </i> ' : $icon_dropdown  = '';
			
			empty( $item->description ) && in_array( 'dropdown-toggle', $item->classes ) ? $icon_dropdown_top = ' <i class="icon-chevron-down"> </i> ' : $icon_dropdown_top  = '';
						
			}
			
           $prepend = '<strong>' .$icon;
           $append = '</strong>';
           $description  = ! empty( $item->description ) ? '<i>'.esc_attr( $item->description ) . $icon_dropdown . '</i>' : '';
           if($depth != 0)
           {
                     $description = $append = $prepend = "";
           }
            $item_output = $args->before;
            $item_output .=  '<a'. $attributes .'>';
            $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
            $item_output .= $description.$args->link_after;
            $item_output .= $icon_dropdown_top . '</a>';
            $item_output .= $args->after;
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }
			
			
		function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
			if ( !$element )
				return;
	
			$id_field = $this->db_fields['id'];
	
			//display this element
			if ( is_array( $args[0] ) )
				$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
			
			
			//Adds the 'parent' class to the current item if it has children               
			if( ! empty( $children_elements[$element->$id_field] ) )
					array_push($element->classes,'dropdown-toggle');	
				
			$cb_args = array_merge( array(&$output, $element, $depth), $args);
			call_user_func_array(array(&$this, 'start_el'), $cb_args);
				
			$id = $element->$id_field;
									
			// descend only when the depth is right and there are childrens for this element
			if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {
	
				foreach( $children_elements[ $id ] as $child ){
	
					if ( !isset($newlevel) ) {
						$newlevel = true;
						//start the child delimiter
						$cb_args = array_merge( array(&$output, $depth), $args);
						call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
					}
					$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
				}
				unset( $children_elements[ $id ] );
			}
	
			if ( isset($newlevel) && $newlevel ){
				//end the child delimiter
				$cb_args = array_merge( array(&$output, $depth), $args);
				call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
			}
			$cb_args = array_merge( array(&$output, $element, $depth), $args);
			call_user_func_array(array(&$this, 'end_el'), $cb_args);
		}
			
}
?>