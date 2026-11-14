<?php
class Zone implements JsonSerializable
{
    public const DB_TABLE = 'zone';

    private ?int $id = null;
    private string $name;
    private int $classification; // -1=undefined, 0=normal, 1=attention, 2=danger 
    private string $description;
    private float $aire; // en m²
    private array $coordinates = []; // [[lng, lat], [lng, lat], ...]


    public function jsonSerialize(): mixed 
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'classification' => $this->classification,  
            'description' => $this->description,
            'aire' => $this->aire,
            'coordinates' => $this->coordinates
        ];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'classification' => $this->classification,
            'description' => $this->description,
            'aire' => $this->aire,
            'coordinates' => $this->coordinates
        ];
    }

    public function hydrate(array $data): self
    {
        (isset ($data['id'])) ?$this->setId( intval($data['id'])): $this->setId(-1);
        // (isset ($data['layer'])) ?$this->setLayer( $data['layer']): $this->setLayer(''); 
        // (isset ($data['geom'])) ?$this->setGeom( $data['geom'])
        //                         : $this->setGeom([]);  
        (isset ($data['name'])) ?$this->setName( $data['name']): $this->setName('');   
        (isset ($data['description'])) ?$this->setDescription( $data['description']): $this->setDescription('');   
        (isset ($data['classification'])) ?$this->setClassification( intval($data['classification'])): $this->setClassification(-1);   
        (isset ($data['aire'])) ?$this->setAire( floatval($data['aire'])): $this->setAire(0.0);
        (isset ($data['coordinates'])) ?$this->setCoordinates( $data['coordinates']): $this->setCoordinates([]);
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    public function getClassification()
    {
        return $this->classification;
    }
    public function setClassification($classification)
    {
        $this->classification = $classification;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function getCoordinates(): array
    {
        return $this->coordinates;
    }
    public function setCoordinates($coordinates): self
    {
        $this->coordinates = $coordinates;
        return $this;
    }
    public function getAire()
    {
        return $this->aire;
    }
    public function setAire($aire)
    {
        $this->aire = $aire;
        return $this;
    }

  



}
