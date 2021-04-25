<?php

namespace App\Entity;

use App\Repository\CryptoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CryptoRepository::class)
 */
class Crypto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    public $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $symbol;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percent_change_1h;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percent_change_24h;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percent_change_7d;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="integer")
     */
    private $cryptoId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $wallet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getPercentChange1h(): ?float
    {
        return $this->percent_change_1h;
    }

    public function setPercentChange1h(?float $percent_change_1h): self
    {
        $this->percent_change_1h = $percent_change_1h;

        return $this;
    }

    public function getPercentChange24h(): ?float
    {
        return $this->percent_change_24h;
    }

    public function setPercentChange24h(?float $percent_change_24h): self
    {
        $this->percent_change_24h = $percent_change_24h;

        return $this;
    }

    public function getPercentChange7d(): ?float
    {
        return $this->percent_change_7d;
    }

    public function setPercentChange7d(?float $percent_change_7d): self
    {
        $this->percent_change_7d = $percent_change_7d;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getCryptoId(): ?int
    {
        return $this->cryptoId;
    }

    public function setCryptoId(int $cryptoId): self
    {
        $this->cryptoId = $cryptoId;

        return $this;
    }

    public function getWallet(): ?int
    {
        return $this->wallet;
    }

    public function setWallet(?int $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }
}
