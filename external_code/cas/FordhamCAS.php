<?php
	/**
	 *
	 *
	 * @copyright All Contents (c) 2010 Jadu Ltd.
	 * @author Jadu Ltd.
	 * @package rupa2
	 */

	require_once('custom/CAS.php');

	class Jadu_Rupa2_Authentication_User_Adapter_FordhamCAS implements Jadu_Rupa2_Authentication_User_Adapter
	{
		private $casStarted = false;

		private function initCAS ()
		{
			if (!$this->casStarted) {
				// Uncomment to enable debugging
				phpCAS::setDebug('/var/www/jadu/logs/cas_log');

				// Initialize phpCAS
				//phpCAS::client(CAS_VERSION_2_0, 'erp-casqa01.erp.fordham.edu', 443, '/cas');
				phpCAS::client(CAS_VERSION_2_0, 'login.fordham.edu',443,'/cas');

				phpCAS::setCasServerCACert('/etc/openldap/certs/cas.pem');

				$this->casStarted = true;
			}
		}

		public function credentialsSubmitted ()
		{
			return false;
		}

		private function promptForCredentials ()
		{
			$this->initCAS();
			phpCAS::forceAuthentication();
		}

		/**
		 * Authenticate the identity and credential and return the ID of the User
		 * record if correct. If incorrect NULL is returned.
		 *
		 * @param string $identity
		 * @param string $credential
		 * @return integer
		 */
		public function authenticate($identity, $credential)
		{
			return -1;
		}

		/**
		 * Save the User and targeting rule answers.
		 *
		 * @param User $user The User to be saved
		 * @param array $answers The targeting rule answers. Pass NULL to not save the answers
		 * @return integer
		 */
		public function save(User $user, array $answers = array())
		{
			return -1;
		}

		/**
		 * Update a user's password in the database table.
		 *
		 * The function expects an unhashed password which it will hash before
		 * updating the row.
		 *
		 * @param string $password The unhashed password to add.
		 */
		public function updatePassword($userID, $password)
		{
		}

		/**
		 * Update the access level of the user with the given ID
		 *
		 * @param integer $userID
		 * @param integer $accessLevel
		 */
		public function updateAccessLevel($userID, $accessLevel)
		{
			return -1;
		}

		/**
		* Send the password to the provided email address.
		*
		* @param booleon $registered If the user is registered or not
		*/
		public function sendPassword($identity)
		{
		}

		/**
		* Deletes a user from the database with the specified id.
		*
		* @param integer $id The Id of the user to delete.
		*/
		public function delete($userID)
		{
		}

		/**
		* Returns the current number of users in the database.
		*
		* @return integer The number of users
		*/
		public function getNumUsers()
		{
			return -1;
		}

		/**
		* Returns the number of users who are found with the search string.
		*
		* @param string $query The term to search for.
		* @return integer The number of user's found.
		*/
		public function getNumUsersQuery($query)
		{
			return -1;
		}

		/**
		* Returns the number of users who have registered since
		* the interval specified.
		*
		* @param integer $interval The interval in which to count the users:
		*							24 = 1 day,
		*							7 = 1 week,
		*							30 = 30 days,
		*							6 = 6 months,
		*							12 = 1 year.
		*
		* @return integer The number of users.
		*/
		public function getNumUsersRecent($interval)
		{
			return -1;
		}

		/**
		* Returns the number of users who have registered since the interval
		* specified and authenticated.
		*
		* @param integer $interval The interval in which to count the users:
		*							24 = 1 day,
		*							7 = 1 week,
		*							30 = 30 days,
		*							6 = 6 months,
		*							12 = 1 year.
		*
		* @return integer The number of users.
		*/
		public function getNumConfirmedUsersRecent($interval)
		{
			return -1;
		}

		/**
		* Retrieves all of the users from the database.
		*
		* @param string $orderBy defaults to 'id'.
		* @param string $orderDirection defaults to 'ASC'.
		* @return array[]User All of the User objects.
		*/
		public function getAllUsers($orderBy = 'id', $orderDirection = 'ASC')
		{
			return array();
		}

		/**
		* Retrieves a specified number users from the database.
		*
		* @param integer $lower The position in the database to start from.
		* @param integer $amount The number of records to retrieve.
		* @param string $orderBy defaults to 'id'.
		* @param string $orderDirection defaults to 'ASC'.
		* @return array[]User User objects.
		*/
		public function getAllUsersLimit($offset, $limit, $orderBy = 'id', $orderDirection = 'ASC')
		{
			return array();
		}

		/**
		* Retrieves a specified number users from the database based on a search
		* term.
		*
		* @param integer $lower The position in the database to start from.
		* @param integer $amount The number of records to retrieve.
		* @param string $query The search term.
		* @param string $orderBy defaults to 'id'.
		* @param string $orderDirection defaults to 'ASC'.
		* @return array[]User User objects.
		*/
		public function getAllUsersLimitQuery($offset, $limit, $query, $orderBy = 'id', $orderDirection = 'ASC')
		{
			return array();
		}

		/**
		* Retrieves a specified number users from the database that have
		* registered in the interval specified.
		*
		* @param integer $lower The position in the database to start from.
		* @param integer $amount The number of records to retrieve.
		* @param integer $interval The interval in which to get the users:
		*							24 = 1 day,
		*							7 = 1 week,
		*							30 = 30 days,
		*							6 = 6 months,
		*							12 = 1 year.
		*
		* @return array[]User User objects.
		*/
		public function getAllUsersLimitRecent($offset, $limit, $interval)
		{
			return array();
		}

		/**
		* Retrieves all users who have agreed to be contacted by email.
		*
		* @return array[]User An array of User objects.
		*/
		public function getAllContactableUsers()
		{
			return array();
		}

		/**
		* Retrieves a single User from the database.
		*
		* @param integer $id The Id of the user to retrieve
		* @return RupaUser The requested user record.
		*/
		public function getUser($id)
		{
			return getRupaUser($id);
		}

		/**
		 * Retrieves the User ID by the identity
		 *
		 * @param string $identity
		 * @return integer
		 */
		public function getUserIdByIdentity($identity)
		{
			$user = getRupaUserByUID($identity);
			return $user->id;
		}

		/**
		 * Retrieves the User by the identity
		 *
		 * @param string $identity
		 * @return RupaUser
		 */
		public function getUserByIdentity($identity)
		{
			return getRupaUserByUID($identity);
		}

		/**
		 * Whether new registrations are excepted
		 *
		 * @return boolean
		 */
		public function canRegister()
		{
			return false;
		}

		/**
		 * Whether or not users can sign out
		 *
		 * @return boolean
		 */
		public function canSignOut()
		{
			return true;
		}

		/**
		 * Whether users can update their details from within Jadu
		 *
		 * @return boolean
		 */
		public function canUpdate()
		{
			return false;
		}

		/**
		 * Whether users can update their password from within Jadu
		 *
		 * @return void
		 * @author Tom Graham
		 */
		public function canUpdatePassword()
		{
			return false;
		}

		/**
		* Whether users can view the site without logging in
		*
		* @return boolean
		*/
		public function allowAnonymousAccess()
		{
			   return true;
		}


		/**
		 * Whether the ID returned by the authenticate method is the primary key of
		 * a record in the JaduUsers table. If false is returned then a new JaduUsers
		 * link record will be created upon each unique login.
		 *
		 * @return boolean
		 */
		public function isStandard()
		{
			return false;
		}

		/**
		 * Whether the identity is the user's e-mail address
		 *
		 * @return string
		 */
		public function isIdentityEmail()
		{
			return false;
		}


		public function authenticateUser($identity, $credential, $requireEmailConfirmation = false)
		{

		}

		public function logSessionIn($userID, $externalID = null)
		{
			global $PREVIEW;

			$_SESSION[getRupaSessionVarName('userID', $PREVIEW)] = $userID;
		}

		public function logFailedAttempt($identity, $failurecode)
		{

		}

		public function logSessionOut()
		{
			$this->initCAS();
			unset($_SESSION[getRupaSessionVarName('userID', $PREVIEW)]);
			phpCAS::logout();
		}

		public function isSessionLoggedIn()
		{
			$this->initCAS();
			return phpCAS::isAuthenticated();
		}

		public function startSession()
		{
			//$this->initCAS();
        }

		public function getSessionUserID()
		{
			global $PREVIEW;

			$this->initCAS();

			$uid = '';

			if ($this->isSessionLoggedIn()) {
				if (isset($_SESSION[getRupaSessionVarName('userID', $PREVIEW)])) {
					return $_SESSION[getRupaSessionVarName('userID', $PREVIEW)];
				}
				else {
					$user = getRupaUserByLDAPUserID(phpCAS::getUser());
					if ($user->id > 0) {
						$user->lastVisited = date('Y-m-d H:i:s');
						$user->update();
					}
					else {
						$uid = generateRupaUserUID();
						$user->uid = $uid;
						$user->lastVisited = date('Y-m-d H:i:s');
						$user->ldapUserID  = phpCAS::getUser();
						$user->id = $user->insert();
					}

					$this->logSessionIn($user->uid);

					$uid = $user->uid;
				}
			}

			return $uid;
		}

		public function getWelcomeMessage(RupaUser $user, $htmlEncode = true)
		{
			$this->initCAS();

			$message = '';
			if ($this->isSessionLoggedIn()) {
				$message = phpCAS::getUser();
			}

			return encodeHtml($message);
		}

		public function getSignInURL ()
		{
			//$this->initCAS();
			return phpCAS::getServerLoginURL();
		}

		public function getRegisterURL ()
		{
			return '';
		}

		function deleteExpiredUsers ()
		{

		}
	}
