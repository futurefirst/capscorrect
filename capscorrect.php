<?php

// Group name of our settings.
CONST CAPSCORRECT_EXTENSION_NAME = 'uk.org.futurefirst.networks.capscorrect';
// Settings names
CONST CAPSCORRECT_LAST_CONTACT_OFFSET = 'lastContactExaminedOffset';
CONST CAPSCORRECT_TOTAL_CONTACTS_CORRECTED = 'totalContactsCorrected';
// Value and name of the default number of rows to be corrected each run.
CONST DEFAULT_DAILY_CONTACTS_CORRECTED = 500;
CONST SCHEDULED_JOB_PARAM_DEFAULT_NAME = 'contactsToCorrectEachRun';

require_once 'capscorrect.civix.php';

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function capscorrect_civicrm_config(&$config) {
  _capscorrect_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function capscorrect_civicrm_install() {
  
  // Create settings entry to store the offset of the last contact examined
  // and the total number of contacts corrected.
  CRM_Core_BAO_Setting::setItem(0, CAPSCORRECT_EXTENSION_NAME, CAPSCORRECT_LAST_CONTACT_OFFSET);
  CRM_Core_BAO_Setting::setItem(0, CAPSCORRECT_EXTENSION_NAME, CAPSCORRECT_TOTAL_CONTACTS_CORRECTED);

  return _capscorrect_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function capscorrect_civicrm_uninstall() {
  return _capscorrect_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function capscorrect_civicrm_enable() {
  return _capscorrect_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function capscorrect_civicrm_disable() {
  return _capscorrect_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function capscorrect_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _capscorrect_civix_civicrm_upgrade($op, $queue);
}

// /**
//  * Implements hook_civicrm_postInstall().
//  *
//  * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
//  */
// function capscorrect_civicrm_postInstall() {
//   _capscorrect_civix_civicrm_postInstall();
// }

// /**
//  * Implements hook_civicrm_entityTypes().
//  *
//  * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
//  */
// function capscorrect_civicrm_entityTypes(&$entityTypes) {
//   _capscorrect_civix_civicrm_entityTypes($entityTypes);
// }
