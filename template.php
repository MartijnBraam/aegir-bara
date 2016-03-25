<?php

/**
 * Preprocessor for theme_page().
 */
function bara_preprocess_page(&$variables, $hook) {
  // Prepare the svg URL
  if (strpos($variables['logo'], 'bara')) {
    $variables['svg_logo'] = str_replace('logo.png', 'images-source/aegir_logo_horizontal.svg', $variables['logo']);
  }

  // Overlay is enabled.
  $variables['overlay'] = (module_exists('overlay') && overlay_get_mode() === 'child');

  $variables['tabs2'] = $variables['tabs'];
  unset($variables['tabs']['#secondary']);
  unset($variables['tabs2']['#primary']);

  // Move the local actions into place of the secondary tabs, for now.
  if (is_array($variables['action_links'])) {
    foreach ($variables['action_links'] as $link) {
      $variables['tabs2']['#secondary'][] = $link;
      $variables['tabs2']['#theme'] = 'menu_local_tasks';
    }
    $variables['action_links']['#access'] = FALSE;
  }

  if (!isset($variables['title'])) {
    $variables['title'] = isset($variables['node']) ? $variables['node']->title : drupal_get_title();
  }

  $node = menu_get_object();
  if (!empty($node) && !$variables['overlay']) {
    // Add a node type label on node pages to help users.
    $types = node_type_get_types();
    $type = $node->type;
    if (!empty($types[$type])) {
      $variables['title'] = "<span class='label'>{$types[$type]->name}</span>" . $variables['title'];

    }
  }
}

function bara_preprocess_html(&$variables, $hook) {
  $node = menu_get_object();
  if (!empty($node)) {
    $type = $node->type;

    $variables['classes_array'][] = " node-page";
    $variables['classes_array'][] = " ntype-{$type}";
  }

  // Add path-based class for a last line of defense
  $current_path = current_path();
  if (!empty($current_path)) {
    $variables['classes_array'][] = ' path-' . drupal_html_class(current_path());
  }

  // Add special body class for error pages
#  if (menu_get_active_item() === 0) {
#    $variables['body_classes'] .= ' error-page';
#  }

}

/**
 * Preprocessor for theme_node().
 */
function bara_preprocess_node(&$variables, $hook) {

  if (!empty($variables['node'])) {
    // Add a node type label on node pages to help users.
    $types = node_type_get_types();
    $type = $variables['node']->type;
    if (!empty($types[$type])) {
      $variables['title'] = "<span class='label'>{$types[$type]->name}</span>" . $variables['title'];
    }
  }
}
