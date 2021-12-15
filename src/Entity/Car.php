<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Car
 * @package App\Entity
 *
 * @ORM\Entity
 */
class Car
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank
     * @var string
     */
    private string $make;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank
     * @var string
     */
    private string $model;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Assert\NotBlank
     * @var \DateTime
     */
    private \DateTime $buildDate;
    private const MAX_CAR_AGE_YEARS = 4;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CarColour", fetch="EAGER", inversedBy="cars")
     * @ORM\JoinColumn(name="colour_id", referencedColumnName="id")
     * @Assert\NotBlank
     */
    private $colour;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMake(): string
    {
        return $this->make;
    }

    /**
     * @param string $make
     */
    public function setMake(string $make): void
    {
        $this->make = $make;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    /**
     * @return \DateTime
     */
    public function getBuildDate(): \DateTime
    {
        return $this->buildDate;
    }

    /**
     * @param \DateTime $buildDate
     */
    public function setBuildDate(\DateTime $buildDate): void
    {
        $currentDate = new \DateTime();
        $dateDiff = $buildDate->diff($currentDate)->format('%a');

        if($dateDiff > $this::MAX_CAR_AGE_YEARS * 365){
            throw new \Exception(
                'Specified build date is not allowed. (Maximum allowed car age: '.$this::MAX_CAR_AGE_YEARS.' years)'
            );
        }

        $this->buildDate = $buildDate;
    }

    /**
     * @return mixed
     */
    public function getColour()
    {
        return $this->colour;
    }

    /**
     * @param mixed $colour
     */
    public function setColour($colour): void
    {
        $this->colour = $colour;
    }

}