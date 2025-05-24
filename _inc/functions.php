<?php
function get_nav_menu(array $items) { // Get elements from array in index php // Взять список элементов из главн странки
    $menuItems = '';
    foreach($items as $item_name => $item_link) {
        $menuItems .= '<li class="nav-item">';
        $menuItems .= '<a class="nav-link click-scroll" href="' . $item_link . '">' . $item_name . '</a>';
        $menuItems .= '</li>';
    }
    return $menuItems;
}


function get_footer_menu(array $items) { // Get elements from array in index php // Взять список элементов из главн странки
    $menuItems = '';
    foreach($items as $item_name => $item_link) {
        $menuItems .= '<li class="site-footer-link-item">';
        $menuItems .= '<a href="' . $item_link . '" class="site-footer-link">' . $item_name . '</a>';
        $menuItems .= '</li>';
    }
    return $menuItems;
}

?>