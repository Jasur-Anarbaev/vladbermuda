 <?php
 add_theme_support('post-thumbnails');
 add_theme_support('title-tag');
 add_theme_support('custom-logo');
 
 add_filter( 'upload_mimes', 'svg_upload_allow' );
 
 # Добавляет SVG в список разрешенных для загрузки файлов.
 function svg_upload_allow( $mimes ) {
    $mimes['svg']  = 'image/svg+xml';
    
    return $mimes;
}


add_filter( 'wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5 );

# Исправление MIME типа для SVG файлов.
function fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ){
    
    // WP 5.1 +
    if( version_compare( $GLOBALS['wp_version'], '5.1.0', '>=' ) ){
        $dosvg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ] );
    }
    else {
        $dosvg = ( '.svg' === strtolower( substr( $filename, -5 ) ) );
    }
    
    // mime тип был обнулен, поправим его
    // а также проверим право пользователя
    if( $dosvg ){
        
        // разрешим
        if( current_user_can('manage_options') ){
            
            $data['ext']  = 'svg';
            $data['type'] = 'image/svg+xml';
        }
        // запретим
        else {
            $data['ext']  = false;
            $data['type'] = false;
        }
    }
    
    return $data;
}


if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
        'page_title' => 'Общие настройки',
        'menu_title' => 'Общие настройки',
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
    
}
function webp_upload_mimes( $existing_mimes ) {
    // add webp to the list of mime types
    $existing_mimes['webp'] = 'image/webp';
    
    // return the array back to the function with our added mime type
    return $existing_mimes;
}
add_filter( 'mime_types', 'webp_upload_mimes' );



error_reporting(E_ALL);
ini_set('display_errors', 1);

 ?>