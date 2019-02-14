<?php

require_once 'alternativenamesearch.civix.php';
use CRM_Alternativenamesearch_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function alternativenamesearch_civicrm_config(&$config) {
  _alternativenamesearch_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function alternativenamesearch_civicrm_xmlMenu(&$files) {
  _alternativenamesearch_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function alternativenamesearch_civicrm_install() {
  _alternativenamesearch_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function alternativenamesearch_civicrm_postInstall() {
  _alternativenamesearch_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function alternativenamesearch_civicrm_uninstall() {
  _alternativenamesearch_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function alternativenamesearch_civicrm_enable() {
  _alternativenamesearch_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function alternativenamesearch_civicrm_disable() {
  _alternativenamesearch_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function alternativenamesearch_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _alternativenamesearch_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function alternativenamesearch_civicrm_managed(&$entities) {
  _alternativenamesearch_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function alternativenamesearch_civicrm_caseTypes(&$caseTypes) {
  _alternativenamesearch_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function alternativenamesearch_civicrm_angularModules(&$angularModules) {
  _alternativenamesearch_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function alternativenamesearch_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _alternativenamesearch_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function alternativenamesearch_civicrm_entityTypes(&$entityTypes) {
  _alternativenamesearch_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Apply your patch.
 *
 * @param string $original - the original file, like /CRM/Core/...php
 *
 * @param string &$code - the code of the original file (which may have been
 * altered already by another implementor of this hook).
 *
 */
function alternativenamesearch_patchwork_apply_patch($original, &$code) {
  Civi::log()->info($original);
  if ($original === '/CRM/Contact/BAO/Query.php') {

    $code = explode("\n", $code);
    $new_code = [];
    while ($code && $code[0] != '  public function sortName(&$values) {') {
      $new_code[] = array_shift($code);
    }
    if (!$code) {
      return FALSE;
    }
    // Ok, we found the function we need to change.
    // Copy down to a specific line.
    $lines = 0;
    while ($code && $code[0] != '    $sub = array();') {
      $new_code[] = array_shift($code);
      $lines++;
    }
    if ($lines > 30) {
      // Something major has changed.
      Civi::log()->notice("Patchwork failed on alternativenamesearch 2", []);
      return FALSE;
    }

    // Insert our patch.
    $new_code[] = '    // Begin alternativenamesearch patch.';
    $new_code[] = '    $sub = alternativenamesearch_get_subs($value, $op, $fieldName, $config);';

    // Now comment the next block.
    $new_code[] = '    // Begin block commented by alternativenamesearch';
    while ($code && $code[0] != '    $this->_where[$grouping][] = $sub;') {
      $new_code[] = '    // ' . array_shift($code);
      $lines++;
    }
    if (!$code) {
      Civi::log()->notice("Patchwork failed on alternativenamesearch 4", []);
      return FALSE;
    }

    // OK, we're done, just copy the rest.
    $new_code = array_merge($new_code, $code);
    $code = implode("\n", $new_code);
    Civi::log()->notice("Patchwork successful on alternativenamesearch", []);
  }
  elseif ($original === '/api/v3/Contact.php') {
    // Patch the API file.
    $code = explode("\n", $code);
    $new_code = [];
    while ($code && $code[0] != '    // Search by id should be exact') {
      $new_code[] = array_shift($code);
    }
    if (!$code) {
      Civi::log()->notice("Patchwork failed on alternativenamesearch 3", []);
      return FALSE;
    }

    // Now scan down to the line that sets the $whereClause variable.
    $lines = 0;
    while ($code && !preg_match('/^    \$whereClause =/', $code[0])) {
      $new_code[] = array_shift($code);
      $lines++;
    }
    // Make sure nothing too much has changed.
    if ($lines > 15) {
      Civi::log()->notice("Patchwork failed on alternativenamesearch 4", []);
      return FALSE;
    }
    $new_code[] = '    // Next line inserted by alternativenamesearch';
    $new_code[] = '    $whereClause = alternativenamesearch_rework_api_where($name, $includeNickName, $where);';
    $new_code[] = '    // ' . array_shift($code);

    // Copy the rest.
    $new_code = array_merge($new_code, $code);
    $code = implode("\n", $new_code);
  }
}

/**
 * Takes user-input name query and returns a where clause on sort_name.
 *
 * Used by
 * - civicrm_api3_contact_getquick()
 *
 * @param string $name the un-escaped user-inputted name.
 * @param string $op
 * @param string $fieldName
 * @param $config
 *
 * @return string
 */
function alternativenamesearch_get_subs($name, $op, $fieldName, $config) {
  Civi::log()->info("Searching $name $op $fieldName");
  $field_names = [];
  if ($fieldName == 'sort_name') {
    $field_names []= CRM_Contact_BAO_Query::caseImportant($op) ? "LOWER(contact_a.sort_name)" : "contact_a.sort_name";
  }
  else {
    $field_names []= CRM_Contact_BAO_Query::caseImportant($op) ? "LOWER(contact_a.display_name)" : "contact_a.display_name";
  }

  if ($config->includeNickNameInName) {
    $field_names []= CRM_Contact_BAO_Query::caseImportant($op) ? "LOWER(contact_a.nick_name)" : "contact_a.nick_name";
  }

  if ($config->includeEmailInName) {
    $field_names []= 'civicrm_email.email';
  }

  // If quoted, strip quotes, escape, add quotes, return as-is.
  if (preg_match('/^([\'"])(.*)\\1$/', $name)) {
    $pattern = "( __FIELDNAME_PLACEHOLDER__ $op '" . CRM_Utils_Type::escape($name, 'String')  ."' )";
  }
  else {

    // We need to generate the search for the name.
    // Aparently we can trust that the $name values are all ready-escaped for
    // MySQL. As we're only splitting on space, this should be safe.
    //
    // Q. what happens if search is escaped space?  foo\ bar -> foo\, bar
    // A. that would be bad. Therefore only split if unescaped, using lookbehind
    // assertion.
    //
    // Civi offers a single LIKE search for name, but we want to take a space
    // separated list; all the 'words' must be found in the name.
    // Convert "foo bar baz" -> like '%foo%' and like '%bar%' and like '%baz%'
    //
    // However if the entered name contains duplicates: foo bar foo, we must
    // check for two instances of foo.
    // Convert "foo bar foo" -> like '%foo%foo%' and like '%bar%'
    //
    // We ignore the case, so Foo, foo, FOO are all considered the same.

    $strtolower = function_exists('mb_strtolower') ? 'mb_strtolower' : 'strtolower';
    // Count occurrances of unique names: $namesCount[name] = (int) count.
    $namesCount = [];
    foreach (preg_split('/\s+/', $strtolower(trim($name))) as $_) {
      $_ = CRM_Utils_Type::escape($_, 'String');
      $namesCount[$_] = isset($namesCount[$_]) ? $namesCount[$_]+1 : 1;
    }

    foreach ($namesCount as $_ => $count) {
      if ($count == 1) {
        // single name, just do normal like statement.
        $namesSplit[] = "(__FIELDNAME_PLACEHOLDER__ LIKE '%$_%')";
      }
      else {
        // name repeated. Look for %foo%foo%
        $namesSplit[] = "(__FIELDNAME_PLACEHOLDER__ LIKE '" . str_repeat("%$_", $count) . "%')";
      }
    }
    $pattern = "( " . implode(' AND ', $namesSplit) . ") ";
  }

  // Now we have our pattern, apply it to fields.
  $or_group = [];
  foreach ($field_names as $field_name) {
    $or_group []= str_replace('__FIELDNAME_PLACEHOLDER__', $field_name, $pattern);
  }

  return "(" . implode(' OR ', $or_group) . ")";
}
/**
 *
 * Function to change the where clause of Contact quicksearch.
 *
 * @param string $name
 * @param string $includeNickName
 * @param string $where
 *
 * @return string
 */
function alternativenamesearch_rework_api_where($name, $includeNickName, $where) {

  // We need to generate the search for the name.
  // Aparently we can trust that the $name values are all ready-escaped for MySQL.
  //
  // Civi offers a single LIKE search for name, but we want to take a space separated list; all the 'words' must be found in the name.
  // Convert "foo bar baz" -> [ '%foo%', '%bar%', '%baz%' ]
  // We also need to make sure that any names that appear n times match only names with that name in at least n times.
  $name_counts = [];
  foreach (preg_split('/\s+/', trim($name)) as $_) {
    $name_counts += [$_ => 0];
    $name_counts[$_]++;
  }

  $name_clauses = [];
  foreach ($name_counts as $name => $_) {
    if ($_ > 1) {
      $name = rtrim(str_repeat("$name%", $_), '%');
    }
    $name_clauses[] = "sort_name LIKE '%$name%'";
  }

  $name_clauses = "( " . implode(' AND ', $name_clauses) . ") ";
  $whereClause = " WHERE ( $name_clauses $includeNickName ) {$where} ";
  return $whereClause;
}
