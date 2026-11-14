<?php
class PipeGetDTO implements JsonSerializable
{
    public const DB_TABLE = 'pipe';

    private ?int $id = null;
    private ?int $site_id = null;
    private int $type; // 0=plastique, 1=metal, 2=ciment
    private string $description;
    private int $diametre;
    private int $reseau; // 0=EP, 1=EU, 2=EA, 3=EV, 4=ECL, 5=GNV
    private array $coordinates = []; // [[lng, lat], [lng, lat], ...]


    public function jsonSerialize(): mixed 
    {
        return [
            'id' => $this->id,
            'site_id' => $this->site_id,
            'type' => $this->type,
            'description' => $this->description,
            'diametre' => $this->diametre,
            'reseau' => $this->reseau,
            'coordinates' => $this->coordinates
        ];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'site_id' => $this->site_id,
            'type' => $this->type,
            'description' => $this->description,
            'diametre' => $this->diametre,
            'reseau' => $this->reseau,
            'coordinates' => $this->coordinates
        ];
    }

    public function hydrate(array $data): self
    {
        (isset ($data['id'])) ?$this->setId( intval($data['id'])): $this->setId(-1);
        (isset ($data['site_id'])) ?$this->setSiteId( intval($data['site_id'])): $this->setSiteId(-1);
        // (isset ($data['layer'])) ?$this->setLayer( $data['layer']): $this->setLayer(''); 
        // (isset ($data['geom'])) ?$this->setGeom( $data['geom'])
        //                         : $this->setGeom([]);    
        (isset ($data['description'])) ?$this->setDescription( $data['description']): $this->setDescription('');   
        (isset ($data['type'])) ?$this->setType( intval($data['type'])): $this->setType(0);   
        (isset ($data['diametre'])) ?$this->setDiametre( intval($data['diametre'])): $this->setDiametre(0);
        (isset ($data['reseau'])) ?$this->setReseau( intval($data['reseau'])): $this->setReseau(0);
        (isset ($data['coordinates'])) ?$this->setCoordinates( $data['coordinates']): $this->setCoordinates([]);
        return $this;
    }

    public function getSiteId(): ?int
    {
        return $this->site_id;
    }
    public function setSiteId($site_id): self
    {
        $this->site_id = $site_id;
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
    public function getCoordinates(): array
    {
        return $this->coordinates;
    }
    public function setCoordinates($coordinates): self
    {
        $this->coordinates = $coordinates;
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
