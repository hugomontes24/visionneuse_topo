<?php
class SiteGetDTO
{
    public const DB_TABLE = 'site';
    private ?int $id = null;
    private string $name;
    private string $city;
    private ?string $img = null;
    // private ?PointsGetDTO $Points=null;
    private array $coordinates = []; // [lng, lat] PROVENANT DE GEOM
    private array $coordinatesCc = []; // [x, y, z] en CC44 (EPSG:3944)
    private array $coordinatesWgs84 = []; // [lng, lat, z] en WGS84 (EPSG:4326)
   
     public function hydrate(array $data): self
    {
        (isset ($data['id'])) ?$this->setId( intval($data['id'])): $this->setId(-1);
        (isset ($data['name'])) ?$this->setName( $data['name']): $this->setName('');
        (isset ($data['city'])) ?$this->setCity( $data['city']): $this->setCity('');
        (isset ($data['img'])) ?$this->setImg( $data['img']): $this->setImg('');
        if (!empty($data['geojson'])) {
                    $geoData = json_decode($data['geojson'], true);
                    // $geoData['coordinates'] vaut directement [-0.250341, 47.66270438888889]
                    $this->setCoordinates($geoData['coordinates'] ?? []);
                }
        (isset ($data['coordinates_cc'])) ?$this->setCoordinatesCc( $data['coordinates_cc']): $this->setCoordinatesCc([]);
        (isset ($data['coordinates_wgs84'])) ?$this->setCoordinatesWgs84( $data['coordinates_wgs84']): $this->setCoordinatesWgs84([]);

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'img' => $this->img,
            'coordinates' => $this->coordinates,
            'coordinatesCc' => $this->coordinatesCc,
            'coordinatesWgs84' => $this->coordinatesWgs84
        ];
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
    

    public function getImg(): string
    {
        return $this->img;
    }
    public function setImg(string $img): void
    {
        $this->img = $img;
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
}


/** 

 $PointsRepository = new PointsRepository();
        $data1 = $PointsRepository->getOne($data['points_id']);

        $PointsGetDTO = new PointsGetDTO();
        $PointsGetDTO->hydrate($data1);
        $this->Points= $PointsGetDTO;


    public function getPoints(): PointsGetDTO
    {
        return $this->Points;
    }
    public function setPoints(PointsGetDTO $Points): void
    {
        $this->Points = $Points;
    }

*/

/** 

 $PointsRepository = new PointsRepository();
        $data1 = $PointsRepository->getOne($data['points_id']);

        $PointsGetDTO = new PointsGetDTO();
        $PointsGetDTO->hydrate($data1);
        $this->Points= $PointsGetDTO;


    public function getPoints(): PointsGetDTO
    {
        return $this->Points;
    }
    public function setPoints(PointsGetDTO $Points): void
    {
        $this->Points = $Points;
    }

*/