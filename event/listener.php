<?php
/**
 *
 *
 * 
 * 
 * 
 * 
 */

namespace christucker\oauth2neoncrm\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * NeonCRM OAuth2 Extension Event Listener
 */
class listener implements EventSubscriberInterface
{
	/**
	 * Assign functions defined in this class to event listeners in the core.
	 *
	 * @static
	 * @return array
	 * @access public
	 */
	public static function getSubscribedEvents()
	{
		return ['core.user_setup_after' => 'neoncrm_setup_lang'];
	}

	/* @var \phpbb\language\language */
	protected $language;

	/**
	 * Constructor.
	 *
	 * @param  \phpbb\language\language $language Language object.
	 * @return void
	 * @access public
	 */
	public function __construct(\phpbb\language\language $language)
	{
		$this->language = $language;
	}

	/**
	 * Load extension language file after the "$user" has been setup.
	 *
	 * @event  core.user_setup_after
	 * @return void
	 * @access public
	 */
	public function neoncrm_setup_lang($event)
	{
		$this->language->add_lang('oauth2neoncrm_common', 'christucker/oauth2neoncrm');
	}
}
