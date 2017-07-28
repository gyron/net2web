<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class Event
 * @package Gyron\Net2Web\Object
 */
class Event extends AbstractObject {

	/**
	 * @param \SimpleXMLElement $oXml
	 * @return Event
	 */
	public function fromXml( \SimpleXMLElement $oXml ) {
		$this->aData = array(
        	'eventid' => (string)($oXml->EventID),
			'eventdatetime' => (string)($oXml->EventDateTime),
			'eventdate' => (string)($oXml->EventDate),
			'eventtime' => (string)($oXml->EventTime),
			'eventtype' => (string)($oXml->EventType),
			'eventdescription' => (string)($oXml->EventDescription),
			'eventsubtype' => (string)($oXml->EventSubType),
			'eventsubdescription' => (string)($oXml->EventSubDescription),
			'address' => (string)($oXml->Address),
			'subaddr' => (string)($oXml->SubAddr),
			'devicename' => (string)($oXml->DeviceName),
			'userid' => (string)($oXml->UserID),
			'username' => (string)($oXml->UserName),
			'cardno' => (string)($oXml->CardNo),
			'firstname' => (string)($oXml->FirstName),
			'middlename' => (string)($oXml->MiddleName),
			'surname' => (string)($oXml->Surname),
			'priority' => (string)($oXml->Priority),
			'prioritysortorder' => (string)($oXml->PrioritySortOrder),
			'eventdetails' => (string)($oXml->EventDetails),
			'devicedeleted' => (string)($oXml->DeviceDeleted)
		);
		return $this;
	}
}