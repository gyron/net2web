<?php declare( strict_types=1 );

namespace Gyron\Net2Web;

use Gyron\Net2Web\Object\AccessLevel;
use Gyron\Net2Web\Object\Area;
use Gyron\Net2Web\Object\Card;
use Gyron\Net2Web\Object\CardType;
use Gyron\Net2Web\Object\Department;
use Gyron\Net2Web\Object\Event;
use Gyron\Net2Web\Object\Operator;
use Gyron\Net2Web\Object\User;

/**
 * Class Client
 * @package Gyron\Net2Web
 */
class Client {

	use Request {
		send as protected sendRequest;
	}

	/**
	 * @var Session
	 */
	protected $oSession;

	/**
	 * @param Session $oSession
	 */
	public function __construct( Session $oSession ) {
		$this->oSession = $oSession;
	}

	/**
	 * @return string
	 */
	public function adminLastErrorMessage(): string {
		$oXmlResult = $this->send( 'lasterrormessage' );
		return (string)($oXmlResult->item->value);
	}


	/**
	 * @param int $nOperatorId
	 * @return int
	 */
	public function adminOperatorLevel( int $nOperatorId ): int {
		$oXmlResult = $this->send( 'getoperatorlevel', array( 'userid' => $nOperatorId ) );
		return (int)($oXmlResult->item->value);
	}

	/**
	 * @return Operator[]
	 */
	public function adminOperatorList(): array {
		$oXmlOperators = $this->send( 'getlistofoperators' );
		$aOperators = [];
		foreach ( $oXmlOperators->OperatorSet->Operator as $oXmlOperator ) {
			$aOperators[] = ( new Operator( $oXmlOperator ) );
		}
		return $aOperators;
	}

	/**
	 * Example response format: 17-07-2017 11:55:19
	 * @return string
	 */
	public function adminServerLastChecked(): string {
		$oXmlResult = $this->send( 'serverlastchecked' );
		return (string)($oXmlResult->item->value);
	}

	/**
	 * @return string[]
	 */
	public function adminTablenameList(): array {
		$oXmlQuery = $this->queryDb( "SELECT Distinct TABLE_NAME FROM information_schema.TABLES" );
		$aTables = [];
		foreach ( $oXmlQuery->NewDataSet->Table as $oXmlTable ) {
			$aTables[] = (string)($oXmlTable->TABLE_NAME);
		}
		return $aTables;
	}

	/**
	 * @param string $sTablename
	 * @return array
	 */
	public function adminRecordList( string $sTablename ): array {
		$oXmlQuery = $this->queryDb( sprintf( "SELECT * FROM %s", $sTablename ) );
		$aRecords = [];
		foreach ( $oXmlQuery->NewDataSet->Table as $oXmlTable ) {
			$aRecord = [];
			foreach ( get_object_vars( $oXmlTable ) as $sPropName => $mPropValue ) {
				$aRecord[(string)$sPropName] = (string)$mPropValue;
			}
			$aRecords[] = $aRecord;
		}
		return $aRecords;
	}

	/**
	 * @param array $aUser
	 * @return User
	 * @throws \Exception
	 */
	public function createUserRecord( array $aUser ): User {
		$sUniqueId = uniqid();
		//$aUser[''] todo fix activation date
		$aUser['field14'] = $sUniqueId;
		$oXmlResult = $this->send( 'adduserrecord', $aUser );

		$bIsSuccess = ( strtolower( (string)$oXmlResult->item->value ) == 'true' );
		if ( !$bIsSuccess ) {
			throw new \Exception( 'Failed to create new user record' );
		}
		$oXmlUser = $this->viewUserRecordByUniqueId( $sUniqueId );
		$nNewUserId = (int)((string)$oXmlUser->UsersSet->User->UserID);

		$this->updateUserRecord( $nNewUserId, array( 'field14' => '' ) );

		$oXmlUser = $this->viewUserRecord( $nNewUserId );
		return $oXmlUser;
	}

