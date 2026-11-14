<?php
class Photo implements JsonSerializable
{
    public const DB_TABLE = 'photo';

    private ?int $id = null;
    private string $name;
    private int $etat; // -1=undefined, 0=normal, 1=attention, 2=danger 
    private string $description;
    private array $coordinates = []; // [lng, lat]

    public function jsonSerialize(): mixed 
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'etat' => $this->etat,  
            'description' => $this->description,
            'coordinates' => $this->coordinates
        ];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'etat' => $this->etat,
            'description' => $this->description,
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
        (isset ($data['etat'])) ?$this->setEtat( intval($data['etat'])): $this->setEtat(-1);   
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
    public function getEtat()
    {
        return $this->etat;
    }
    public function setEtat($etat)
    {
        $this->etat = $etat;
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
    public function getCoordinates()
    {
        return $this->coordinates;
    }
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
        return $this;
    }

  



}
