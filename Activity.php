<?php
namespace ArtfulRobot\CiviCRM;

use \ArtfulRobot\CiviCRM as ARLCRM;

/** 
 * @file class for fetching info on CiviCRM activity
 * @copyright Rich Lott 2013
 * @licence - see licence.txt
 *
 * load activity, provide access methods for assignees, targets.
 *
 *
 */

class Activity
{
    protected $activity;
    protected $assignees;
    protected $targets;

    //public function loadFromActivityId($activity_id){{{
    /** create instance from activity_id
     */
    public function loadFromActivityId($activity_id)
    {
        require_once 'sites/default/civicrm.settings.php';
        require_once 'CRM/Core/Config.php';
        require_once 'api/api.php';
        $config = \CRM_Core_Config::singleton( );
        $activity_id = (int) $activity_id;
        if (!($activity_id>0)) {
            throw new \Exception("Activity not found");
        }

        // look up deets.
        $result = civicrm_api('Activity', 'get', array(
                    'version' => 3,
                    'sequential' => 1, // index from 0 not by id
                    'activity_id' => $activity_id
                    ));
        if ($result['is_error']) {
            throw new \Exception("Activity API error");
        }
        if($result['count']) {
          $result = $result['values'][0];
          $this->activity = $result;
        } else {
          throw new \Exception("Activity not found");
        }
    } // }}}

    public function __construct($activity_id=null)/*{{{*/
    {
        $this->activity = null;
        if ($activity_id !== null) $this->loadFromActivityId($activity_id);
    }/*}}}*/

    public function getAssignees()/*{{{*/
    {
        $this->assertActivity();
        if (!$this->assignees) {
            // no cache, so look it up.
            $this->assignees = \CRM_Activity_BAO_ActivityAssignment::getAssigneeNames( $this->activity['id'], true);
        }
        return $this->assignees;
    }/*}}}*/
    public function getTargets()/*{{{*/
    {
        $this->assertActivity();
        if (!$this->targets) {
            // no cache, so look it up.
            $this->targets = \CRM_Activity_BAO_ActivityTarget::getTargetNames( $this->activity['id'], true);
        }
        return $this->targets;
    }/*}}}*/
    public function assertActivity()/*{{{*/
    {
        if (!$this->activity) {
            throw new \Exception("Activity not loaded");
        }
    }/*}}}*/

    /* return htmlspecialchars copy of activity field */
    public function safe($var)
    {
        $this->assertActivity();
        if (empty($this->activity[$var])) {
            return '';
        } else {
            return htmlspecialchars($this->activity[$var]);
        }
    }
}