	/**
	 * @param User $oUser
	 * @return User
	 * @throws \Exception
	 */
	public function updateUserRecord( User $oUser ): User {
		$oXmlResult = $this->send( 'updateuserrecord', $oUser->toQueryData() );

		$bIsSuccess = ( strtolower( (string)$oXmlResult->item->value ) == 'true' );
		if ( !$bIsSuccess ) {
			throw new \Exception( 'Failed to update user record' );
		}
		$oUser = $this->viewUserRecord( $oUser->getUserId() );
		return $oUser;
	}

	/**
	 * Using querydb instead of the "viewaccessleveldetail" method, as a list of areas isn't useful to us.
	 *
	 * @param int $nAccessLevelId
	 * @return AccessLevel
	 */
	public function viewAccessLevel( int $nAccessLevelId ): AccessLevel {
		$oXmlQuery = $this->queryDb( sprintf( "SELECT * FROM AccessLevels WHERE AccessLevelID=%s", $nAccessLevelId ) );
		return ( new AccessLevel( $oXmlQuery->NewDataSet->Table ) );
	}

	/**
	 * @return AccessLevel[]
	 */
	public function viewAccessLevels(): array {
		$oXmlAccessLevels = $this->send( 'viewaccesslevels' );
		$aAccessLevels = [];
		foreach ( $oXmlAccessLevels->AccessLevelsSet->AccessLevel as $oXmlAccessLevel ) {
			$aAccessLevels[] = ( new AccessLevel( $oXmlAccessLevel ) );
		}
		return $aAccessLevels;
	}

	/**
	 * @param int $nUserId
	 * @return Card[]
	 */
	public function viewCards( ?int $nUserId = null ): array {
		$oXmlQuery = $this->queryDb( sprintf( "SELECT * FROM Cards %s", is_null( $nUserId )? '': ' WHERE UserID = '.$nUserId ) );
		$aCards = [];
		foreach ( $oXmlQuery->NewDataSet->Table as $oXmlTable ) {
			$aCard = [];
			foreach ( get_object_vars( $oXmlTable ) as $sPropName => $mPropValue ) {
				$aCard[(string)$sPropName] = (string)$mPropValue;
			}
			$aCards[] = $aCard;
		}
		return $aCards;
	}

	/**
	 * @return CardType[]
	 */
	public function viewCardTypes(): array {
		$oXmlCardTypes = $this->send( 'viewcardtypes' );
		$aCardTypes = [];
		foreach ( $oXmlCardTypes->CardTypesSet->CardType as $oXmlCardType ) {
			$aCardTypes[] = ( new CardType( $oXmlCardType ) );
		}
		return $aCardTypes;
	}

	/**
	 * @return Department[]
	 */
	public function viewDepartments(): array {
		$oXmlDepartments = $this->send( 'viewdepartments' );
		$aDepartments = [];
		foreach ( $oXmlDepartments->DepartmentsSet->Department as $oXmlDepartment ) {
			$aDepartments[] = ( new Department( $oXmlDepartment ) );
		}
		return $aDepartments;
	}

	/**
	 * @param int $nEventId
	 * @return Event
	 * @throws \Exception
	 */
	public function viewEvent( int $nEventId ): Event {
		$aEvents = $this->viewEvents( sprintf( "EventID = %s", $nEventId ) );
		if ( empty( $aEvents ) ) {
			throw new \Exception( sprintf( 'Failed to find event ID %s', $nEventId ) );
		}
		return $aEvents[0];
	}

