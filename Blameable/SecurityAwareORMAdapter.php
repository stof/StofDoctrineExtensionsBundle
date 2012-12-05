<?php

namespace Stof\DoctrineExtensionsBundle\Blameable;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Gedmo\Mapping\Event\Adapter\ORM as BaseAdapterORM;
use Gedmo\Blameable\Mapping\Event\BlameableAdapter;

class SecurityAwareORMAdapter extends BaseAdapterORM implements BlameableAdapter
{
    /** @var SecurityContextInterface */
    private $security;

    public function setSecurityContext(SecurityContextInterface $security = null)
    {
        $this->security = $security;
    }

    public function getUserValue($meta, $field)
    {
        if ($this->security && $token = $this->security->getToken()) {
            $mapping = $meta->getFieldMapping($field);
            if (isset($mapping['type']) && $mapping['type'] == 'string') {
                return $token->getUsername();
            }

            return $token->getUser();
        }

        return null;
    }
}
