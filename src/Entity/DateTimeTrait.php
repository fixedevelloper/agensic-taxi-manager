<?php


namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;

trait DateTimeTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private $date_created;
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private $date_modified;

    /**
     * DateTimeTrait constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->date_created=new \DateTimeImmutable('now',new \DateTimeZone('Africa/Brazzaville'));
        $this->date_modified=new \DateTimeImmutable('now',new \DateTimeZone('Africa/Brazzaville'));
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * @param mixed $date_created
     */
    public function setDateCreated($date_created): void
    {
        $this->date_created = $date_created;
    }

    /**
     * @return mixed
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }

    /**
     * @param mixed $date_modified
     */
    public function setDateModified($date_modified): void
    {
        $this->date_modified = $date_modified;
    }


}
