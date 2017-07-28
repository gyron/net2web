<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class User
 * @package Gyron\Net2Web\Object
 */
class User extends AbstractObject {

	/**
	 * @var array
	 */
	protected $aMetaData = array();

	/**
	 * @var int
	 */
	protected $nUserId = null;

	/**
	 * @param \SimpleXMLElement $oXml
	 * @return User
	 */
	public function fromXml( \SimpleXMLElement $oXml ) {
		$this->nUserId = (int)( (string)$oXml->UserID );

		$this->aData = array(
			'firstname' => (string)$oXml->FirstName,
			'middlename' => (string)$oXml->MiddleName,
			'surname' => (string)$oXml->Surname,
			'telephoneno' => (string)$oXml->Telephone,
			'telephoneextension' => (string)$oXml->Extension,
			'pincode' => (string)$oXml->PIN,
			//'cardnumber' => '',
			//'cardtypeid' => '',
			'active' => strtolower( (string)$oXml->Active ) == 'true',
			'faxno' => (string)$oXml->Fax,
			'activationdate' => strtotime( (string)$oXml->ActivateDate ),
			'accesslevelid' => (string)$oXml->AccessLevelID,
			'departmentid' => (string)$oXml->DepartmentID,
			'antipassbackind' => strtolower( (string)$oXml->AntiPassbackUser ) == 'true',
			'alarmuserind' => strtolower( (string)$oXml->AlarmUser ) == 'true',
			'expirydate' => strtotime( (string)$oXml->ExpiryDate ),
			//'picturefilename' => '',
			//'userpicture' => '',
			'field1' => (string)$oXml->Field1_100,
			'field2' => (string)$oXml->Field2_100,
			'field3' => (string)$oXml->Field3_50,
			'field4' => (string)$oXml->Field4_50,
			'field5' => (string)$oXml->Field5_50,
			'field6' => (string)$oXml->Field6_50,
			'field7' => (string)$oXml->Field7_50,
			'field8' => (string)$oXml->Field8_50,
			'field9' => (string)$oXml->Field9_50,
			'field10' => (string)$oXml->Field10_50,
			'field11' => (string)$oXml->Field11_50,
			'field12' => (string)$oXml->Field12_50,
			'field13' => (string)$oXml->Field13_Memo,
			'field14' => (string)$oXml->Field14_50
		);

		$this->aMetaData = array(
			'userguid' => (string)$oXml->UserGUID,
			'username' => (string)$oXml->UserName,
			'global' => strtolower( (string)$oXml->Global ) == 'true',
			'lockdownexempt' => strtolower( (string)$oXml->LockDownExempt ) == 'true',
			'isaccessleveluser' => strtolower( (string)$oXml->IsAccessLevelUser ) == 'true',
			'departmentname' => (string)$oXml->DepartmentName,
			'accesslevelname' => (string)$oXml->AccessLevelName,
			'staffcategoryid' => (string)$oXml->StaffCategoryID,
			'activatedate' => strtotime( (string)$oXml->ActivateDate ),
			'lastaccesstime' => strtotime( (string)$oXml->LastAccessTime ),
			'lastupdated' => strtotime( (string)$oXml->LastUpdated ),
			'lastareaid' => (string)$oXml->LastAreaID,
			'lastarea' => (string)$oXml->LastArea
		);
		return $this;
	}

	/**
	 * @return array
	 */
	public function getMetaData(): array {
		return $this->aMetaData;
	}

	/**
	 * @return int
	 */
	public function getUserId(): int {
		return $this->nUserId;
	}

	/**
	 * @return array
	 */
	public function toQueryData(): array {
		$aData = $this->aData;

		$aData['userid'] = (string)$this->nUserId;
		if ( $aData['expirydate'] ) {
			$aData['expirydate'] = gmdate( 'm/d/Y', $aData['expirydate'] );
		}
		else {
			unset( $aData['expirydate'] );
		}

		// required to force a no expiration date; decide how to handle more elegantly
		// $aData['expirydate'] = '01/01/1753';

		$aData['activationdate'] = gmdate( 'm/d/Y', $aData['activationdate'] );
		$aData['active'] = $aData['active'] === true? 'true': 'false';
		$aData['alarmuserind'] = $aData['alarmuserind'] === true? 'true': 'false';
		$aData['antipassbackind'] = $aData['antipassbackind'] === true? 'true': 'false';

		return $aData;
	}
}