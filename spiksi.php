<?php

require_once 'spiksi.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function spiksi_civicrm_config(&$config) {
  _spiksi_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function spiksi_civicrm_xmlMenu(&$files) {
  _spiksi_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function spiksi_civicrm_install() {
  _spiksi_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function spiksi_civicrm_uninstall() {
  _spiksi_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function spiksi_civicrm_enable() {
  _spiksi_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function spiksi_civicrm_disable() {
  _spiksi_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function spiksi_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _spiksi_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function spiksi_civicrm_managed(&$entities) {
  _spiksi_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function spiksi_civicrm_caseTypes(&$caseTypes) {
  _spiksi_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function spiksi_civicrm_angularModules(&$angularModules) {
_spiksi_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function spiksi_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _spiksi_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * @param array $params
 */
function spiksi_civicrm_speakciviParams(&$params) {
  if (!$params) {
    CRM_Core_Error::debug_var('$params empty', $params);
  }
}

function spiksi_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if ($objectName == 'Individual' && $op == 'create') {

    /* Assumptions: civicrm_campaign.id = civicrm_campaign.external_identifier */
    $sourceCampaignExternalIdMap = array(
      'speakciviform #1' => 1, /* Register on english newsletter-EN */
    );

    $params = array(
      1 => array($objectId, 'Integer'),
    );

    $query = 'SELECT id, created_date FROM civicrm_contact c WHERE c.id = %1';
    $contact = CRM_Core_DAO::executeQuery($query, $params);
    $contact->fetch();

    // fixme There is not possible to get email or country on this step because they don't exist yet!
    $email = '';
    $country = '';

    CRM_Core_Error::debug_var('$objectRef', $objectRef);
    $param = (object)array(
      'action_type' => 'petition',
      'action_technical_type' => 'people4soil.eu:register',
      'create_dt' => $contact->created_date,
      'action_name' => 'create-contact',
      'external_id' => $sourceCampaignExternalIdMap[$objectRef->source],
      'cons_hash' => (object)array(
        'firstname' => $objectRef->first_name,
        'lastname' => $objectRef->last_name,
        'emails' => array(
          0 => (object)array(
            'email' => $email,
          )
        ),
        'addresses' => array(
          0 => (object)array(
            'zip' => '['.$country.']',
            'country' => $country,
          ),
        ),
      ),
    );
    CRM_Core_Error::debug_var('$param spiksi_civicrm_post', $param);

    // todo call speakcivi endpoint by curl with params in $_POST
  }
}
