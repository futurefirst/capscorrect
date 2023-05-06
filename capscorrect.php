<?php

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
  Civi::settings()->set(CAPSCORRECT_LAST_CONTACT_OFFSET, 0);
  Civi::settings()->set(CAPSCORRECT_TOTAL_CONTACTS_CORRECTED, 0);

  return _capscorrect_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function capscorrect_civicrm_enable() {
  return _capscorrect_civix_civicrm_enable();
}
