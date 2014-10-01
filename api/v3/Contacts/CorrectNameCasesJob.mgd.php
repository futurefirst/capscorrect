<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference

return array (
  0 => 
  array (
    'name' => 'Cron:Contacts.CorrectNameCasesJob',
    'entity' => 'Job',
    'params' => 
    array (
      'version' => 3,
      'name' => 'Call Contacts.CorrectNameCasesJob API',
      'description' => 'Call Contacts.CorrectNameCasesJob API',
      'run_frequency' => 'Daily',
      'api_entity' => 'Contacts',
      'api_action' => 'CorrectNameCasesJob',
      'parameters' => SCHEDULED_JOB_PARAM_DEFAULT_NAME . "=" . DEFAULT_DAILY_CONTACTS_CORRECTED, 
    ),
  ),
);