<?php
class RasterGetDTO implements JsonSerializable
{
    private ?int $id = null;
    private ?string $url = '';
    private ?string $date = '';
    private int $year = 0; 
    private string $description;
    private ?int $siteId = null;
 
    public function toArray(): array
    {
       return [
            'id' => $this->id,
            'url' => $this->url,
            'date' => $this->date,
            'year' => $this->year,
            'description' => $this->description,
            'siteId' => $this->siteId,
            
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
        (isset ($data['url'])) ?$this->setUrl( $data['url']): $this->setUrl('');   
        (isset ($data['date'])) ?$this->setDate( $data['date']): $this->setDate(''); 
        (isset ($data['year'])) ?$this->setYear( intval($data['year'])): $this->setYear(0);
        (isset ($data['description'])) ?$this->setDescription( $data['description']): $this->setDescription('');   
        (isset ($data['site_id'])) ?$this->setSiteId( intval($data['site_id'])): $this->setSiteId(null);
        return $this;
    }


    public function getUrl(): string
    {
        return $this->url;
    }
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }


    public function getDate(): string
    {
        return $this->date;
    }
    public function setDate(string $date): self
    {
        $this->date = $date;
        return $this;
    }


    public function getYear(): int
    {
        return $this->year;
    }
    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }



    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

  

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }
    
  public function getSiteId(): ?int
    {
        return $this->siteId;
    }
    public function setSiteId(?int $siteId): self
    {
        $this->siteId = $siteId;
        return $this;
    }
    
  



}
