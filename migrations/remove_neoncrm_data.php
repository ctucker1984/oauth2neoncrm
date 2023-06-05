<?php
/**
 * 
 * Chris Tucker - NeonCRM OAuth2 Extension
 * 
 * 
 * 
 * 
 */

 namespace christucker\oauth2neoncrm\migrations;

 /**
  * 'container_aware_migration' will give us access to $this->container
  */
  class remove_neoncrm_data extends \phpbb\db\migration\container_aware_migration
  {
    /**
     * Assign migration dependencies
     * 
     * @return array    Array of migration files
     * @access public
     * @static
     */
    static public function depends_on()
    {
        return ['\phpbb\db\migration\data\v32x\v327'];
    }

	/**
	 * Assign reverting actions.
	 *
	 * @return array		Array of reverting actions
	 * @access public
	 */
	public function revert_data()
	{
		return [
			['custom', [[$this, 'remove_neoncrm_oauths_garbage']]],
		];
	}

	/**
	 * Remove all GitHub's OAuth data from the OAuth tables.
	 *
	 * @return void
	 * @access public
	 */
	public function remove_neoncrm_oauths_garbage()
	{
		$tokens		= $this->container->getParameter('tables.auth_provider_oauth_token_storage');
		$states		= $this->container->getParameter('tables.auth_provider_oauth_states');
		$accounts	= $this->container->getParameter('tables.auth_provider_oauth_account_assoc');

		$table_ary = [
			$tokens		=> 'auth.provider.oauth.service.neoncrm',
			$states		=> 'auth.provider.oauth.service.neoncrm',
			$accounts	=> 'neoncrm',
		];

		$this->db->sql_transaction('begin');

		foreach ($table_ary as $table => $provider)
		{
			$sql = 'DELETE FROM ' . $table . "
					WHERE provider = '" . $this->db->sql_escape($provider) . "'";
			$this->sql_query($sql);
		}

		$this->db->sql_transaction('commit');
	}
  }