<?php
/**
 * Output ALT tag of Advance Custom Fields Image Object.
 *
 * @package Oxygen Snippets
 */

function forte_get_image_alt($field){
    
    if(!class_exists('ACF')){
        return;
    }
    
    $image = get_field($field);
    return $image['alt'];
}
