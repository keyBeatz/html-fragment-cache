# html-fragment-cache
HTML fragment cache is simple WP plugin for developers.
This plugin provide HTML caching class with various functions and the API for easy implementation. It is using classic file cache method. This plugin may be very useful once you need to cache specific pieces of your code insted of whole page and can't use memcached or any other object caching method. Plugin offers keybased (key->data) system which can be used also for caching dynamic queries with multiple parameters.

Basic example
```php

$group  = array( 'folder1', 'folder2' );    // group can be array or string, see more info in the code api.php
$name   = array( 'my', 'file' );            // name can be array or string, see more info in the code api.php
$expire = '2017-04-01 20:00:00';            // set expire in datetime format, you can set whole datetime or just a part like Y-m-d, it will be reformated. False == infinite.

$contents = hac_get( $group, $name );

if( !empty( $contents ) )
  echo $contents;
else {
  $html = "<p>this string is prepared to be cached</p>";
  hac_add( $group, $name, $html, $expire );   // name of file and path will be in default setting like /wp-content/uploads/hac_cache/folder1/folder2/my_file.html
  echo $html;
}

```

Example with php ob_get_contents():

```php
...

$cached_sidebar = hac_get( 'sidebars', 'main_sidebar' );  // try to grab file from the cache

if( !empty( $cached_sidebar ) )
  echo $cached_sidebar;               // if file was found in the cache print it
else {
  ob_start();
  get_sidebar();                      // get default WP sidebar, instead of printing we are using ob_get_contents()
  $sidebar_html = ob_get_contents();  // get sidebar as html string
  ob_end_clean();
  
  hac_add( 'sidebars', 'main_sidebar', $sidebar_html, $expire = false );  // add sidebar to cache, next time it will be loaded from cache until it gets expired or revalidated
  
  echo $sidebar_html;
}

```
Revalidation examples:
```php
// for cache revalidation you can use two (three) functions or set expire variable in hac_add() function
// you will use the very same group and name for deleting specific file:
hac_delete_file( $group, $name );

// or you can delete whole group
hac_delete_group( $group );

// or you can delete all cached files at once with:
hac_flush();
```
Settings:
- for settings please check main plugin file and edit provided constants
