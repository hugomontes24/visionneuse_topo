<?php
class PhotoPatchDTO implements JsonSerializable
{
    public const DB_TABLE = 'photo';

    private ?int $id = null;
    private int $etat; // -1=undefined, 0=normal, 1=attention, 2=danger 
    private string $description;


    public function jsonSerialize(): mixed 
    {
        return [
            'id' => $this->id,
            'etat' => $this->etat,  
            'description' => $this->description
        ];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'etat' => $this->etat,
            'description' => $this->description,
        ];
    }

    public function hydrate(array $data): self
    {
        (isset ($data['id'])) ?$this->setId( intval($data['id'])): $this->setId(-1);
        (isset ($data['description'])) ?$this->setDescription( $data['description']): $this->setDescription('');   
        (isset ($data['etat'])) ?$this->setEtat( intval($data['etat'])): $this->setEtat(-1);   
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
  



}
