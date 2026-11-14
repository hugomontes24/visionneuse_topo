<?php
class Tampon implements JsonSerializable
{
    private ?int $id = null;
    private ?string $matricule = '';
    private ?int $siteId = null;
    private int $type=0; // 0=autre, 1=circulaire, 2=rectangulaire, 3=carré
    private int $reseau=0; // 0=autre,1=EP, 2=EU, 3=EA, 4=EV, 5=ECL, 6=GNV
    private string $description;
    private float $rotation; // en degrés 0 - 360
    private array $amontIds = []; // [id1, id2, ...]
    private array $avalIds = []; // [id1, id2, ...]
    private ?array $coordinatesCc = null; // [x, y, z]
    private ?array $coordinatesWgs84 = null; // [lng, lat,z]

    public function toArray(): array
    {
       return [
            'id' => $this->id,
            'matricule' => $this->matricule,
            'siteId' => $this->siteId,
            'type' => $this->type,
            'reseau' => $this->reseau,
            'description' => $this->description,
            'rotation' => $this->rotation,
            'amontIds' => $this->amontIds,
            'avalIds' => $this->avalIds,
            'coordinatesCc' => $this->coordinatesCc,
            'coordinatesWgs84' => $this->coordinatesWgs84
        ];
    }

  /**
     * Cette fonction est utilisée pour sérialiser l'objet en JSON. Elle est appelée automatiquement par json_encode().
     * @return mixed
     */
    public function jsonSerialize(): mixed 
    {
        return $this->toArray();
    }

    public function hydrate(array $data): self
    {
        (isset ($data['id'])) ?$this->setId( intval($data['id'])): $this->setId(null);
        (isset ($data['matricule'])) ?$this->setMatricule( $data['matricule']): $this->setMatricule('');  
        (isset ($data['site_id'])) ?$this->setSiteId( intval($data['site_id'])): $this->setSiteId(null);
        (isset ($data['type'])) ?$this->setType( intval($data['type'])): $this->setType(0);   
        (isset ($data['reseau'])) ?$this->setReseau( intval($data['reseau'])): $this->setReseau(0);
        (isset ($data['description'])) ?$this->setDescription( $data['description']): $this->setDescription('');   
        (isset ($data['rotation'])) ?$this->setRotation( floatval($data['rotation'])): $this->setRotation(0.0);
        (isset ($data['amont_ids'])) ?$this->setAmontIds( $data['amont_ids']): $this->setAmontIds([]);
        (isset ($data['aval_ids'])) ?$this->setAvalIds( $data['aval_ids']): $this->setAvalIds([]);
        (isset ($data['coordinates_cc'])) ?$this->setCoordinatesCc( $data['coordinates_cc']): $this->setCoordinatesCc([]);
        (isset ($data['coordinates_wgs84'])) ?$this->setCoordinatesWgs84( $data['coordinates_wgs84']): $this->setCoordinatesWgs84([]);
        return $this;
    }

     public function getMatricule(): ?string
    {
        return $this->matricule;
    }
    public function setMatricule($matricule): self
    {
        $this->matricule = $matricule;
        return $this;
    }

    public function getAmontIds(): array
    {
        return $this->amontIds;
    }
    public function setAmontIds($amontIds): self
    {
        $this->amontIds = $amontIds;
        return $this;
    }

    public function getAvalIds(): array
    {
        return $this->avalIds;
    }
    public function setAvalIds($avalIds): self
    {
        $this->avalIds = $avalIds;
        return $this;
    }

    public function getSiteId(): ?int
    {
        return $this->siteId;
    }
    public function setSiteId($siteId): self
    {
        $this->siteId = $siteId;
        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }
    public function setType($type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription($description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getRotation(): float
    {
        return $this->rotation;
    }
    public function setRotation($rotation): self
    {
        $this->rotation = $rotation;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }
    public function getCoordinatesCc(): ?array
    {
        return $this->coordinatesCc;
    }
    public function setCoordinatesCc($coordinatesCc): self
    {
        $this->coordinatesCc = $coordinatesCc;
        return $this;
    }

    public function getCoordinatesWgs84(): ?array
    {
        return $this->coordinatesWgs84;
    }
    public function setCoordinatesWgs84($coordinatesWgs84): self
    {
        $this->coordinatesWgs84 = $coordinatesWgs84;    
        return $this;
    }

    public function getReseau(): int
    {
        return $this->reseau;
    }
    public function setReseau($reseau): self
    {
        $this->reseau = $reseau;
        return $this;
    }

  



}
