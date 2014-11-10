<?php

namespace OpenAuth;

use Zend\EventManager\Event;

class OpenAuthEvent extends Event{
	const EVENT_LOGIN_CALLBACK = 'openauth.login.callback';
}
