<?php declare(strict_types=1);

namespace OpenRealEstate\RealEstate\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Entity
 */
class RealEstate
{
    use Timestampable;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @var string
     */
    private $zip;

    /**
     * @ORM\Column(type="integer", length=10)
     * @var int
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="OpenRealEstate\RealEstate\Entity\RealEstateType")
     * @var RealEstateType
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="OpenRealEstate\RealEstate\Entity\ReconstructionType")
     * @var ReconstructionType
     */
    private $reconstructionType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip)
    {
        $this->zip = $zip;
    }

    public function getArea(): ?int
    {
        return $this->area;
    }

    public function setArea(int $area)
    {
        $this->area = $area;
    }

    public function getType(): ?RealEstateType
    {
        return $this->type;
    }

    public function setType(RealEstateType $type)
    {
        $this->type = $type;
    }

    public function getReconstructionType(): ?ReconstructionType
    {
        return $this->reconstructionType;
    }

    public function setReconstructionType(ReconstructionType $reconstructionType)
    {
        $this->reconstructionType = $reconstructionType;
    }
}
