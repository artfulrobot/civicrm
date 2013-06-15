<?php
namespace ArtfulRobot\CiviCRM;

/** 
 * @file class for fetching info on CiviCRM custom groups
 * @copyright Rich Lott 2013
 * @licence - see licence.txt
 */

class CustomGroup
{
	/** fetch useful information on a custom group given its machine name
     *  The machine name is from civicrm_custom_group.name field.
     *
     *
	 * output: array(
	 *        'custom_group_id' => (int),
	 *        'table_name'      => (string),
	 *        'fields' => array(
	 *            (string) {field label} => array(
	 *                'custom_field_id' => (int),
	 *                'column_name' => (int),
	 *                'option_group_id' => (int)
	 *                )))
	 *
	 * @param string $group_name
	 * @return array
	 */
	public static function getDetails($group_name)
	{
		$results = array();
		$params = array('name'=>$group_name);
		\CRM_Core_BAO_CustomGroup::retrieve( $params, $results );

        if (empty($results['id'])) {
            throw new \Exception("Custom Group '$group_name' not found");
        }

		$out = array('custom_group_id'=>$results['id'],
				'table_name' => $results['table_name'],
				'fields' => array());

		// now look up fields
		$results = array();
		$fld = new \CRM_Core_BAO_CustomField();
		$fld->custom_group_id = $out['custom_group_id'];
		$fld->find();
		while ($fld->fetch())
		{
			$out['fields'][$fld->label] = array(
					'custom_field_id' => $fld->id,
					'column_name'     => $fld->column_name,
					'option_group_id' => $fld->option_group_id);
		}
		$fld->free();
		return $out;
	}
}

