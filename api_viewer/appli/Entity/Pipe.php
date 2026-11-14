<?php
class Pipe implements JsonSerializable
{
    private ?int $id = null;
    private ?int $site_id = null;
    private int $type=0; // 0=autre, 1=metal, 2=beton, 3=PVC,4=PEHD,5=GRP,6=FONTE,7=ACIER,8=ALUMINIUM
    private string $description;
    private int $diametre=0;
    private int $reseau=0; // 0=autre, 1=EP, 2=EU, 3=EA, 4=EV, 5=ECL, 6=GNV
    private ?array $coordinatesCc = null; // [[lng, lat], [lng, lat], ...]
    private ?array $coordinatesWgs84 = null; // [[lng, lat], [lng, lat], ...]

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'site_id' => $this->site_id,
            'type' => $this->type,
            'description' => $this->description,
            'diametre' => $this->diametre,
            'reseau' => $this->reseau,
            'coordinatesCc' => $this->coordinatesCc,
            'coordinatesWgs84' => $this->coordinatesWgs84
        ];
    }

    public function jsonSerialize(): mixed 
    {
        return $this->toArray();
    }

    public function hydrate(array $data): self
    {
        (isset ($data['id'])) ?$this->setId( intval($data['id'])): $this->setId(-1);
        (isset ($data['site_id'])) ?$this->setSiteId( intval($data['site_id'])): $this->setSiteId(-1);  
        (isset ($data['description'])) ?$this->setDescription( $data['description']): $this->setDescription('');   
        (isset ($data['type'])) ?$this->setType( intval($data['type'])): $this->setType(0);   
        (isset ($data['diametre'])) ?$this->setDiametre( intval($data['diametre'])): $this->setDiametre(0);
        (isset ($data['reseau'])) ?$this->setReseau( intval($data['reseau'])): $this->setReseau(0);
        (isset ($data['coordinates_cc'])) ?$this->setCoordinatesCc( $data['coordinates_cc']): $this->setCoordinatesCc([]);
        (isset ($data['coordinates_wgs84'])) ?$this->setCoordinatesWgs84( $data['coordinates_wgs84']): $this->setCoordinatesWgs84([]);
        return $this;
    }

    public function getSiteId(): ?int {return $this->site_id;}
    public function setSiteId($site_id): self
    {
        $this->site_id = $site_id;
        return $this;
    }

    public function getType(): int {return $this->type;}
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

    public function getDiametre(): int
    {
        return $this->diametre;
    }
    public function setDiametre(int $diametre): self
    {
        $this->diametre = $diametre;
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
    public function getCoordinatesCc(): array
    {
        return $this->coordinatesCc;
    }
    public function setCoordinatesCc($coordinatesCc): self
    {
        $this->coordinatesCc = $coordinatesCc;
        return $this;
    }
    public function getCoordinatesWgs84(): array
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
