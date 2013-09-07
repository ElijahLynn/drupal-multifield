<?php

class multifield_export_ui extends ctools_export_ui {
  function access($op, $item) {
    if (in_array($op, array('import', 'export', 'revert', 'disable', 'enable'))) {
      return FALSE;
    }
    return parent::access($op, $item);
  }

  function build_operations($item) {
    // WTF, why doesn't ctools_export_ui() run these operations
    // through the access method???
    $allowed_operations = parent::build_operations($item);
    foreach ($allowed_operations as $op => $operation) {
      if (!$this->access($op, $item)) {
        unset($allowed_operations[$op]);
      }
    }
    return $allowed_operations;
  }

  function delete_page($js, $input, $item) {
    $output = parent::delete_page($js, $input, $item);

    if ($fields = multifield_type_get_fields($item)) {
      $output['description']['#markup'] .= ' ' . t('The following fields and their respective data will also be removed, which cannot be undone:') . theme('item_list', array('items' => $fields));
    }

    return $output;
  }
}
