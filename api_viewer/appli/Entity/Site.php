<?php
class Site
{
    private ?int $id = null;
    private string $name;
    private string $city;
    private ?string $img = null;
    // private ?int $pointsId = null;
    private array $coordinates = []; // [lng, lat]

     
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'img' => $this->img,
            'coordinates' => $this->coordinates
        ];
    }

    public function hydrate(array $data): self
    {
        (isset ($data['id'])) ?$this->setId( intval($data['id'])): $this->setId(-1);
        (isset ($data['name'])) ?$this->setName( $data['name']): $this->setName('');
        (isset ($data['city'])) ?$this->setCity( $data['city']): $this->setCity('');
        (isset ($data['img'])) ?$this->setImg( $data['img']): $this->setImg('');
        (isset ($data['coordinates'])) ?$this->setCoordinates( $data['coordinates']): $this->setCoordinates([]);
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    } 
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
   public function getCity(): string
    {
        return $this->city;
    }
    public function setCity(string $city): void
    {
        $this->city = $city;
    }
    /**
     * Get the value of img
     */ 
    public function getImg(): ?string
    {
        return $this->img;
    }

    /**
     * Set the value of img
     *
     * @return  self
     */ 
    public function setImg($img): self
    {
        $this->img = $img;

        return $this;
    }
    public function getCoordinates()
    {
        return $this->coordinates;
    }
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
        return $this;
    }
    // public function getPointsId(): ?int
    // {
    //     return $this->pointsId;
    // }
    // public function setPointsId(?int $pointsId): void
    // {
    //     $this->pointsId = $pointsId;
    // }


    
}