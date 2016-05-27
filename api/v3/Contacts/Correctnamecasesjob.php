<?php

/**
 * Contacts.CorrectNameCasesJob API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_contacts_correctnamecasesjob_spec(&$spec) {
  // Place holder in case we wish to add some parameters
}

/**
 * This function will set any string that is:
 *  - all uppercase or
 *  - all lowercase or
 *  - contains leading and/or trailing whitespace and
 *  - not empty
 * to be trimmed and capitalised correctly with the first letter on upper case.
 * It is meant to clean contact names.
 * Major credits to Xavier Dutoit (<civicrm@tttp.eu>) and the his extension
 * https://civicrm.org/extensions/normalise-data-entered-firstname-last-name .
 * @param string $aName - a string that should contain the name
 * @return boolean - True if the string got modified and false if it didn't
 */
function contacts_correctnamecasesjob_correct_name_case(&$aName) {
  // See if the name is empty.
  if (strlen($aName) != 0) {
    // Check if the name needs correction.
    if (
      $aName == strtolower($aName) ||
      ($aName == strtoupper($aName) && strlen($aName) > 1) ||
      ($aName != trim($aName) && strlen(trim($aName)) > 0)
    ) {
      // Correct the name by only setting the first letter to upper-case,
      // and trimming leading and trailing whitespace
      $aName = trim($aName);
      $aName = strtoupper($aName[0]) . strtolower(substr($aName, 1));
      // Signal that a change was made
      return TRUE;
    }
  }

  // No change was made
  return FALSE;
}

/**
 * Contacts.CorrectNameCasesJob API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_contacts_correctnamecasesjob($params) {

  $lastContactExaminedOffset = CRM_Core_BAO_Setting::getItem(CAPSCORRECT_EXTENSION_NAME, CAPSCORRECT_LAST_CONTACT_OFFSET);
  // See if the returned value is NULL or negative
  if ( $lastContactExaminedOffset === NULL || $lastContactExaminedOffset < 0 ) {
    throw new API_Exception(/*errorMessage*/ 'Unable to complete scheduled job, the offset was not returned.', /*errorCode*/ 1);
  }
  // Get the number of contacts to correct this run
  $numberOfContactsToRun = DEFAULT_DAILY_CONTACTS_CORRECTED;
  if (isset($params[SCHEDULED_JOB_PARAM_DEFAULT_NAME]) && is_numeric($params[SCHEDULED_JOB_PARAM_DEFAULT_NAME])){
    $numberOfContactsToRun = $params[SCHEDULED_JOB_PARAM_DEFAULT_NAME];
  }

  // Retrieve all the contacts of type individual
  // from the stored offset, with the max number of rows
  $getContactsApiParams = array(
    'version' => 3,
    'sequential' => 1,
    'contact_type' => 'Individual',
    'offset' => $lastContactExaminedOffset,
    'rowCount' => $numberOfContactsToRun,
    'return' => 'first_name, middle_name, last_name',
    'sort' => 'contact_id ASC',
  );
  $getContactsApiResults = civicrm_api('Contact', 'get', $getContactsApiParams);
  if ( civicrm_error($getContactsApiResults) ){
    throw new API_Exception(/*errorMessage*/ 'Unable to complete scheduled job, error fetching contacts.', /*errorCode*/ 2);
  }

  $contactsCorrectedThisRun = 0;
  foreach( $getContactsApiResults['values'] as $aContact ){
    // Correct the names if needed
    $isFirstNameCorrected  = contacts_correctnamecasesjob_correct_name_case($aContact['first_name']);
    $isMiddleNameCorrected = contacts_correctnamecasesjob_correct_name_case($aContact['middle_name']);
    $isLastNameCorrected   = contacts_correctnamecasesjob_correct_name_case($aContact['last_name']);
    // If at least one of the names is altered update the contact
    if ($isFirstNameCorrected || $isMiddleNameCorrected || $isLastNameCorrected) {
      $contactWithCorrectedNameApiParams = array(
        'version'     => 3,
        'id'          => $aContact['id'],
        'first_name'  => $aContact['first_name'],
        'middle_name' => $aContact['middle_name'],
        'last_name'   => $aContact['last_name'],
      );
      $contactWithCorrectedNameApiResults = civicrm_api('Contact', 'create', $contactWithCorrectedNameApiParams);
      // Update the contacts corrected counter
      $contactsCorrectedThisRun++;
    }
  }
  // Update the total contacts corrected setting value
  $totalContactsCorrected = CRM_Core_BAO_Setting::getItem(CAPSCORRECT_EXTENSION_NAME, CAPSCORRECT_TOTAL_CONTACTS_CORRECTED);
  $totalContactsCorrected += $contactsCorrectedThisRun;
  CRM_Core_BAO_Setting::setItem($totalContactsCorrected, CAPSCORRECT_EXTENSION_NAME, CAPSCORRECT_TOTAL_CONTACTS_CORRECTED);

  // Update the offset setting
  $lastContactExaminedOffset += $getContactsApiResults['count'];
  CRM_Core_BAO_Setting::setItem($lastContactExaminedOffset, CAPSCORRECT_EXTENSION_NAME, CAPSCORRECT_LAST_CONTACT_OFFSET);


  // Return values array
  $apiReturnValues = array(
    'text_result' => "The current offset is: $lastContactExaminedOffset."
                   . " The total number of contacts corrected so far is $totalContactsCorrected."
                   . " The total number of contacts corrected in the last run is $contactsCorrectedThisRun",
    'Current offset' => $lastContactExaminedOffset,
    'Total contacts corrected' => $totalContactsCorrected,
    'Contacts corrected this run' => $contactsCorrectedThisRun,
  );

  return civicrm_api3_create_success($apiReturnValues, $params, 'Contacts', 'CorrectNameCasesJob');
}
