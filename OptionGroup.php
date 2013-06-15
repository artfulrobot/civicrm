<?php
namespace ArtfulRobot\CiviCRM;

/** 
 * @file helps finding option group info
 * @copyright Rich Lott 2013
 * @licence - see licence.txt
 */

class OptionGroup
{
	public static function getGroupId($group_name)
	{
        $params = array(
                'version' => 3,
                'sequential' => 1,
                'name' => $group_name,
                );
        $result = civicrm_api('OptionGroup', 'get', $params);

        if ($result['is_error'] || $result['count']!=1) {
            throw new \Exception("OptionGroup '$group_name' not found");
        }

        return $result['values'][0]['id'];
	}
	 /*** @param string $group_name
	 * @return array
	 */
	public static function getValueValue($group_name, $value_name)
	{
        $params = array(
                'version' => 3,
                'sequential' => 1,
                'option_group_id' => OptionGroup::getGroupId($group_name),
                'name' => $value_name,
                );
        $result = civicrm_api('OptionValue', 'get', $params);

        if ($result['is_error'] || $result['count']!=1) {
            throw new \Exception("OptionGroup '$group_name' not found");
        }
        return $result['values'][0]['value'];
	}
}