	/**
	 * Example queries could be:
	 *  1. "EventID not in (139593)"
	 *  2. "UserID = 341 and EventType = 20 and EventDate = '31/08/2016' and EventTime > '10:26:46'"
	 *
	 * @param string $sQuery
	 * @param string $sSort
	 * @param int $nRows
	 * @return Event[]
	 */
	public function viewEvents( string $sQuery = "EventID > 0", string $sSort = "eventId desc", int $nRows = 1000 ): array {
		//1000, "EventID not in (139593)", "eventId desc"
		$oXmlEvents = $this->send( 'viewevents', array( 'rows' => $nRows, 'query' => $sQuery, 'sort' => $sSort ) );
		$aEvents = [];
		foreach ( $oXmlEvents->item->value->EventsSet->Event as $oXmlEvent ) {
			$aEvents[] = ( new Event( $oXmlEvent ) );
		}
		return $aEvents;
	}

	/**
	 * This is a bug, as it's supposed to return access levels but instead seems to return
	 * the access areas of the level associated to the user.
	 * Not sure how this works when multiple access levels are enabled.
	 *
	 * @param int $nUserId
	 * @return Area[]
	 */
	public function viewUserAccessAreas( int $nUserId ): array {
		$aOptions = is_null( $nUserId )? array(): array( 'userid' => $nUserId );
		$oXmlAreas = $this->send( 'viewaccesslevels', $aOptions );
		$aAreas = [];

		foreach ( $oXmlAreas->IndividualReaderAreasSet->IndividualReaderAreas as $oXmlArea ) {
			$aAreas[] = ( new Area( $oXmlArea ) );
		}
		return $aAreas;
	}

	/**
	 * @param int $nUserId
	 * @return Card[]
	 */
	public function viewUserCards( int $nUserId ): array {
		return $this->viewCards( $nUserId );
	}

	/**
	 * @param int $nUserId
	 * @param int $nLimit
	 * @return Event[]
	 */
	public function viewUserEvents( int $nUserId, int $nLimit = 20 ): array {
		$oXmlEvents = $this->send( 'viewevents', array( 'rows' => $nLimit, 'query' => "UserID = ".$nUserId, 'sort' => "eventId desc" ) );
		$aEvents = [];
		foreach ( $oXmlEvents->item->value->EventsSet->Event as $oXmlEvent ) {
			$aEvents[] = ( new Event( $oXmlEvent ) );
		}
		return $aEvents;
	}

	/**
	 * @param int $nUserId
	 * @return User
	 */
	public function viewUserRecord( int $nUserId ): User {
		$oXmlUser = $this->send( 'viewuserrecords', array( 'sqlwhere' => sprintf( "userid = %s", $nUserId ) ) );
		return ( new User( $oXmlUser->UsersSet->User ) );
	}

	/**
	 * @param string $sUniqueId
	 * @return User
	 */
	public function viewUserRecordByUniqueId( string $sUniqueId ): User {
		$oXmlUser = $this->send( 'viewuserrecords', array( 'sqlwhere' => sprintf( "field14_50 = '%s'", $sUniqueId ) ) );
		return ( new User( $oXmlUser->UsersSet->User ) );
	}

	/**
	 * @return User[]
	 */
	public function viewUserRecords(): array {
		$oXmlUsers = $this->send( 'viewuserrecords', array( 'sqlwhere' => "active = 'true'" ) );
		$aUsers = [];
		foreach ( $oXmlUsers->UsersSet->User as $oXmlUser ) {
			$aUsers[] = ( new User( $oXmlUser ) );
		}
		return $aUsers;
	}

	/**
	 * @param string $sQuery
	 * @return \SimpleXMLElement
	 */
	protected function queryDb( string $sQuery ): \SimpleXMLElement {
		$oXmlResult = $this->send( 'querydb', array( 'query' => $sQuery ) );
		return $oXmlResult;
	}

	/**
	 * @param string $sMethod
	 * @param array $aParameters
	 * @return \SimpleXMLElement
	 */
	protected function send( string $sMethod, array $aParameters = array() ): \SimpleXMLElement {
		$aParameters['sid'] = $this->oSession->getSessionId();
		return $this->sendRequest( $this->oSession->getUrl(), $sMethod, $aParameters );
	}
}