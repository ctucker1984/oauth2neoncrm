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

 class install_config extends \phpbb\db\migration\migration
 {
    public function effectively_installed()
    {
        return $this->config->offsetExists( 'auth_oauth_neoncrm_key' );
    }

	public static function depends_on()
	{
		return array('\phpbb\db\migration\data\v32x\v327');
	}

	/**
	 * Add, update or delete data stored in the database during extension installation.
	 *
	 * @return array Array of data update instructions
	 */
	public function update_data()
	{
		return array(
            array( 'config.add', array( 'auth_oauth_neoncrm_org_id', '' ) ),
            array( 'config.add', array( 'auth_oauth_neoncrm_client_id', '' ) ),
            array( 'config.add', array( 'auth_oauth_neoncrm_secret', '' ) ),
            array( 'config.add', array( 'auth_oauth_neoncrm_api_key', '' ) ),
		);
	}
 }