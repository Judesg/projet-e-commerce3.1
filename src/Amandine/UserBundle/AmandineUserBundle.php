<?php

namespace Amandine\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AmandineUserBundle extends Bundle
{
    public function getParent()
  {
    return 'FOSUserBundle';
  }
}
