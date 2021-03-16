<?php


namespace App\Entity;


trait IdTrait
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }
}