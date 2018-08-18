<?php

return array (
  'autoload' => false,
  'hooks' => 
  array (
    'upload_after' => 
    array (
      0 => 'thumb',
    ),
    'wipecache_after' => 
    array (
      0 => 'tinymce',
    ),
    'set_tinymce' => 
    array (
      0 => 'tinymce',
    ),
  ),
  'route' => 
  array (
    '/example$' => 'example/index/index',
    '/example/d/[:name]' => 'example/demo/index',
    '/example/d1/[:name]' => 'example/demo/demo1',
    '/example/d2/[:name]' => 'example/demo/demo2',
  ),
);