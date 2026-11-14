<?php
class RasterRepository
{
    private string $table = 'rasters';

    public function getAllBySiteId(int $site_id): array
    {
         $pg_PDO = PDOConnection::pg_get();
        // ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates récupère les coordonnées en TABLEAU
        $query = "SELECT id, 
                        url,
                        date,
                        year,
                        description,
                        site_id
                FROM " . $this->table . " 
                WHERE site_id = :site_id" ;
            $statement = $pg_PDO->prepare($query);
            $statement->bindValue(':site_id', $site_id, PDO::PARAM_INT);
            $statement->execute();
        $data = [];
        while($row = $statement->fetch(PDO::FETCH_ASSOC)){

            $rasterGetDTO = new RasterGetDTO();
            $rasterGetDTO->hydrate($row);
            
            $data[] = $rasterGetDTO->toArray();
        }
        return $data;
    }

  public function create(Raster $Raster): string
{
    $pg_PDO = PDOConnection::pg_get();

    $query = "INSERT INTO " . $this->table . " 
                ( url, date, year, description, site_id )
            VALUES 
                (:url, :date, :year, :description, :site_id )";

    $statement = $pg_PDO->prepare($query);
    
    $statement->bindValue(':url', $Raster->getUrl(), PDO::PARAM_STR);
    $statement->bindValue(':date', $Raster->getDate(), PDO::PARAM_STR);
    $statement->bindValue(':year', $Raster->getYear(), PDO::PARAM_INT);
    $statement->bindValue(':description', $Raster->getDescription(), PDO::PARAM_STR);
    $statement->bindValue(':site_id', $Raster->getSiteId(), PDO::PARAM_INT);

    $statement->execute();

    return $pg_PDO->lastInsertId();
}

    public function getOne(int $id): array
    {
        $pg_PDO = PDOConnection::pg_get();
        // On spécifie les colonnes et on transforme la géométrie comme dans le getAll
        $query = "SELECT id,
                        site_id, 
                        type, 
                        description, 
                        rotation, 
                        reseau,
                        ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates 
                FROM " . $this->table . " 
                WHERE id = :id";

        $statement = $pg_PDO->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        // Sécurité : si l'ID n'existe pas, fetch renvoie false
        if (!$row) {
            return [];
        }
        // Décodage des coordonnées (car PostgreSQL renvoie une chaîne JSON)
        $row['coordinates'] = json_decode($row['coordinates'], true);

        $rasterGetDTO = new RasterGetDTO();
        $rasterGetDTO->hydrate($row);

        return $rasterGetDTO->toArray();
    }

    public function getAll(): array
    {
        $pg_PDO = PDOConnection::pg_get();
        // ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates récupère les coordonnées en TABLEAU
        $query = "SELECT id,
                        url,
                        date,
                        year,
                        description,
                        site_id
                FROM " . $this->table .  
                "" ;
        $statement = $pg_PDO->query($query);
        $data = [];
        while($row = $statement->fetch(PDO::FETCH_ASSOC)){
            // var_dump($row);  
      
            $rasterGetDTO = new RasterGetDTO();
            $rasterGetDTO->hydrate($row);
            
            $data[] = $rasterGetDTO->toArray();
        }
        return $data;
    }

    public function update(RasterGetDTO $rasterPatch): int
    {
        $pg_PDO = PDOConnection::pg_get();

        $query = "UPDATE " . $this->table . " 
                    SET 
                        description = :description
                    WHERE id = :id";
        $statement = $pg_PDO->prepare($query);
        // $statement->bindValue(':classification', $rasterPatch->getClassification(), PDO::PARAM_INT);
        $statement->bindValue(':description', $rasterPatch->getDescription(), PDO::PARAM_STR);
        $statement->bindValue(':id', $rasterPatch->getId(), PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }

        public function delete(int $id): void
        {
            $pg_PDO = PDOConnection::pg_get();
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $statement = $pg_PDO->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
        }



}
