<?php
declare(strict_types=1);

namespace AppBundle\Entity\OAuth;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_client_authorizations")
 */
class ClientAuthorization
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user", nullable=false)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OAuth\Client")
     * @ORM\JoinColumn(name="client", nullable=false)
     */
    protected $client;

    /**
     * @ORM\Column(type="array")
     */
    protected $scope;
}
