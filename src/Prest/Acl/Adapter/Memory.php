<?php

namespace Prest\Acl\Adapter;

use Prest\Acl\MountingEnabledAdapterInterface;

class Memory extends \Phalcon\Acl\Adapter\Memory implements MountingEnabledAdapterInterface
{
    use \AclAdapterMountTrait;
}
