<?php
class PipeRepository
{
    private string $table = 'pipes';
    public function getAllBySiteId(int $site_id): array
    {
         $pg_PDO = PDOConnection::pg_get();
        // ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates récupère les coordonnées en TABLEAU
        $query = "SELECT id, 
                     site_id,
                     type,
                     description,
                     diametre,
                     reseau,
                     -- 1. Coordonnées natives CC47 (EPSG:3947) [[X, Y, Z], [X, Y, Z], ...]
                     ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates_cc,     
                     -- 2. Coordonnées WGS84 (EPSG:4326) pour Leaflet [[Lng, Lat, Z], [Lng, Lat, Z], ...]
                     ST_AsGeoJSON(ST_Transform(geom, 4326))::json->'coordinates' AS coordinates_wgs84
              FROM " . $this->table . " 
              WHERE site_id = :site_id AND geom IS NOT NULL AND NOT ST_IsEmpty(geom)";
            $statement = $pg_PDO->prepare($query);
            $statement->bindValue(':site_id', $site_id, PDO::PARAM_INT);
            $statement->execute();
        $data = [];
        while($row = $statement->fetch(PDO::FETCH_ASSOC)){
            
            $row['coordinates_cc'] = json_decode($row['coordinates_cc'], true);
            
            $row['coordinates_wgs84'] = json_decode($row['coordinates_wgs84'], true);
            $pipeGetDTO = new PipeGetDTO();
            $pipeGetDTO->hydrate($row); 

            $data[] = $pipeGetDTO->toArray();
        }
        return $data;
    }

    public function create(Pipe $Pipe): string
    {
        $pg_PDO = PDOConnection::pg_get();
        
        $query = "INSERT INTO " . $this->table . " 
                    (site_id, type, description, diametre, reseau,
                    geom)
                VALUES 
                    (:site_id, :type, :description, :diametre, :reseau,
                    ST_SetSRID(ST_GeomFromGeoJSON(:geom), 4326)
                    )";

        $statement = $pg_PDO->prepare($query);
        $statement->bindValue(':site_id', $Pipe->getSiteId(), PDO::PARAM_INT);
        $statement->bindValue(':type', $Pipe->getType(), PDO::PARAM_INT);
        $statement->bindValue(':description', $Pipe->getDescription(), PDO::PARAM_STR);
        $statement->bindValue(':diametre', $Pipe->getDiametre(), PDO::PARAM_STR);
        $statement->bindValue(':reseau', $Pipe->getReseau(), PDO::PARAM_INT);

        // Correction 2 : Construction d'un objet GeoJSON valide
        // PostGIS a besoin du "type" et de la structure complète
        $geoJSON = json_encode([
                'geom' => [
                    'type' => 'Point',
                    'coordinates' => $Pipe->getCoordinatesCc()
                ]
            ]); // besoin d'un tableau de 3 dimensions pour un POLYGON

        $statement->bindValue(':geom', $geoJSON, PDO::PARAM_STR);
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
                        diametre, 
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

        $pipeGetDTO = new PipeGetDTO();
        $pipeGetDTO->hydrate($row);

        return $pipeGetDTO->toArray();
    }

    public function getAll(): array
    {
        $pg_PDO = PDOConnection::pg_get();
        // ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates récupère les coordonnées en TABLEAU
        $query = "SELECT id, 
                        site_id,    
                        type,
                        description,
                        diametre,
                        reseau,
                        ST_AsGeoJSON(geom)::json->'coordinates' AS coordinates
                FROM " . $this->table . 
                " WHERE geom IS NOT NULL AND NOT ST_IsEmpty(geom)" ;
        $statement = $pg_PDO->query($query);
        $data = [];
        while($row = $statement->fetch(PDO::FETCH_ASSOC)){
            
            $row['coordinates'] = json_decode($row['coordinates'], true);
            $pipeGetDTO = new PipeGetDTO();
            $pipeGetDTO->hydrate($row);
            
            $data[] = $pipeGetDTO->toArray();
        }
        return $data;
    }

    public function update(PipeGetDTO $pipePatch): int
    {
        $pg_PDO = PDOConnection::pg_get();

        $query = "UPDATE " . $this->table . " 
                    SET 
                        type = :type,
                        description = :description,
                        diametre = :diametre
                    WHERE id = :id";
        $statement = $pg_PDO->prepare($query);
        $statement->bindValue(':type', $pipePatch->getType(), PDO::PARAM_STR);
        // $statement->bindValue(':classification', $pipePatch->getClassification(), PDO::PARAM_INT);
        $statement->bindValue(':description', $pipePatch->getDescription(), PDO::PARAM_STR);
        $statement->bindValue(':diametre', $pipePatch->getDiametre(), PDO::PARAM_STR);
        $statement->bindValue(':id', $pipePatch->getId(), PDO::PARAM_INT);
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
